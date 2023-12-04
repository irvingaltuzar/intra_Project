<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class PublicController extends Controller
{

    public function expire_session_inactivity(){

        return view('auth.expire_session');

    }

    public function wallpaperWelcome(){
        return view('layouts.welcome');
    }
    
}
