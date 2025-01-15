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
use App\Models\Notification;

class CommentController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
        $this->GeneralFunctionsRepository = new GeneralFunctionsRepository();
    }

    public function get_list_comments(Request $request){

        $response= [];
        $publication_id = null;
        $comments = null;

        if(isset($request->publication_id) && $request->publication_id != null){
            $publication_id = $request->publication_id;
            $publication = Publication::where('id',$publication_id)->first();

            if($publication != null){
                $comments = Comment::where('publications_id',$publication_id)->simplePaginate(10);
                $response = ['success' => 1,'data'=>$comments];

            }else{
                $response = ['success' => 0, 'error'=>['code'=>1,'message'=>'Sin comentarios, se tú el primero en comentar.'],'data'=>[]];
            }

        }else{
            $key_publication = $this->get_aux_key_publication($request);

            $publication = Publication::where('aux_key_publication',$key_publication)->first();

            if($publication != null){
                $comments = Comment::where('publications_id',$publication->id)->simplePaginate(10);
                $response = ['success' => 1,'data'=>$comments];

            }else{
                $response = ['success' => 0, 'error'=>['code'=>1,'message'=>'Sin comentarios, se tú el primero en comentar.'],'data'=>[]];
            }

        }

        if($comments != null){
            return response()->json($response);
        }else{
            return $response = ['success' => 0, 'error'=>['code'=>1,'message'=>'Sin comentarios, se tú el primero en comentar.'],'data'=>[]];
        }




    }

    public function add_comments(Request $request){

        $response = null;
        $temp_publicacion_id = null;

        if(isset($request->publication_id) && $request->publication_id > 0){

            $temp_request= new Request();
		    $temp_request->setMethod('POST');
            $parent_comments_id = (isset($request->parent_comments) && $request->parent_comments > 0) ? $request->parent_comments : null;
		    $temp_request->query->add(['publications_id' => $request->publication_id,
                                        'parent_comments_id' => $parent_comments_id,
                                        'comment' => $request->message,]);

            $new_comment = $this->save_comments($temp_request);
            $temp_publicacion_id = $request->publication_id;

            $response = ['success' => 1, 'comment'=> $new_comment];

        }else{
            // No tiene publicacion asignada y se tiene que crear una nueva
            $publication = $this->add_ghost_publication($request);
            $parent_comments_id = (isset($request->parent_comments) && $request->parent_comments > 0) ? $request->parent_comments : null;

            $data = [
                'publications_id' => $publication->id,
                'parent_comments_id' => $parent_comments_id,
                'comment' => $request->message,
            ];

            $temp_request= new Request();
		    $temp_request->setMethod('POST');
		    $temp_request->query->add($data);

            $new_comment = $this->save_comments($temp_request);
            $temp_publicacion_id = $publication->id;


            $response = ['success' => 1, 'comment'=> $new_comment];

        }

        // Se revisa si la publicación envia correos
        $publication = Publication::where('id',$temp_publicacion_id)->first();
        $user = User::join('publications','publications.vw_users_usuario','=','vw_users.usuario')
            ->where('publications.id',$temp_publicacion_id)
            ->first();

        $this->GeneralFunctionsRepository->addNotification((object) array(
                                    "usuario_notifying" => auth()->user()->usuario, // Es el usuario que genera la notificación
                                    "usuario_notified" => $publication->vw_users_usuario, // Al usuario que le va aparecer la notificación
                                    "message" => "*".ucwords(strtolower(auth()->user()->name))."* agregó un comentario de cumpleaños.",
                                    "type" => "comment",
                                    "data" => null,
                                    "link" => "/collaborators?section=pane-birthday#link_".$user->usuarioId,
                                ));

        //Cumpleaños
        if($publication->publications_section_id == 1){
            $this->sendHappyCommentNotification($publication, $request->message);
        }


        return response()->json($response);

    }

    public function sendHappyCommentNotification($_publication = null, String $_message){

        //Se verifica que el comentario no sea de la misma persona que cumple años
        //dd($_publication->vw_users_usuario,auth()->user()->usuario);
        $temp_user = str_replace('_','.',$_publication->vw_users_usuario);

        if($temp_user != auth()->user()->usuario){
            $user = User::where('usuario',$temp_user)->first();
            $mail_data = [
                'sub_seccion' => "birthday",
                'recipient_emails' => $user->email,
                'subject' => "Colaboradores - Felicitaciones",
                'message' => $_message,
                'link' => url('collaborators'),
                'birthday_boy' => $user->name.' '.$user->last_name,
                'congratulator_boy' => auth()->user()->name.' '.auth()->user()->last_name,
            ];

            $this->GeneralFunctionsRepository->sendEmails($mail_data);
        }

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

    public function save_comments(Request $request){

        $publication_id = $request->parent_comments_id != null ? null : $request->publications_id;

        $comment = new Comment();
        $comment->publications_id = $publication_id ;
        $comment->vw_users_usuario = auth()->user()->usuario;
        $comment->parent_comments_id = $request->parent_comments_id;
        $comment->comment = $request->comment;
        $comment->save();

        return $comment;
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

    public function get_aux_key_publication($data){
        $aux_key_publication = null;

        if($data->type_publication == "birthday"){
            //Cumpleaños
            $year = date('Y');
            $birthday = explode("-",$data->birthday_date);
            $aux_key_publication ="{$data->vw_users_usuario}_{$year}-{$birthday[1]}-{$birthday[2]}";

        }

        return $aux_key_publication;
    }

    public function add_reaction(Request $request){
        //Se verifica si tiene publicación
        $temp_publication_id = 0;
        $new_reaction = null;

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

            $user = User::join('publications','publications.vw_users_usuario','=','vw_users.usuario')
            ->where('publications.id',$temp_publication_id)
            ->first();

            $notification = (object) array(
                "usuario_notifying" => auth()->user()->usuario, // Es el usuario que genera la notificación
                "usuario_notified" => $user->usuario,
                "message" => "*".ucwords(strtolower(auth()->user()->name))."* te felicito por tu cumpleaños.",
                "type" => "reaction",
                "data" => "felicitaciones",
                "link" => "/collaborators#link_".$user->usuarioId,
            );
            $this->GeneralFunctionsRepository->addNotification($notification);
        }

        $total_reaction = PostReactions::where('publications_id',$temp_publication_id)->count();
        $response = ['success' => 1, 'reaction'=> $new_reaction,'total_reactions' =>$total_reaction];

        return $response;
    }


    public function getListReactions(Request $request){

        if(isset($request->publication) && $request->publication > 0){
            $reactions = PostReactions::with('user')->where('publications_id',$request->publication)->get();
            
            return ['success'=> 1,'data'=> $reactions];
        }else{
            return ['success'=>0,'data'=>[],'No se encontro la publicación solicitada'];
        }

        return ($reactions);
        

    }





}
