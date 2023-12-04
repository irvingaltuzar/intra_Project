<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Rol;
use App\Repositories\GeneralFunctionsRepository;

class PermissionController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
        $this->sub_seccion_id=18;
        $this->GeneralFunctionsRepository = new GeneralFunctionsRepository();
    }

    public function permissionShow(){
        $temp_request = new Request();
        $temp_request->setMethod('GET');
        $temp_request->query->add(['limit'=>10]);
        $list = $this->permissionList($temp_request);
        $compact = compact('list');

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>$this->sub_seccion_id,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Listado",
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return view('admin.settings.permissions.list')->with($compact);

    }

    public function permissionList(Request $request){

        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '10';
        $search = isset($request->search) ? json_decode(json_encode($request->search),false) : '';
        $rol_list= [];

        if(isset($search->isSearch) && strlen($search->text) > 0){

            $rol_list = Permission::with(['rol','user'])->whereRelation('rol',function($q) use ($search){
                                                            return $q->orWhere('name', 'like', "%$search->text%");
                                                        })
                                                        ->orWhereRelation('user',function($q) use ($search){
                                                            return $q->orWhere('name', 'like', "%$search->text%")->orWhere('last_name', 'like', "%$search->text%");
                                                        })
            ->orderBy('created_at',$order_by)->Paginate($limit);
        }else{

            $rol_list = Permission::with(['rol','user'])->orderBy('created_at',$order_by)
            ->Paginate($limit);
        }

        $rol_list->setPath('/admin/settings/permission-list');

        return $rol_list;
    }

    public function subSeccionAll(Request $request){
        $data= SubSeccion::where('hidden',1)->orderBy('seccion_id','asc')->get();

        return ["success" => 1, "data" => $data];

    }

    public function permissionDelete($_id){

        if(isset($_id) && $_id > 0){
            $record = Permission::where('id',$_id)->first();
            if($record != null){
                $record->delete();

                /* Start - Auditoria */
                $params=[
                    "sub_seccion_id" =>$this->sub_seccion_id,
                    "ip" => $this->GeneralFunctionsRepository->getIp(),
                    "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
                    "comment" => "Registro => ".$_id,
                ];
                $this->GeneralFunctionsRepository->addAudit($params);
                /* End - Auditoria */

                return ['success' => 1, 'message' => "Registro eliminado con éxito"];
            }else{
                return ['success' => 0, 'message' => "No existe el registro enviado."];
            }

        }else{
            return ['success' => 0, 'message' => "No es valido el registro enviado."];
        }

    }

    public function permissionItemDelete($_id){

        if(isset($_id) && $_id > 0){
            $record = RolesItem::where('id',$_id)->delete();

            /* Start - Auditoria */
            $params=[
                "sub_seccion_id" =>$this->sub_seccion_id,
                "ip" => $this->GeneralFunctionsRepository->getIp(),
                "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
                "comment" => "Registro => ".$_id,
            ];
            $this->GeneralFunctionsRepository->addAudit($params);
            /* End - Auditoria */

            if($record != null){
                return ['success' => 1, 'message' => "Registro eliminado con éxito"];
            }else{
                return ['success' => 0, 'message' => "No existe el registro enviado."];
            }

        }else{
            return ['success' => 0, 'message' => "No es valido el registro enviado."];
        }

    }

    public function permissionEdit(Request $request){
        $editRecord = Permission::where("id",$request->id)->first();
        $editRecord->roles_id = $request->rol;
        $editRecord->save();

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>$this->sub_seccion_id,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Registro => ".$editRecord->id,
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        if($editRecord != null){
            return ['success' => 1, 'data'=> $editRecord];
        }else{
            return ['success' => 1, 'data'=> ""];
        }

    }

    public function permissionIsUserExist(Request $request){

        $is_user_exist = Permission::where('vw_users_usuario',$request->user)->first();

        if($is_user_exist != null){
            return ['success' => 1, 'is_user_exist'=>1];
        }else{
            return ['success' => 1, 'is_user_exist'=>0];
        }

    }

    public function permissionSave(Request $request){

        $newRecord = new Permission();
        $newRecord->vw_users_usuario = $request->user;
        $newRecord->roles_id = $request->rol;
        $newRecord->save();

        if($newRecord != null){
            /* Start - Auditoria */
            $params=[
                "sub_seccion_id" =>$this->sub_seccion_id,
                "ip" => $this->GeneralFunctionsRepository->getIp(),
                "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
                "comment" => "Registro => ".$newRecord->id,
            ];
            $this->GeneralFunctionsRepository->addAudit($params);
            /* End - Auditoria */

            return ['success' => 1, 'data'=> $newRecord];
        }else{
            return ['success' => 1, 'data'=> ""];
        }


    }

}
