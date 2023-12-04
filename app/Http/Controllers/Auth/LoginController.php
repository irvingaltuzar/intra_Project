<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Permission;
use App\Models\Location;
use App\Models\Subgroup;
use App\Http\Controllers\TempSessionController;
use App\Repositories\GeneralFunctionsRepository;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->TempSession = new TempSessionController();
        $this->GeneralFunctionsRepository = new GeneralFunctionsRepository();
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function username() {
        return 'usuario';
    }
    public function password() {
        return 'password';
    }
    public function login(Request $request) {
        $request->validate([
            'usuario' => 'required',
            'password' => 'required'
        ]);


        $credentials = $request->only($this->username(), $this->password());
        $username = $credentials[$this->username()];
        $password = $credentials[$this->password()];

        $user_format = ($_SERVER['HTTP_HOST'] == '192.168.3.121:8006' ? env('LDAP_USER_FORMAT') : 'dmi.local');
        $userdn = sprintf($user_format, $username);
        $servidor_LDAP = ($_SERVER['HTTP_HOST'] == '192.168.3.121:8006' ? env('LDAP_HOSTS') : '192.168.3.220');
        $conectado_LDAP = ldap_connect($servidor_LDAP);
        ldap_set_option($conectado_LDAP, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($conectado_LDAP, LDAP_OPT_REFERRALS, 0);

        try {

            if(ldap_bind($conectado_LDAP,$username ."@".  $user_format, $password)){

                if (Auth::attempt(['usuario' =>$username , 'password' => 'OupQrqJT'])){

                    $this->TempSession->addUserTempSession($credentials);

                    $permission = Permission::with("rol.roles_items")->where('vw_users_usuario',Auth::user()->usuario)->first();
                    /* Permission::join('roles as r','r.id','=','permissions.roles_id')
                    ->where('permissions.vw_users_usuario',Auth::user()->usuario)->first(); */

                    // Se obtine el grupo del usuario
                    $location = Subgroup::where('name',Auth::user()->group)->pluck('id')->first();

                    Session(['location_user' => $location]);


                    $permissions = ["seccion"=>[], "permissions"=>[]];
                    //return $permission;
                    foreach($permission->rol->roles_items as $rol){

                        if(in_array($rol->sub_seccion->seccion->name, $permissions['seccion']) == false){
                            $permissions['seccion'][] = $rol->sub_seccion->seccion->name;
                        }
                        $permissions['permissions'][] = $rol->sub_seccion->name;
                    }

                    Session(['permission' => $permissions]);


                    $params=[
                        "sub_seccion_id" =>20,
                        "ip" => $this->GeneralFunctionsRepository->getIp(),
                        "event" => __FUNCTION__,
                        "comment" => "Ingreso al Login",
                    ];
                    $this->GeneralFunctionsRepository->addAudit($params);

                    if(isset($_COOKIE['login_admin']) && $_COOKIE['login_admin'] == 1){
                        return redirect()->route('admin');
                    }else{
                        return redirect()->route('home');
                    }

                } else{
                    throw ValidationException::withMessages([
                        'usuario' => ['Tu usuario no existe en nuestra base de Datos']
                    ]);
                }
            }
        } catch (\Throwable $th) {

            throw ValidationException::withMessages([
                'usuario' => ['Las crendeciales son incorrectas, intentalo nuevamente']
            ]);

        }
    }

    public function loginAD(Request $request){

        $request->validate([
            'user' => 'required',
        ],
        [
            'user.required' => "Error al obtener el dato"
        ]);

        $username = $request->user;
        if (Auth::attempt(['usuario' =>$username , 'password' => 'OupQrqJT'])){

            $credentials['usuario']=$username;
            $this->TempSession->addUserTempSession($credentials);

            $permission = Permission::with("rol.roles_items")->where('vw_users_usuario',Auth::user()->usuario)->first();
            /* Permission::join('roles as r','r.id','=','permissions.roles_id')
            ->where('permissions.vw_users_usuario',Auth::user()->usuario)->first(); */

            // Se obtine el grupo del usuario
            $location = Subgroup::where('name',Auth::user()->group)->pluck('id')->first();

            Session(['location_user' => $location]);


            $permissions = ["seccion"=>[], "permissions"=>[]];
            //return $permission;
            if($permission != null){
                foreach($permission->rol->roles_items as $rol){

                    if(in_array($rol->sub_seccion->seccion->name, $permissions['seccion']) == false){
                        $permissions['seccion'][] = $rol->sub_seccion->seccion->name;
                    }
                    $permissions['permissions'][] = $rol->sub_seccion->name;
                }
            }


            Session(['permission' => $permissions]);


            $params=[
                "sub_seccion_id" =>20,
                "ip" => $this->GeneralFunctionsRepository->getIp(),
                "event" => __FUNCTION__,
                "comment" => "Ingreso por el Auto Login",
            ];
            $this->GeneralFunctionsRepository->addAudit($params);

            //dd("ddd");
            return ["success" => 1];
        } else{
            return ["success" => 0];
        }

    }


    public function logout(){

        $ruta_dinamica = "/login";
        if(isset(request()->login_admin) && request()->login_admin == true){
            setcookie("login_admin", true,time()+3600);
        }else{
            setcookie('login_admin',0, time() - 3600);

        }

        if(isset(request()->redirect_home_public) && request()->redirect_home_public == true){
            $ruta_dinamica = "/";
        }


        $params=[
            "sub_seccion_id" =>20,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" => __FUNCTION__,
            "comment" => "Cerrar sesión",
        ];
        $this->GeneralFunctionsRepository->addAudit($params);

        $this->TempSession->expiredTempSession();
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect($ruta_dinamica);
    }

}
