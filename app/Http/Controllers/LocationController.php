<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Location;
use App\Models\Subgroup;
use App\Models\PermissionPostLocation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class LocationController extends Controller
{
    //
    public function __construct(){
        //$this->middleware('auth');
    }

    public function all(){
        /* $locations = Location::all(); */
        $auth_user = Auth::user()->usuario;
        $is_master = PermissionPostLocation::where('vw_locations_name','MASTER-USER')->where('vw_users_usuario',$auth_user)->first();

        if($is_master != null){
            $locations = Location::orderBy('name','asc')->get();
        }else{
            $locations = Location::Join('permission_post_locations','permission_post_locations.vw_locations_name','=','vw_locations.name')
            ->where('permission_post_locations.vw_users_usuario',$auth_user)
            ->whereNull('permission_post_locations.deleted_at')
            ->whereNull('vw_locations.deleted_at')
            ->select('vw_locations.*')
            ->orderBy('name','asc')
            ->get();
        }
        return ['success' => 1,'data' => $locations];
    }

    public function subgroups(){
        $subgroups = Subgroup::whereNotNull("name")->get();
        return ['success' => 1,'data' => $subgroups];
    }
}
