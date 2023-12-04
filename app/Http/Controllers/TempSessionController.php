<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\TempSession;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\GeneralFunctionsRepository;

class TempSessionController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
        $this->GeneralFunctionsRepository = new GeneralFunctionsRepository();
    }


    public function addUserTempSession($credentials){

        // Se eliminan las sesiones que hayan quedado abiertas
        $tempSessions = TempSession::where('temp_user',$credentials['usuario'])->get();
        foreach($tempSessions as $session){
            $session->delete();
        }

        $new = new TempSession();
        $new->temp_user = $credentials['usuario'];
        $new->temp_password = encrypt("OupQrqJT");
        $new->personal_token = Uuid::uuid4();
        $new->save();

    }

    public function expiredTempSession(){

        $params=[
            "sub_seccion_id" =>20,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" => __FUNCTION__,
            "comment" => "expiro sesión",
        ];
        $this->GeneralFunctionsRepository->addAudit($params);

        if(isset(auth()->user()->usuario)){
            $record = TempSession::where('temp_user',auth()->user()->usuario)->first();
            if($record != null){
                $record->is_expired = 1;
                $record->save();
                $record->delete();
            }
        }



    }

    public function redirectAlfa(){
        $credentials = TempSession::where('temp_user',auth()->user()->usuario)->first();

        if($credentials != null){
            if($credentials->is_login == 0){
                return redirect(env('HOST_TEMP_SESSION_ALFA').'/auth/intranet/'.$credentials->personal_token);
            }else{
                return redirect(env('HOST_TEMP_SESSION_ALFA'));
            }
        }else{
            return redirect('logout');
        }

    }

    public function expire_session_inactivity(){

        $params=[
            "sub_seccion_id" =>20,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" => __FUNCTION__,
            "comment" => "Expiro sesión por inactividad",
        ];
        $this->GeneralFunctionsRepository->addAudit($params);

        $this->expiredTempSession();
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return view('auth.expire_session');

    }

    public function expire_session_inactivity_alfa(){
        $this->expiredTempSession();
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return 1;

    }


}
