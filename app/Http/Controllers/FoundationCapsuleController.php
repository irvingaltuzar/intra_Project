<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FoundationCapsule;
use App\Models\BucketLocation;
use Illuminate\Support\Facades\Storage;
use App\Repositories\GeneralFunctionsRepository;
use Carbon\Carbon;
use Image;
use App\Models\BucketFile;
use App\Models\Location;
use App\Models\File;



class FoundationCapsuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->sub_seccion_id = 22;
        $this->GeneralFunctionsRepository = new GeneralFunctionsRepository();
    }

    public function fundacion(){

        /* Start - Auditoria */
            $params=[
                "sub_seccion_id" =>33,
                "ip" => $this->GeneralFunctionsRepository->getIp(),
                "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
                "comment" => "Fundación",
            ];
            $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        $hoy = Carbon::today();
        $temp_request = new Request();
        $temp_request->setMethod('GET');
        $temp_request->query->add(['limit'=>10,
                                    'expiration_date' => $hoy
                                    ]);

        $foundation_capsule_list = $this->publicFoundationCapsuleList($temp_request);
        $compact = compact('foundation_capsule_list');
        return view('fundacion')->with($compact);
    }

    public function FoundationCapsuleShow(){

        $temp_request = new Request();
        $temp_request->setMethod('GET');
        $temp_request->query->add(['limit' => 10]);
        $list = $this->adminFoundationCapsuleList($temp_request);
        $submodule = "FoundationCapsule";
        $compact = compact('list','submodule');
        return view('admin.foundation.list')->with($compact);

    }

    public function publicFoundationCapsuleList(Request $request){

        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '10';
        $FoundationCapsule_list = FoundationCapsule::join('bucket_locations','foundation_capsule.id','bucket_locations.origin_record_id')
            ->where('bucket_locations.subgroups_id',session('location_user'))
            ->whereNull('foundation_capsule.deleted_at')
            ->whereNull('bucket_locations.deleted_at')
            ->where('bucket_locations.sub_seccion_id',22)
            ->whereDate('foundation_capsule.expiration_date', '>=', Carbon::now())->orderBy('foundation_capsule.created_at','desc')
            ->select('foundation_capsule.*')
            ->Paginate($limit);

        $FoundationCapsule_list->setPath('/foundation-capsule');

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>$this->sub_seccion_id,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Listado",
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return $FoundationCapsule_list;
    }


    public function adminFoundationCapsuleList(Request $request){

        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '10';
        $search = isset($request->search) ? json_decode(json_encode($request->search),false) : '';
        $expiration_date = (isset($request->expiration_date) && $request->expiration_date != null) ? $request->expiration_date : '';
        $FoundationCapsule_list= [];

        if(isset($search->isSearch) && strlen($search->text) > 0){
            $FoundationCapsule_list = FoundationCapsule::whereRaw("(title like '%$search->text%'
                or description like '%$search->text%'
                or expiration_date like '%$search->text%')")
            ->orderBy('expiration_date',$order_by)->Paginate($limit);
        }else{
            $FoundationCapsule_list = FoundationCapsule::orderBy('expiration_date',$order_by)
            ->Paginate($limit);
        }

        $FoundationCapsule_list->setPath('/admin/foundation-capsule-list');

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>$this->sub_seccion_id,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Listado Admin",
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return $FoundationCapsule_list;
    }

    public function FoundationCapsuleSave(Request $request){

        //Se validan los datos que llegan
        $validatedData = $request->validate([
            'locations' => 'required|array',
            'title' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpg,png,|max:5000',
            'description' => 'nullable|string',
            'link' => 'nullable|string',
            'expiration_date' => 'required|date',
        ]);

        // Se almacena la imagen
        $file = $request->file('photo');
        $extension = $file->getClientOriginalExtension();
        $fileName = time().'_foundation_capsule.';
        $fileName = $fileName.$extension;
        $path = '/foundation/'.$fileName;
        \Storage::disk('public')->put($path,\File::get($file));
        $this->GeneralFunctionsRepository->uploadImgServerGrupoDMI($request->file('photo'),$fileName);

        // Se almacena el video
        $path_video=NULL;
        $docName = '_foundation_capsule';
        if($request->file('video') != null){
            $fileName = time().$docName.'.';
            $file = $request->file('video');
            $extension = $file->getClientOriginalExtension();
            $fileName = $fileName.$extension;
            $path_video = '/foundation/'.$fileName;
            \Storage::disk('public')->put($path_video,\File::get($file));
            $path_video = 'storage'.$path_video;
        }


        $newRecord = new FoundationCapsule();
        $newRecord->title = $request->title;
        $newRecord->photo = 'storage'.$path;
        $newRecord->video = $path_video;
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
                $fileName = time()."_foundation_capsule";
                $doc = $request['files_'.$i];
                $extension = $doc->getClientOriginalExtension();
                $fileName = $fileName.'_'.$i.'.'.$extension;
                $path = '/foundation/'.$fileName;
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
            "comment" => "Avisos de área => ".$newRecord->id,
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        $this->sendEmails($newRecord->id,$this->sub_seccion_id);

        return ['success'=> 1, 'data'=>$newRecord];


    }

    public function FoundationCapsuleEdit(Request $request){

        $path = null;
        //Se validan los datos que llegan
        $validatedData = $request->validate([
            'locations' => 'required|array',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|string',
            'expiration_date' => 'required|date',
        ]);

        if($request->file('photo') != null){
            $validatePhoto = $request->validate([
                'photo' => 'required|image|mimes:jpg,png|max:5000',
            ]);
            // Se almacena la imagen
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'_foundation_capsule.';
            $fileName = $fileName.$extension;
            $path = '/foundation/'.$fileName;
            \Storage::disk('public')->put($path,\File::get($file));
            $this->GeneralFunctionsRepository->uploadImgServerGrupoDMI($request->file('photo'),$fileName);

        }

        // Se almacena el video
        $path_video="";
        $docName = '_foundation_capsule';
        if($request->file('video') != null){

            $fileName = time().$docName.'.';
            $file = $request->file('video');
            $extension = $file->getClientOriginalExtension();
            $fileName = $fileName.$extension;
            $path_video = '/foundation/'.$fileName;
            \Storage::disk('public')->put($path_video,\File::get($file));
        }

        $editRecord = FoundationCapsule::where('id',$request->foundation_capsule)->first();
        $editRecord->title = $request->title;
        $editRecord->description = $request->description;
        $editRecord->link = $request->link;
        $editRecord->expiration_date = $request->expiration_date;

        if($request->file('photo') != null){
            $editRecord->photo = 'storage'.$path;
        }

        if($request->file('video') != null){
            //Se elimina el video física que se reemplazara
            $this->GeneralFunctionsRepository->deleteFile(['url'=>$editRecord->video]);
            $editRecord->video = 'storage'.$path_video;
        }

        $editRecord->save();

        //Se eliminan las ubicaciones actuales y se almacenan los id de las ubicaciones creadas
        if($editRecord != null){

            $locations_old = BucketLocation::where('origin_record_id',$request->foundation_capsule)
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
                $fileName = time()."_foundation_capsule";
                $doc = $request['files_'.$i];
                $extension = $doc->getClientOriginalExtension();
                $fileName = $fileName.'_'.$i.'.'.$extension;
                $path = '/foundation/'.$fileName;
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
            "comment" => "Avisos de área => ".$editRecord->id,
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        //$this->sendEmails($editRecord->id,$this->sub_seccion_id);
        return ['success'=> 1, 'data'=>$editRecord];

    }

    public function FoundationCapsuleDelete($_id){

        if(isset($_id) && $_id > 0){
            $record = FoundationCapsule::where('id',$_id)->first();
            if($record != null){
                $this->GeneralFunctionsRepository->deleteFile(['url'=>$record->photo]);

                BucketLocation::where('origin_record_id',$record->id)
                                ->where('sub_seccion_id',$this->sub_seccion_id)
                                ->delete();

                $record->delete();

                /* Start - Auditoria */
                $params=[
                    "sub_seccion_id" =>20,
                    "ip" => $this->GeneralFunctionsRepository->getIp(),
                    "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
                    "comment" => "Noticia de Area => ".$_id,
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
                        "sub_seccion_id" =>20,
                        "ip" => $this->GeneralFunctionsRepository->getIp(),
                        "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
                        "comment" => "Noticia de Area => ".$_record_id.'   File => '.$_file_id,
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

    public function sendEmails($_record_id,$_sub_seccion_id){

        $record = FoundationCapsule::find($_record_id);

        if($record != null){
            $users_email = $this->GeneralFunctionsRepository->getUsersByLocation($_record_id,$_sub_seccion_id);
            $cover_img_name = $this->GeneralFunctionsRepository->getCoverNameImg($record->photo);

            $mail_data = [
                'sub_seccion' => "foundation_capsule",
                'recipient_emails' =>$users_email,
                'subject' => "Cápsulas de Fundación - $record->title",
                'link' => url('fundacion').'?section=pane-information-capsules',
                'title' => "Cápsulas de Fundación - $record->title",
                'description' => $record->description,
                'link_data' => $record->link,
                'link_video' => $record->video != null ? "multimedia/foundation_capsule/$record->id" : null,
                'photo' =>"https://www.grupodmi.com.mx/intranet/img/comunicados/$cover_img_name",
            ];

            $this->GeneralFunctionsRepository->sendEmails($mail_data);

            //Se envia la notificación del comunicado
            $this->GeneralFunctionsRepository->preparingNotificationCommunique($mail_data,$_record_id,$_sub_seccion_id);
        }

    }

    public function sendReminder(int $_record_id)
    {
        $record = FoundationCapsule::find($_record_id);

        if($record != null){
            $users_email = $this->GeneralFunctionsRepository->getUsersByLocation($_record_id,$this->sub_seccion_id);
            $cover_img_name = $this->GeneralFunctionsRepository->getCoverNameImg($record->photo);

            $mail_data = [
                'sub_seccion' => "foundation_capsule",
                'recipient_emails' =>$users_email,
                'subject' => "Recordatorio - Cápsulas de Fundación - $record->title",
                'link' => url('fundacion').'?section=pane-information-capsules',
                'title' => "Cápsulas de Fundación - $record->title",
                'description' => $record->description,
                'link_data' => $record->link,
                'link_video' => $record->video != null ? "multimedia/foundation_capsule/$record->id" : null,
                'photo' =>"https://www.grupodmi.com.mx/intranet/img/comunicados/$cover_img_name",
            ];

            $this->GeneralFunctionsRepository->sendEmails($mail_data);
            
            //Se envia la notificación del comunicado
            $mail_data['title'] = $mail_data['subject'];
            $this->GeneralFunctionsRepository->preparingNotificationCommunique($mail_data,$_record_id,$this->sub_seccion_id);

            return ['success' => 1, 'message' => "Recordatorio enviado con éxito"];
        }
    }



}
