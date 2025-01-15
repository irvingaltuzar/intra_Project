<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class UserController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }

    public function all(){
        $users = User::orderBy('name','asc')->get();

        return ['success' => 1,'data' => $users];
    }

    public function promotionAll(){
        $users = DB::select('select * from vw_promotions_personal order by name asc');

        return ['success' => 1,'data' => $users];
    }

}
