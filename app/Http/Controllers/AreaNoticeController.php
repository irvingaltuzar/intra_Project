<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AreaNotice;
use App\Models\Location;
use App\Models\BucketLocation;
use Illuminate\Support\Facades\Storage;
use App\Repositories\GeneralFunctionsRepository;
use Carbon\Carbon;
use Image;
use App\Models\BucketFile;
use App\Models\File;
use App\Models\ValidationMetadata;



class AreaNoticeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->sub_seccion_id = 11;
        $this->GeneralFunctionsRepository = new GeneralFunctionsRepository();
    }

    public function areaNoticeShow(){

        $temp_request = new Request();
        $temp_request->setMethod('GET');
        $temp_request->query->add(['limit' => 10]);
        $list = $this->adminAreaNoticeList($temp_request);
        $submodule = "areaNotice";
        $compact = compact('list','submodule');
        return view('admin.news.area_notice.list')->with($compact);

    }

    public function publicAreaNoticeList(Request $request){

        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '10';
        $areaNotice_list = AreaNotice::join('bucket_locations','area_notices.id','bucket_locations.origin_record_id')
            ->where('bucket_locations.subgroups_id',session('location_user'))
            ->whereNull('area_notices.deleted_at')
            ->whereNull('bucket_locations.deleted_at')
            ->where('bucket_locations.sub_seccion_id',11)
            ->whereDate('area_notices.expiration_date', '>=', Carbon::now())->orderBy('area_notices.created_at','desc')
            ->select('area_notices.*')
            ->Paginate($limit);

        $areaNotice_list->setPath('/news/area-notice');

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>$this->sub_seccion_id,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Listado",
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return $areaNotice_list;
    }


    public function adminAreaNoticeList(Request $request){

        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '10';
        $search = isset($request->search) ? json_decode(json_encode($request->search),false) : '';
        $expiration_date = (isset($request->expiration_date) && $request->expiration_date != null) ? $request->expiration_date : '';
        $areaNotice_list= [];
        
        if(isset($search->isSearch) && strlen($search->text) > 0){
            $areaNotice_list = AreaNotice::whereRaw("(title like '%$search->text%'
                or description like '%$search->text%'
                or expiration_date like '%$search->text%')");

                if(session('department_assigned_mail') != null){
                    $areaNotice_list = $areaNotice_list->where('belongs_department',session('department_assigned_mail'));
                }else{
                    $flag_validation_all_communication = ValidationMetadata::where('key','system_validate_not_view_communications_all_area')->first();
                    if($flag_validation_all_communication->value == 1){
                        $areaNotice_list = $areaNotice_list->whereNull('belongs_department');
                    }else{
                        $areaNotice_list = $areaNotice_list->whereNotNull('belongs_department');
                    }
                }

            $areaNotice_list = $areaNotice_list->orderBy('expiration_date',$order_by)->Paginate($limit);
        }else{

            if(session('department_assigned_mail') != null){
                $areaNotice_list = AreaNotice::where('belongs_department',session('department_assigned_mail'))
                                                ->orderBy('expiration_date',$order_by)
                                                ->Paginate($limit);
            }else{
                $flag_validation_all_communication = ValidationMetadata::where('key','system_validate_not_view_communications_all_area')->first();

                if($flag_validation_all_communication->value == 1){
                    $areaNotice_list = AreaNotice::whereNull('belongs_department')
                                                ->orderBy('expiration_date',$order_by)
                                                ->Paginate($limit);
                }else{
                    $areaNotice_list = AreaNotice::orderBy('expiration_date',$order_by)
                                                ->Paginate($limit);
                }

                
            }
            
        }

        $areaNotice_list->setPath('/admin/news/area-notice-list');

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>$this->sub_seccion_id,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Listado Admin",
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return $areaNotice_list;
    }

    public function areaNoticeSave(Request $request){

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
        $fileName = time().'_area_notice.';
        $fileName = $fileName.$extension;
        $path = '/news/'.$fileName;
        \Storage::disk('public')->put($path,\File::get($file));
        $this->GeneralFunctionsRepository->uploadImgServerGrupoDMI($request->file('photo'),$fileName);

        // Se almacena el video
        $path_video=NULL;
        $docName = '_area_notice';
        if($request->file('video') != null){
            $fileName = time().$docName.'.';
            $file = $request->file('video');
            $extension = $file->getClientOriginalExtension();
            $fileName = $fileName.$extension;
            $path_video = '/news/'.$fileName;
            \Storage::disk('public')->put($path_video,\File::get($file));
            $path_video = 'storage'.$path_video;
        }


        $newRecord = new AreaNotice();
        $newRecord->title = $request->title;
        $newRecord->photo = 'storage'.$path;
        $newRecord->video = $path_video;
        $newRecord->description = $request->description;
        $newRecord->link = $request->link;
        $newRecord->expiration_date = $request->expiration_date;
        $newRecord->belongs_department = session('department_assigned_mail');
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
                $fileName = time()."_area_notice";
                $doc = $request['files_'.$i];
                $extension = $doc->getClientOriginalExtension();
                $fileName = $fileName.'_'.$i.'.'.$extension;
                $path = '/news/'.$fileName;
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

    public function areaNoticeEdit(Request $request){

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
            $fileName = time().'_area_notice.';
            $fileName = $fileName.$extension;
            $path = '/news/'.$fileName;
            \Storage::disk('public')->put($path,\File::get($file));
            $this->GeneralFunctionsRepository->uploadImgServerGrupoDMI($request->file('photo'),$fileName);

        }

        // Se almacena el video
        $path_video="";
        $docName = '_area_notice';
        if($request->file('video') != null){

            $fileName = time().$docName.'.';
            $file = $request->file('video');
            $extension = $file->getClientOriginalExtension();
            $fileName = $fileName.$extension;
            $path_video = '/news/'.$fileName;
            \Storage::disk('public')->put($path_video,\File::get($file));
        }

        $editRecord = AreaNotice::where('id',$request->area_notice)->first();
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

            $locations_old = BucketLocation::where('origin_record_id',$request->area_notice)
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
                $fileName = time()."_area_notice";
                $doc = $request['files_'.$i];
                $extension = $doc->getClientOriginalExtension();
                $fileName = $fileName.'_'.$i.'.'.$extension;
                $path = '/news/'.$fileName;
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

    public function areaNoticeDelete($_id){

        if(isset($_id) && $_id > 0){
            $record = AreaNotice::where('id',$_id)->first();
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

        $record = AreaNotice::find($_record_id);

        if($record != null){
            $users_email = $this->GeneralFunctionsRepository->getUsersByLocation($_record_id,$_sub_seccion_id);
            $cover_img_name = $this->GeneralFunctionsRepository->getCoverNameImg($record->photo);

            $mail_data = [
                'sub_seccion' => "area_notice",
                'recipient_emails' =>$users_email,
                'subject' => "Avisos de Área - $record->title",
                'link' => url('news').'?section=pane-area-notices',
                'title' => "Avisos de Área - $record->title",
                'description' => $record->description,
                'link_data' => $record->link,
                'link_video' => $record->video != null ? "multimedia/area_notice/$record->id" : null,
                'photo' =>"https://www.grupodmi.com.mx/intranet/img/comunicados/$cover_img_name",
            ];

            $this->GeneralFunctionsRepository->sendEmails($mail_data);
            //Se envia la notificación del comunicado
            $this->GeneralFunctionsRepository->preparingNotificationCommunique($mail_data,$_record_id,$_sub_seccion_id);
        }

    }

    public function sendReminder(int $_record_id)
    {
        $record = AreaNotice::find($_record_id);

        if($record != null){
            $users_email = $this->GeneralFunctionsRepository->getUsersByLocation($_record_id,$this->sub_seccion_id);
            $cover_img_name = $this->GeneralFunctionsRepository->getCoverNameImg($record->photo);

            $mail_data = [
                'sub_seccion' => "area_notice",
                'recipient_emails' =>$users_email,
                'subject' => "Recordatorio - Avisos de Área - $record->title",
                'link' => url('news').'?section=pane-area-notices',
                'title' => "Avisos de Área - $record->title",
                'description' => $record->description,
                'link_data' => $record->link,
                'link_video' => $record->video != null ? "multimedia/area_notice/$record->id" : null,
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
