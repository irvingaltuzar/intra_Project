<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmails;
use App\Jobs\SendEmail;
use App\Models\Location;
use App\Models\BucketLocation;
use App\Models\User;
use App\Models\Audit;
use App\Models\SubAudit;
use App\Models\Subgroup;
use App\Models\ValidationMetadata;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use App\Models\Notification;


class GeneralFunctionsRepository
{

    public function deleteFile($_params){

        if($_params['url'] != null){
            \File::delete($_params['url']);
        }

        return 1;

    }


    public function sendEmails($_params){

        if(env('APP_ENV_SEND_EMAIL') == 1){
            dispatch(new \App\Jobs\SendEmail($_params))->afterResponse();
        }else{
            $_params['recipient_emails'] = [auth::user()->email];
            //$_params['recipient_emails'] = ["eladio.perez@grupodmi.com.mx"];
            dispatch(new \App\Jobs\SendEmail($_params))->afterResponse();
        }

    }

    // Se obtienene los correo de los usuarios solicitados 
    public function getUsersByLocation($record_id,$_sub_seccion_id,$_specific_locations = null){

        // Se envia a todos los usuarios
        if($_specific_locations == "all"){

            $all_locations_name = Subgroup::get()->pluck('name');
            $all_users = User::whereIn('group',$all_locations_name)
                        ->whereNotNull('email')->where('email','like','%@%')
                        ->get()
                        ->pluck('email')->toArray();
            return $all_users;

        }else{
            $location_ids = BucketLocation::where('origin_record_id',$record_id)
                                            ->where('sub_seccion_id',$_sub_seccion_id)
                                            ->get()
                                            ->pluck('subgroups_id');


            // Se obtinen las ubicaciones obtenidas
            $locations_name = Subgroup::whereIn('id',$location_ids)->get()->pluck('name');
            if($locations_name != null){
                $users = User::whereIn('group',$locations_name)
                        ->whereNotNull('email')->where('email','like','%@%')
                        ->get()
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

    public function getUsersForNotificationByLocation($_record_id,$_sub_seccion_id,$_all_users = null){

        if($_all_users == 'all'){
            $all_locations_name = Subgroup::get()->pluck('name');
            $all_users = User::whereIn('group',$all_locations_name)
                        ->whereNotNull('email')->where('email','like','%@%')
                        ->get()
                        ->pluck('usuario')->toArray();
            return $all_users;
        }else{
            $location_ids = BucketLocation::where('origin_record_id',$_record_id)
                                        ->where('sub_seccion_id',$_sub_seccion_id)
                                        ->get()
                                        ->pluck('subgroups_id');


            // Se obtinen las ubicaciones obtenidas
            $locations_name = Subgroup::whereIn('id',$location_ids)->get()->pluck('name');
            if($locations_name != null){
                $users = User::whereIn('group',$locations_name)
                        ->whereNotNull('email')->where('email','like','%@%')
                        ->get()
                        ->pluck('usuario')->toArray();

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

    /*********** Se enviar la portada al servidor externo ********** */
    public function uploadImgServerGrupoDMI($_img,$_file_name){

        $file_upload= Storage::disk('ftp_dmi')->put($_file_name,\File::get($_img));

        /* $data['fileToUpload'] = curl_file_create(
            realpath($_img) ,$_img->getClientOriginalExtension(),$_file_name
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.grupodmi.com.mx/intranet/subir_imagen.php");
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($ch); */

    }

    public function getCoverNameImg($_url_photo){

        $array_url = explode('/',$_url_photo);
        $name = end($array_url);

        return $name;

    }

    public function addAudit($_params){

        $newAudit = new Audit();
        $newAudit->sub_seccion_id = $_params['sub_seccion_id'];
        $newAudit->username = Auth::user() != null ? Auth::user()->usuario : "publico";
        $newAudit->date_audit = Carbon::now();
        $newAudit->ip = $_params['ip'];
        $newAudit->event = $_params['event'];
        $newAudit->save();

        if($newAudit != null){
            $newSubAudit = new SubAudit();
            $newSubAudit->audit_id = $newAudit->id;
            $newSubAudit->comment = $_params['comment'];
            $newSubAudit->save();
        }

    }

    public function getIp(){
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
    }

    public function getLastCkeckFromSistemasAdministrativos(){

        $server="";
        if(env('APP_ENV_IS_PROD') == 0){ 
            //es dev
            $server="http://192.168.3.160:8000";
        }else{
            //prod
            $server="http://192.168.3.170:8000";
        }

        //$server="http://localhost:8001";

        $user = auth()->user()->usuario;
        $url = "$server/api/v1/rrhh/assist-control/last-check?user=$user"; // Se obtiene la categoría 5
		$headers = [
			'Authorization' => 'Bearer XFFZUjsxRklkdCwwODhWO3JMcVAjaW50cmFuZXQjR3JlY2Fz',
		];

        try {
            $client = new Client();
            $response = $client->post($url, [
                'headers' => $headers,
            ]);

            $statusCode = $response->getStatusCode();
            $content = $response->getBody()->getContents();
            $content = json_decode($content);

            if(isset($content->success) && $content->success == 1){
                if(sizeof($content->data) > 0){
                    $checked_of_month = $content->data;
                    $last_check = $checked_of_month[sizeof($checked_of_month)-1];
                    $date = Carbon::parse($last_check->fecha)->format("d-m-Y");
                    $time = Carbon::parse($last_check->checada)->format("H:i:s");
                    
                    return ["date"=> $date, "time"=>$time];
                }else{
                    return [];
                }
            }else{
                return [];
            }
        } catch (\Throwable $th) {
            return [];
        }
		
    }

    public function isAssignedDepartment($_usuario){
                
        $usuario = ValidationMetadata::where('key','like','%system_send_mail_by_department%',)
                                        ->where('value',$_usuario)->first();
        if($usuario != null){
            $aux_department = explode('-',$usuario->key);
            $department = $aux_department[1];
        }else{
            $department = null;
        }

        return $department;
    }

    function decryptPasswordCatMailers($_encrypted_password) {
        $temp_encryption_key = ValidationMetadata::where('key','system_secret_key-cat_mailers')->first();

        // Generar una clave de cifrado segura
        $encryption_key = md5($temp_encryption_key);
    
        // Decodificar la cadena cifrada
        $decoded = base64_decode($_encrypted_password);
    
        // Extraer el IV y la contraseña cifrada
        $iv = substr($decoded, 0, openssl_cipher_iv_length('aes-256-cbc'));
        $_encrypted_password = substr($decoded, openssl_cipher_iv_length('aes-256-cbc'));
    
        // Utilizar AES-256-CBC para descifrar la contraseña
        return openssl_decrypt($_encrypted_password, 'aes-256-cbc', $encryption_key, 0, $iv);
    }

    function encryptPasswordCatMailers($password) {

        // Generar una clave de cifrado segura
        $temp_encryption_key = ValidationMetadata::where('key','system_secret_key-cat_mailers')->first();
        $encryptionKey = md5($temp_encryption_key);
    
        // Usar AES-256-CBC para cifrar la contraseña
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encryptedPassword = openssl_encrypt($password, 'aes-256-cbc', $encryptionKey, 0, $iv);
    
        // Concatenar el IV al resultado para que pueda ser utilizado durante el descifrado
        return base64_encode($iv . $encryptedPassword);
    }

    public function getNotifications(){

        if(Auth::check()){

            $notifications = Notification::where('usuario_notified',Auth::user()->usuario)->orderBy('id','desc')->limit(50)->get();

            return response()->json(['success' => 1, 'data'=> $notifications], 200);

        }else{
            return ['success' => 0, 'message' => 'Por favor, inicia sesión para acceder a esta página.'];
        }

    }

    public function viewNotifications(){
        if(Auth::check()){
            $data_time = Carbon::now();
            //dd($data_time);
            $notifications = Notification::where('usuario_notified',auth()->user()->usuario)
                                            ->whereNull('read_at')
                                            ->get();
            if(sizeof($notifications) > 0){
                Notification::where('usuario_notified',auth()->user()->usuario)
                                            ->whereNull('read_at')
                                            ->update(['read_at' => $data_time]);
            }
            return response()->json(['success' => 1, 'data'=>null, 'message' => 'Notificaciones actualizadas.'], 200);

        }else{
            return ['success' => 0, 'message' => 'Por favor, inicia sesión para acceder a esta página.'];
        }
    }

    public function preparingNotificationCommunique($_data,$_record_id,$_sub_seccion_id,$_all_users = null){

        //Se obtienen los usuarios para las notificaciones
        if($_all_users == null){
            $users_notifieds = $this->getUsersForNotificationByLocation($_record_id,$_sub_seccion_id);
        }else{
            $users_notifieds = $this->getUsersForNotificationByLocation(null,null,$_all_users);
        }
        
        $_data['usuario_notified']= $users_notifieds;

        $notification = (Object) array(
            'usuario_notifying'=>'intranet', // Es el usuario que genera la notificación
            'usuario_notified'=>$users_notifieds, // Al usuario que le va aparecer la notificación
            //'usuario_notified'=>"eladio.perez",
            'message'=>$_data['title'],
            'type'=>'notification',
            'data'=>null,
            'link'=>$_data['link'],
        );

        $this->addNotification($notification);
    }

    public function filterBoldType($_text){

        // Encontrar la posición del primer "-"
        $posicionGuion = strpos($_text, '-');

        // Verificar si se encontró el guion
        if ($posicionGuion !== false) {
            $_text = "*" . $_text;

            // Insertar asterisco antes del primer "-"
            $_text = substr_replace($_text, "*", $posicionGuion, 0);
        }

        return $_text;

    }

    public function addNotification($_data){
        
        $message="";
        if(isset($_data->message) && strlen($_data->message) > 0){
            $message = $this->filterBoldType($_data->message);
        }else{
            $message = "*".(ucfirst(strtolower(auth()->user()->name)))."*, tienes una nueva notificación";
        }

        if(gettype($_data->usuario_notified) == "string"){
            $notification = new Notification();
            $notification->usuario_notifying =  $_data->usuario_notifying; // Es el usuario que genera la notificación
            $notification->usuario_notified = $_data->usuario_notified; // Al usuario que le va aparecer la notificación
            $notification->message = $message; // Mensaje de la notificación, lo que quieran que vaya en negrito, tienen que ponerlo entre asteriscos "*Jose*, hola",
            $notification->type = isset($_data->type) ? $_data->type : 'notification'; // El tipo si es notification(notificacion normal) | system | comment | reaction
            $notification->data = isset($_data->data) ? $_data->data : null; // Data extra que pueden agregar a la notificación y es genérica
            $notification->link = isset($_data->link) ? $_data->link : null; // Es el link a donde va llevar la notificación al darle clic
            $notification->save();
        }else if(gettype($_data->usuario_notified) == "array"){

            foreach($_data->usuario_notified as $usuario){
                $notification = new Notification();
                $notification->usuario_notifying =  $_data->usuario_notifying; // Es el usuario que genera la notificación
                $notification->usuario_notified = $usuario; // Al usuario que le va aparecer la notificación
                $notification->message = $message; // Mensaje de la notificación, lo que quieran que vaya en negrito, tienen que ponerlo entre asteriscos "*Jose*, hola",
                $notification->type = isset($_data->type) ? $_data->type : 'notification'; // El tipo si es notification(notificacion normal) | system | comment | reaction
                $notification->data = isset($_data->data) ? $_data->data : null; // Data extra que pueden agregar a la notificación y es genérica
                $notification->link = isset($_data->link) ? $_data->link : null; // Es el link a donde va llevar la notificación al darle clic
                $notification->save();
            }

        }

        

    }


}

