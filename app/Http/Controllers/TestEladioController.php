<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Communique;
use App\Models\File;
use App\Models\CommuniqueFile;
use App\Models\Comment;
use App\Models\UsersPrueba;
use App\Models\Permission;
use App\Models\Project_Dmi;
use App\Models\Location;
use App\Models\BucketLocation;
use App\Models\Audit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Repositories\GeneralFunctionsRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmails;
use App\Http\Controllers\CollaboratorController;
use Illuminate\Support\Facades\Route;
use App\Jobs\SendEmail;
use App\Models\AreaNotice;
use App\Models\Promotion;
use App\Models\ValidationMetadata;
use App\Models\CatMailer;
use Illuminate\Support\Facades\Config;
use Swift_SmtpTransport;
use Swift_Mailer;



class TestEladioController extends Controller
{
    //

    public function __construct(){
        /*
        $this->CollaboratorController = new CollaboratorController(); */
        $this->GeneralFunctionsRepository = new GeneralFunctionsRepository();


    }

    public function index(){
        
        dd(Session::all());
    }

    public function uploadFTP(){
        $fileName = "1681512986_area_notice.png";
        //$fileExist = Storage::disk('ftp_dmi')->exists($fileName);
        $contents = Storage::disk('ftp_dmi')->get($fileName);
        dd($contents);
    }

    public function enviarNoticias(){
        $record = AreaNotice::find(18);

        if($record != null){
            $users_email = $this->GeneralFunctionsRepository->getUsersByLocation($record->id,11);
            $cover_img_name = $this->GeneralFunctionsRepository->getCoverNameImg($record->photo);

            $mail_data = [
                'sub_seccion' => "area_notice",
                'recipient_emails' =>$users_email,
                'subject' => "Avisos de Área - $record->title",
                'link' => "http://192.168.3.170:8006/news",
                'title' => "Avisos de Área - $record->title",
                'description' => $record->description,
                'photo' =>"https://www.grupodmi.com.mx/intranet/img/comunicados/$cover_img_name",
            ];
            $this->GeneralFunctionsRepository->sendEmails($mail_data);
        }
    }



