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
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;


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
            dispatch(new \App\Jobs\SendEmail($_params))->afterResponse();
        }

    }

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


}

