<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    
    public function handle($request, Closure $next, ...$guards)
    {   
        if(Auth::check()){
            //dd($_COOKIE,$request->getRequestUri());
            
            $this->authenticate($request, $guards);
            return $next($request);

        }else{
            //setcookie("request_uri", $request->getPathInfo(),time()+3600);
            setcookie("request_uri", $request->getRequestUri(),time()+3600);
            //dd($_COOKIE,$request->getRequestUri());
            return redirect('/wallpaper');
        }
        
    }

    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
