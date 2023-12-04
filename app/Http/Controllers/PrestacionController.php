<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\GeneralFunctionsRepository;
use App\Models\Benefit;
use Carbon\Carbon;
use App\Models\BucketFile;
use App\Models\File;
use App\Models\Location;
use Image;
use App\Models\BucketLocation;

class PrestacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->GeneralFunctionsRepository = new GeneralFunctionsRepository();
        $this->sub_seccion_id = 14;
    }

    public function show($id){
        $prestacion=Benefit::where('id',$id)->first();
        return view('detail_prestacion',['prestacion'=>$prestacion]);
    }

    public function prestacionShow(){

        $temp_request = new Request();
        $temp_request->setMethod('GET');
        $temp_request->query->add(['limit' => 10]);
        $list = $this->prestacionList($temp_request);
        $submodule = "prestacion";
        $compact = compact('list','submodule');

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>$this->sub_seccion_id,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Listado",
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return view('admin.benefits.prestaciones.list')->with($compact);

    }

    public function prestacionListPublic(Request $request){

        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '10';
        $search = isset($request->search) ? json_decode(json_encode($request->search),false) : '';
        $expiration_date = (isset($request->expiration_date) && $request->expiration_date != null) ? $request->expiration_date : '';
        $prestacion_list= [];

        $prestacion_list = Benefit::join('bucket_locations','benefits.id','bucket_locations.origin_record_id')
        ->where('bucket_locations.subgroups_id',session('location_user'))
        ->whereNull('benefits.deleted_at')
        ->whereNull('bucket_locations.deleted_at')
        ->where('bucket_locations.sub_seccion_id',14)
        ->whereDate('benefits.expiration_date', '>=', $expiration_date)
        ->where('benefits.type','prestacion')
        ->orderBy('benefits.created_at',$order_by)
        ->select('benefits.*')
        ->Paginate($limit);

        $prestacion_list->setPath('/prestacion/prestaciones');

        return $prestacion_list;
    }

    public function prestacionList(Request $request){

        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '10';
        $search = isset($request->search) ? json_decode(json_encode($request->search),false) : '';
        $expiration_date = (isset($request->expiration_date) && $request->expiration_date != null) ? $request->expiration_date : '';
        $prestacion_list= [];

        if(isset($search->isSearch) && strlen($search->text) > 0){
            $prestacion_list = Benefit::whereRaw("(title like '%$search->text%'
                or description like '%$search->text%'
                or expiration_date like '%$search->text%')")
            ->where('type','prestacion')
            ->orderBy('expiration_date',$order_by)->Paginate($limit);
        }else{
            $prestacion_list = Benefit::whereDate('expiration_date', '>=', $expiration_date)
            ->where('type','prestacion')
            ->orderBy('expiration_date',$order_by)
            ->Paginate($limit);
        }

        $prestacion_list->setPath('/admin/benefits/prestacion-list-admin');

        return $prestacion_list;
    }

    public function prestacionSave(Request $request){

        //Se validan los datos que llegan
        $validatedData = $request->validate([
            'locations' => 'required|array',
            'title' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpg,png,|max:5000',
            'description' => 'nullable|string',
            'link' => 'nullable|string',
            'expiration_date' => 'required|date',
            'type' => 'required|string|max:10'
        ]);

        $folder_path_storage = 'storage/prestacion/';
		if(!file_exists($folder_path_storage)) {
			mkdir($folder_path_storage, 0777,true);
		}

        // Se almacena la imagen
        $file = $request->file('photo');
        $extension = $file->getClientOriginalExtension();
        $fileName = time().'_prestacion.';
        $fileName = $fileName.$extension;
        $path = '/prestacion/'.$fileName;
        \Storage::disk('public')->put($path,\File::get($file));

        $newRecord = new Benefit();
        $newRecord->title = $request->title;
        $newRecord->type = "prestacion";
        $newRecord->photo = 'storage'.$path;
        $newRecord->description = $request->description;
        $newRecord->link = $request->link;
        $newRecord->expiration_date = $request->expiration_date;
        $newRecord->save();

        //Se almacenan los id de las ubicaciones creadas
        if($newRecord != null){

            //Se verifica si trae subgrupos
            if(isset($request->subgroups)){
                $relation_subgroups = Location::join('subgroups','subgroups.vw_locations_name','=','vw_locations.name')
                                        ->whereIn('subgroups.id',$request->subgroups)
                                        ->whereNull('subgroups.deleted_at')
                                        ->whereNull('vw_locations.deleted_at')
                                        ->select('vw_locations.id as vw_locations_id','vw_locations.name as vw_locations_name',
                                                'subgroups.id as subgroups_id','subgroups.name as subgroups_name')
                                        ->get();
            }else{
                $relation_subgroups = Location::join('subgroups','subgroups.vw_locations_name','=','vw_locations.name')
                                        ->whereIn('vw_locations.id',$request->locations)
                                        ->whereNull('subgroups.deleted_at')
                                        ->whereNull('vw_locations.deleted_at')
                                        ->select('vw_locations.id as vw_locations_id','vw_locations.name as vw_locations_name',
                                                'subgroups.id as subgroups_id','subgroups.name as subgroups_name')
                                        ->get();
            }

            foreach ($relation_subgroups as $key => $subgroup) {
                $bucket_location = new BucketLocation();
                $bucket_location->origin_record_id = $newRecord->id;
                $bucket_location->locations_id = $subgroup->vw_locations_id;
                $bucket_location->subgroups_id = $subgroup->subgroups_id;
                $bucket_location->sub_seccion_id = $this->sub_seccion_id;
                $bucket_location->save();
            }

        }

        // Se verifica si hay documentos cargados
        if($request->cont_files > 0 && $newRecord != null){

            for ($i=0; $i <$request->cont_files ; $i++) {
                // Se almacena el documento
                $fileName = time()."_benefit";
                $doc = $request['files_'.$i];
                $extension = $doc->getClientOriginalExtension();
                $fileName = $fileName.'_'.$i.'.'.$extension;
                $path = '/prestacion/'.$fileName;
                \Storage::disk('public')->put($path,\File::get($doc));

                $file = new File();
                $file->name = $doc->getClientOriginalName();
                $file->file = 'storage'.$path;
                $file->extension = $doc->getClientOriginalExtension();
                $file->type_file = $doc->getClientOriginalExtension();
                $file->save();

                if($file != null){
                    $bucket_file = new BucketFile();
                    $bucket_file->sub_seccion_id = $this->sub_seccion_id;
                    $bucket_file->origin_record_id = $newRecord->id;
                    $bucket_file->file_id = $file->id;
                    $bucket_file->save();
                }
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

    public function prestacionEdit(Request $request){

        $path = null;
        //Se validan los datos que llegan
        $validatedData = $request->validate([
            'locations' => 'required|array',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|string',
            'expiration_date' => 'required|date',

        ]);

        $folder_path_storage = 'storage/prestacion/';
		if(!file_exists($folder_path_storage)) {
			mkdir($folder_path_storage, 0777,true);
		}

        if($request->file('photo') != null){
            $validatePhoto = $request->validate([
                'photo' => 'required|image|mimes:jpg,png|max:5000',
            ]);
            // Se almacena la imagen
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'_prestacion.';
            $fileName = $fileName.$extension;
            $path = '/prestacion/'.$fileName;
            \Storage::disk('public')->put($path,\File::get($file));

        }

        $editRecord = Benefit::where('id',$request->prestacion)->first();
        $editRecord->title = $request->title;
        $editRecord->description = $request->description;
        $editRecord->link = $request->link;
        $editRecord->expiration_date = $request->expiration_date;

        if($request->file('photo') != null){
            $editRecord->photo = 'storage'.$path;
        }

        $editRecord->save();

        //Se eliminan las ubicaciones actuales y se almacenan los id de las ubicaciones creadas
        if($editRecord != null){

            $locations_old = BucketLocation::where('origin_record_id',$request->prestacion)
            ->where('sub_seccion_id',$this->sub_seccion_id)
            ->delete();

            //Se verifica si trae subgrupos
            if(isset($request->subgroups)){
                $relation_subgroups = Location::join('subgroups','subgroups.vw_locations_name','=','vw_locations.name')
                                        ->whereIn('subgroups.id',$request->subgroups)
                                        ->whereNull('subgroups.deleted_at')
                                        ->whereNull('vw_locations.deleted_at')
                                        ->select('vw_locations.id as vw_locations_id','vw_locations.name as vw_locations_name',
                                                'subgroups.id as subgroups_id','subgroups.name as subgroups_name')
                                        ->get();
            }else{
                $relation_subgroups = Location::join('subgroups','subgroups.vw_locations_name','=','vw_locations.name')
                                        ->whereIn('vw_locations.id',$request->locations)
                                        ->whereNull('subgroups.deleted_at')
                                        ->whereNull('vw_locations.deleted_at')
                                        ->select('vw_locations.id as vw_locations_id','vw_locations.name as vw_locations_name',
                                                'subgroups.id as subgroups_id','subgroups.name as subgroups_name')
                                        ->get();
            }

            foreach ($relation_subgroups as $key => $subgroup) {
                $bucket_location = new BucketLocation();
                $bucket_location->origin_record_id = $editRecord->id;
                $bucket_location->locations_id = $subgroup->vw_locations_id;
                $bucket_location->subgroups_id = $subgroup->subgroups_id;
                $bucket_location->sub_seccion_id = $this->sub_seccion_id;
                $bucket_location->save();
            }

        }

        // Se verifica si hay documentos cargados
        if($request->cont_files > 0 && $editRecord != null){

            for ($i=0; $i <$request->cont_files ; $i++) {
                // Se almacena el documento
                $fileName = time()."_prestacion";
                $doc = $request['files_'.$i];
                $extension = $doc->getClientOriginalExtension();
                $fileName = $fileName.'_'.$i.'.'.$extension;
                $path = '/prestacion/'.$fileName;
                \Storage::disk('public')->put($path,\File::get($doc));

                $file = new File();
                $file->name = $doc->getClientOriginalName();
                $file->file = 'storage'.$path;
                $file->extension = $doc->getClientOriginalExtension();
                $file->type_file = $doc->getClientOriginalExtension();
                $file->save();

                if($file != null){
                    $bucket_file = new BucketFile();
                    $bucket_file->sub_seccion_id = $this->sub_seccion_id;
                    $bucket_file->origin_record_id = $editRecord->id;
                    $bucket_file->file_id = $file->id;
                    $bucket_file->save();
                }
            }
        }

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>$this->sub_seccion_id,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Registro => ".$editRecord->id,
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return ['success'=> 1, 'data'=>$editRecord];

    }

    public function prestacionDelete($_id){

        if(isset($_id) && $_id > 0){
            $record = Benefit::where('id',$_id)->first();
            if($record != null){
                $this->GeneralFunctionsRepository->deleteFile(['url'=>$record->photo]);

                BucketLocation::where('origin_record_id',$record->id)
                                ->where('sub_seccion_id',$this->sub_seccion_id)
                                ->delete();

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

    public function deleteFile($_file_id,$_record_id){

        if((isset($_file_id) && $_file_id > 0) && (isset($_record_id) && $_record_id > 0)){

            $bucketFile = BucketFile::where('file_id',$_file_id)
                                        ->where('origin_record_id',$_record_id)
                                        ->where('sub_seccion_id',$this->sub_seccion_id)
                                        ->first();

            if($bucketFile != null){
                $file = File::where('id',$_file_id)->first();

                if($file != null){
                    $this->GeneralFunctionsRepository->deleteFile(['url'=>$file->file]);
                    $file->delete();
                    $bucketFile->delete();

                    /* Start - Auditoria */
                    $params=[
                        "sub_seccion_id" =>$this->sub_seccion_id,
                        "ip" => $this->GeneralFunctionsRepository->getIp(),
                        "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
                        "comment" => "Registro => ".$_record_id.'   File => '.$_file_id,
                    ];
                    $this->GeneralFunctionsRepository->addAudit($params);
                    /* End - Auditoria */

                    return ['success' => 1, 'message' => "Registro eliminado con éxito"];
                }else{
                    return ['success' => 0, 'message' => "No existe el registro enviado."];
                }

            }else{
                return ['success' => 0, 'message' => "No existe el registro enviado."];
            }

        }else{
            return ['success' => 0, 'message' => "No es valido el registro enviado."];
        }

    }

}
