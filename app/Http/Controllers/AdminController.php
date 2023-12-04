<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminController extends Controller
{
    //
    public function __construct(){
        $this->middleware('admin');
    }

    public function index(){
        return view('admin.home');
    }
}
