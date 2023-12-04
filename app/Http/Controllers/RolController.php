<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rol;
use App\Models\RolesItem;
use App\Models\SubSeccion;
use App\Repositories\GeneralFunctionsRepository;

class RolController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
        $this->GeneralFunctionsRepository = new GeneralFunctionsRepository();
        $this->sub_seccion_id=19;

    }

    public function rolShow(){
        $temp_request = new Request();
        $temp_request->setMethod('GET');
        $temp_request->query->add(['limit'=>10]);
        $list = $this->rolList($temp_request);
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

        return view('admin.settings.roles.list')->with($compact);

    }

    public function rolList(Request $request){

        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '10';
        $search = isset($request->search) ? json_decode(json_encode($request->search),false) : '';
        $rol_list= [];

        if(isset($search->isSearch) && strlen($search->text) > 0){
            $rol_list = Rol::with(['roles_items'])->whereRaw("(name like '%$search->text%')")
            ->orderBy('created_at',$order_by)->Paginate($limit);
        }else{

            $rol_list = Rol::with(['roles_items'])->orderBy('created_at',$order_by)
            ->Paginate($limit);
        }

        $rol_list->setPath('/admin/settings/rol-list');

        return $rol_list;
    }

    public function subSeccionAll(Request $request){
        $data= SubSeccion::where("hidden",0)->orderBy('seccion_id','asc')->get();

        return ["success" => 1, "data" => $data];

    }

    public function rolAll(Request $request){
        $data= Rol::with("roles_items")->orderBy('name','asc')->get();

        return ["success" => 1, "data" => $data];

    }

    public function rolSave(Request $request){

        //Se validan los datos que llegan
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $newRecord = new Rol();
        $newRecord->name = $request->name;
        $newRecord->save();

        if($newRecord != null){
            foreach($request->matriz_permissions as $permission){
                $rolItem = new RolesItem();
                $rolItem->roles_id = $newRecord->id;
                $rolItem->sub_seccion_id = $permission['sub_seccion'];
                $rolItem->actions = implode(',',$permission['permissions']);
                $rolItem->save();
            }
        }

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>$this->sub_seccion_id,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Registro => ".$newRecord->id,
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return ['success'=> 1, 'data'=>$newRecord];

    }

    public function rolDelete($_id){

        if(isset($_id) && $_id > 0){
            $record = Rol::where('id',$_id)->first();
            if($record != null){

                RolesItem::where('roles_id',$record->id)->delete();

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

    public function rolItemDelete($_id){

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

    public function rolEdit(Request $request){

        //Se validan los datos que llegan
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $editRecord = Rol::where('id',$request->id)->first();
        $editRecord->name = $request->name;
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
            foreach($request->matriz_permissions as $permission){
                $rolItem = new RolesItem();
                $rolItem->roles_id = $editRecord->id;
                $rolItem->sub_seccion_id = $permission['sub_seccion'];
                $rolItem->actions = implode(',',$permission['permissions']);
                $rolItem->save();
            }
        }

        return ['success'=> 1, 'data'=>$editRecord];

    }





}
