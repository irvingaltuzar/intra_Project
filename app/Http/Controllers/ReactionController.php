<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\Comment;
use App\Models\PublicationSection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Repositories\GeneralFunctionsRepository;
use App\Models\User;
use App\Models\PostReactions;
use App\Models\Promotion;
use App\Models\Notification;

class ReactionController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
        $this->GeneralFunctionsRepository = new GeneralFunctionsRepository();
    }

    public function add_ghost_publication($data){
        // El add_ghost_publication => Es para una publicación que depende de una seccion independiente al blog
        $aux_key_publication = null;

        /* Generar los keys auxiliares de publicacion extras para modulos sin publicacion */
        $aux_key_publication = $this->generation_aux_key_publication($data);

        $exist_publication = Publication::where('vw_users_usuario',$data->receiver_vw_users_usuario)
                                            ->where('aux_key_publication',$aux_key_publication)
                                            ->first();


        if($exist_publication != null){
            return $exist_publication;
        }else{
            $section = PublicationSection::where('id',$data->publication_section)->first();

            $publication = new Publication();
            $publication->publications_section_id = $section->id;
            $publication->vw_users_usuario = str_replace('_','.',$data->receiver_vw_users_usuario);
            $publication->aux_key_publication = $aux_key_publication;
            $publication->description = $section->name;
            $publication->save();

            return $publication;
        }

    }

    public function generation_aux_key_publication($data){
        $aux_key_publication = null;

        if($data->publication_section == 1){
            //Cumpleaños
            $year = date('Y');
            $birthday = explode("-",$data->birthday_date);
            $aux_key_publication ="{$data->receiver_vw_users_usuario}_{$year}-{$birthday[1]}-{$birthday[2]}";

        }

        return $aux_key_publication;
    }


    public function add_reaction(Request $request){
        //Se verifica si tiene publicación
        $temp_publication_id = 0;
        $new_reaction = null;
        $user = null;
        $message = "";
        $link = "";

        if(isset($request->publication_id) && $request->publication_id > 0){

            // get publication_section 
            $publication_section = PublicationSection::where('name',$request->section)->first();



            $temp_publication_id = $request->publication_id;
            $new_reaction = new PostReactions();
            $new_reaction->publications_section_id = $publication_section->id;
            $new_reaction->publications_id = $request->publication_id;
            $new_reaction->vw_users_usuario = auth()->user()->usuario;
            $new_reaction->type_reaction = $request->type_reaction;
            $new_reaction->save();

        }else{
            // No tiene publicacion asignada y se tiene que crear una nueva
            $publication = $this->add_ghost_publication($request);

            $temp_publication_id = $publication->id;

            $new_reaction = new PostReactions();
            $new_reaction->publications_id = $publication->id;
            $new_reaction->publications_section_id = $publication->publications_section_id;
            $new_reaction->vw_users_usuario = auth()->user()->usuario;
            $new_reaction->type_reaction = $request->type_reaction;
            $new_reaction->save();
            
        }

        if($new_reaction != null){

            if($request->section == 'promotions'){
                $promotion = Promotion::find($request->publication_id);
                $user = User::where('full_name',$promotion->user_name)->first();
                
                $message = "*".ucwords(strtolower(auth()->user()->name))."* te felicito por tu nuevo ascenso.";
                $link = "collaborators?section=pane-promotions";


            }else if($request->section == 'cumpleaños'){
                $user = User::join('publications','publications.vw_users_usuario','=','vw_users.usuario')
                            ->where('publications.id',$temp_publication_id)
                            ->first();

                $message = "*".ucwords(strtolower(auth()->user()->name))."* te felicito por tu cumpleaños.";
                $link = "/collaborators#link_".$user->usuarioId;

            }else{
                $user = User::join('publications','publications.vw_users_usuario','=','vw_users.usuario')
                            ->where('publications.id',$temp_publication_id)
                            ->first();
            }
            
            //Se revisa si el usuario existe
            if($user != null){
                $notification = (object) array(
                    "usuario_notifying" => auth()->user()->usuario, // Es el usuario que genera la notificación
                    "usuario_notified" => $user->usuario,
                    "message" => $message,
                    "type" => "reaction",
                    "data" => $request->type_reaction, // Es para saber que tipo de reaccion fue
                    "link" => $link,
                );
                $this->GeneralFunctionsRepository->addNotification($notification);
            }
        }

        $total_reaction = PostReactions::where('publications_id',$temp_publication_id)->count();
        $response = ['success' => 1, 'reaction'=> $new_reaction,'total_reactions' =>$total_reaction];

        return $response;
    }


    public function getListReactions(Request $request){

        if(isset($request->publication) && $request->publication > 0 && isset($request->section)){

            $publication_section = PublicationSection::where('name',$request->section)->first();

            $reactions = PostReactions::with('user')
                                        ->where('publications_section_id',$publication_section->id)
                                        ->where('publications_id',$request->publication)->get();
            
            return ['success'=> 1,'data'=> $reactions];
        }else{
            return ['success'=>0,'data'=>[],'No se encontro la publicación solicitada'];
        }

        return ($reactions);
        

    }





}