    public function getUsersByLocation($record_id,$_sub_seccion_id,$_specific_locations = null){

        // Se envia a todos los usuarios
        if($_specific_locations == "all"){

            $all_locations_name = Location::get()->pluck('name');
            $all_users = User::whereIn('location',$all_locations_name)
                        ->whereNotNull('email')->where('email','like','%@%')
                        ->get()
                        ->pluck('email')->toArray();
            return $all_users;

        }else{
            $location_ids = BucketLocation::where('origin_record_id',$record_id)
                                            ->where('sub_seccion_id',$_sub_seccion_id)
                                            ->get()
                                            ->pluck('locations_id');


            // Se obtinen las ubicaciones obtenidas
            $locations_name = Location::whereIn('id',$location_ids)->get()->pluck('name');
            if($locations_name != null){
                $users = User::whereIn('location',$locations_name)->whereNotNull('email')->where('email','like','%@%')->get()
                        ->pluck('email')->toArray();

                if($users != null){
                    return $users;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }



    }

    public function saveUploadImageServerDMI(Request $request){

        // Se almacena la imagen
        $file = $request->file('photo');
        $extension = $file->getClientOriginalExtension();
        $fileName = $request->name_img.'.'.$extension;
        $path = "/intranet/img/comunicados/";
        $file_upload= Storage::disk('ftp_dmi')->put($fileName,\File::get($file));

        return $file_upload;

    }

    /*
    public function saveUploadImageServerDMI(Request $request){

        // Se almacena la imagen
        $file = $request->file('photo');
        $extension = $file->getClientOriginalExtension();
        $fileName = $request->name_img.'.'.$extension;

        $data['fileToUpload'] = curl_file_create(
            realpath($request->file('photo')) ,$request->file('photo')->getClientOriginalExtension(),$fileName
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.grupodmi.com.mx/intranet/subir_imagen.php");
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($ch);
        if($result === false){
            echo 'Curl error: ' . curl_error($ch);
            dd(curl_error($ch));
        }else{
            echo 'Operación completada sin errores';

        }



        return ["success" => 1, "data" => $result];

    }
    */

    public function sendMail(){

        $record = Communique::find(2914);
        $this->GeneralFunctionsRepository = new GeneralFunctionsRepository();
        $users_email = ["eladio.perez@grupodmi.com.mx","carlos.montejo@grupodmi.com.mx"];
        $cover_img_name = null;

        $_params = [
            'sub_seccion' => "communication",
            'recipient_emails' =>$users_email,
            'subject' => "Comunicado - $record->title",
            'link' => url('dmi_comunicados'),
            'title' => "Comunicado - $record->title",
            'description' => $record->description,
            'photo' =>"",
            'link_data' => $record->link,
        ];
        //dd($_params);
        // SendEmail::dispatch($_params);
        dispatch(new \App\Jobs\SendEmail($_params))->afterResponse();
    }

    public function all_location(){
        $all_locations_name = Location::get()->pluck('name');
        $all_users = User::whereIn('location',$all_locations_name)
                        ->get()
                        ->pluck('email')->toArray();
        dd($all_users);
    }

    public function uploadImgServerGrupoDMI($_img,$_file_name){

        $data['fileToUpload'] = curl_file_create(
            realpath($_img) ,$_img->getClientOriginalExtension(),$_file_name
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.grupodmi.com.mx/intranet/subir_imagen.php");
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        if(curl_exec($ch) === false){
            echo 'Curl error: ' . curl_error($ch);
            dd(curl_error($ch));
        }else{
            echo 'Operación completada sin errores';
            $result = curl_exec($ch);
            return $result;
        }



    }

    public function testGeneral(){
        $projects = Project_Dmi::leftjoin('vw_locations', 'vw_locations.id','=','project_dmi.vw_locations_id')
                    ->select('project_dmi.*','vw_locations.photo')
                    ->orderBy('creation_date','asc')
                    ->get();

        return $projects;
    }

    public function permissions(){

        dd(Auth::user()->usuario);
        $permissions = Permission::join('roles as r','r.id','=','permissions.roles_id')
        ->where('permissions.vw_users_usuario',Auth::user()->usuario)->get();

        Session(['permissions' => $permissions]);

        dd(Session::get('permissions')[0]['name']);

        return $permissions;
    }

    public function getComments(){
        $comments = Comment::with(['childrenComments'])->get();
        return $comments;
    }

    public function test_comunicados(){
        $hoy = Carbon::today();
        $communiques_consejo=Communique::where('type','consejo')->
        whereDate('expiration_date', '>=', $hoy)->orderBy('id')->get();
        $communiques_organizacional=Communique::where('type','organizacional')->
        whereDate('expiration_date', '>=', $hoy)->orderBy('id')->get();
        $communiques_institucional=Communique::where('type','institucional')->
        whereDate('expiration_date', '>=', $hoy)->orderBy('id')->get();

        return view('dmi_comunicados.dmi_comunicados',
        [
            'communiques_consejo'=>$communiques_consejo,
            'communiques_organizacional'=>$communiques_organizacional,
            'communiques_institucional'=>$communiques_institucional
        ]);
    }

    public function pintar_hijos($ramas,$p_linea){

        forEach($ramas as $item){
            $linea = $this->pintar_linea($p_linea);
            echo $linea.' => '.$item->name.'<br>';
            if(sizeof($item->organigrama) > 0){
                $this->pintar_hijos($item->organigrama,$linea);
            }
        }

    }

    public function pintar_linea($linea){
        $actual_len = strlen($linea)+1;
        $new_linea = "";

        for ($i=0; $i <=$actual_len ; $i++) {
            $new_linea = $new_linea . '=';
        }

        return $new_linea;
    }


    /* public function getUsersByLocation($_location = 13){
        // Se seteo la $_location=13, xq el id 13 son todas las ubicaciones

        $user;

        if($_location == 13){
            $user = User::all();
        }else{
            // Se obtine la ubicación obtenida
            $location = Location::where('id',$_location)->first();
            if($location != null){
                $users = User::where('location',$location->name)->get();
                if($users != null){
                    //dd($users);
                    return $users;
                }else{
                    return false;
                }

            }else{
                return false;
            }
        }

        dd($user);

    } */

}
