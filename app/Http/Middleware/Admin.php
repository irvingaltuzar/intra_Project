<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Rol;
use App\Repositories\GeneralFunctionsRepository;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        // Revisamos que estemos logueados primeramente
        if(Auth::check()){
            // Revisamos que cuente con el rol para acceder al admin
            if(Session::has('permission')){
                $permission = Session::get('permission.permissions');
                if(sizeof($permission) > 0){
                    // Revisamos si el usuario esta asociado a un correo en especifico para enviar avisos de area y lo cargamos el departamento a la session
                    $generalFunctionsRepository = new GeneralFunctionsRepository();
                    $department = $generalFunctionsRepository->isAssignedDepartment(Auth::user()->usuario);
                    Session(['department_assigned_mail' => $department]);
                    return $next($request);
                }else{
                    return redirect()->route('home');
                }
            }else{
                return redirect()->route('home');
            }
        }else{
            return redirect()->route('login');
        }
    }
}
