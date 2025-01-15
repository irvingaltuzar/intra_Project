<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\TypeEvent;
use App\Models\BucketLocation;
use App\Repositories\GeneralFunctionsRepository;
use Carbon\Carbon;
use App\Models\BucketFile;
use App\Models\File;
use App\Models\Location;
use Image;

class EventController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
        $this->sub_seccion_id = 15;
        $this->GeneralFunctionsRepository = new GeneralFunctionsRepository();
    }

    public function index(){
        /* Start - Auditoria */
            $params=[
                "sub_seccion_id" =>32,
                "ip" => $this->GeneralFunctionsRepository->getIp(),
                "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
                "comment" => "Eventos",
            ];
            $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        $year = date('Y')-1;
        $events = Event::with('type_event')->join('bucket_locations','events.id','bucket_locations.origin_record_id')
        ->where('bucket_locations.subgroups_id',session('location_user'))
        ->whereNull('events.deleted_at')
        ->whereNull('bucket_locations.deleted_at')
        ->where('bucket_locations.sub_seccion_id',15)
        ->whereYear('events.date','>=',$year)->orderBy('events.date','desc')
        ->select('events.*')
        ->get();
        $type_events = TypeEvent::all();
        $compact = compact('events','type_events');
        return view('events.events')->with($compact);
    }

    public function eventShow(){
        $temp_request = new Request();
        $temp_request->setMethod('GET');
        $temp_request->query->add(['limit'=>10]);
        $list = $this->eventList($temp_request);
        $compact = compact('list');

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>$this->sub_seccion_id,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Listado admin",
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return view('admin.events.list')->with($compact);

    }

    public function eventList(Request $request){
        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '10';
        $search = isset($request->search) ? json_decode(json_encode($request->search),false) : '';
        $type_event_by = isset($request->type_event_by) ? (" type_event_id = $request->type_event_by "): '';
        $events = [];
        if(isset($search->isSearch) && strlen($search->text) > 0){
            if($type_event_by != ''){
                $temp_type_event_by = isset($request->type_event_by) ? ("type_event_id = $request->type_event_by "): '';

                $events = Event::with(['type_event'])
                ->whereRaw("(title like '%$search->text%')")
                ->whereRaw($temp_type_event_by)
                ->orderBy('created_at',$order_by)->Paginate($limit);

            }else{
                $events = Event::with(['type_event'])
                ->whereRaw("(title like '%$search->text%')")
                ->orderBy('created_at',$order_by)->Paginate($limit);
            }

        }else if ($type_event_by != ''){

            $events = Event::with(['type_event'])->whereRaw($type_event_by)->orderBy('created_at',$order_by)
            ->Paginate($limit);
        }else{
            $events = Event::with(['type_event'])->orderBy('created_at',$order_by)
            ->Paginate($limit);
        }

        $events->setPath('/events/list');

        return $events;
    }

    public function eventSave(Request $request){
        $this->sub_seccion_id = 15;

        //Se validan los datos que llegan
        $validatedData=$request->validate([
            'type_event' => 'required|numeric|min:1|max:500',
            'locations' => 'required|array',
            'date' => 'required|date',
            'time' => 'nullable|string|max:5',
            'title' => 'required|string|max:255',
            'place' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Se almacena la imagen

        $folder_path_storage = 'storage/events/';
		if(!file_exists($folder_path_storage)) {
			mkdir($folder_path_storage, 0777,true);
		}
        $path=null;
        if($request->file('photo') != null){
            $request->validate([
                'photo' => 'nullable|image|mimes:jpg,png|max:5000',
            ]);

            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'_event.';
            $fileName = $fileName.$extension;
            $path = '/events/'.$fileName;
            \Storage::disk('public')->put($path,\File::get($file));
            $this->GeneralFunctionsRepository->uploadImgServerGrupoDMI($request->file('photo'),$fileName);
            $path = "storage".$path;
        }


        $event = new Event();
        $event->type_event_id = $request->type_event;
        $event->place = $request->place;
        $event->title = $request->title;
        $event->description = $request->description;
        $event->date = $request->date;
        $event->time = $request->time;
        $event->photo = $path;
        $event->save();

        //Se almacenan los id de las ubicaciones creadas
        if($event != null){

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
                $bucket_location->origin_record_id = $event->id;
                $bucket_location->locations_id = $subgroup->vw_locations_id;
                $bucket_location->subgroups_id = $subgroup->subgroups_id;
                $bucket_location->sub_seccion_id = $this->sub_seccion_id;
                $bucket_location->save();
            }
        }

        // Se verifica si hay imagenes cargadas
        if($request->cont_images > 0 && $event != null){

            for ($i=0; $i <$request->cont_images ; $i++) {
                // Se almacena el documento
                $fileName = time()."_events";
                $doc = $request['images_'.$i];
                $extension = $doc->getClientOriginalExtension();
                $fileName = $fileName.'_'.$i.'.'.$extension;
                $path = '/events/'.$fileName;
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
                    $bucket_file->origin_record_id = $event->id;
                    $bucket_file->file_id = $file->id;
                    $bucket_file->save();
                }
            }
        }

        // Se verifica si hay documentos cargados
        if($request->cont_files > 0 && $event != null){

            for ($i=0; $i <$request->cont_files ; $i++) {
                // Se almacena el documento
                $fileName = time()."_events";
                $doc = $request['files_'.$i];
                $extension = $doc->getClientOriginalExtension();
                $fileName = $fileName.'_'.$i.'.'.$extension;
                $path = '/events/'.$fileName;
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
                    $bucket_file->origin_record_id = $event->id;
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
            "comment" => "evento => ".$event->id,
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */
        
        if($request->check_send_email == 1){
            $this->sendEmails($event->id,$this->sub_seccion_id);
        }

        return ['success'=> 1, 'data'=>$event];
    }

    public function eventEdit(Request $request){
        $this->sub_seccion_id = 15;

        //Se validan los datos que llegan
        $validatedData=$request->validate([
            'type_event' => 'required|numeric|min:1|max:500',
            'locations' => 'required|array',
            'date' => 'required|date',
            'time' => 'nullable|string|max:5',
            'title' => 'required|string|max:255',
            'place' => 'nullable|string|max:255',
            'description' => 'nullable|string',

        ]);

        if($request->file('photo') != null){
            $validatePhoto = $request->validate([
                'photo' => 'nullable|image|mimes:jpg,png|max:5000',
            ]);
            // Se almacena la imagen
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'_event.';
            $fileName = $fileName.$extension;
            $path = '/events/'.$fileName;
            \Storage::disk('public')->put($path,\File::get($file));

        }

        $event = Event::where('id',$request->event)->first();
        $event->type_event_id = $request->type_event;
        $event->place = $request->place;
        $event->title = $request->title;
        $event->description = $request->description;
        $event->date = $request->date;
        $event->time = $request->time;

        if($request->file('photo') != null){
            //Se elimina la imagen física que se reemplazara
            $this->GeneralFunctionsRepository->deleteFile(['url'=>$event->photo]);
            $event->photo = 'storage'.$path;
        }

        $event->save();

        //Se eliminan las ubicaciones actuales y se almacenan los id de las ubicaciones creadas
        if($event != null){

            $locations_old = BucketLocation::where('origin_record_id',$event->id)
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
                $bucket_location->origin_record_id = $event->id;
                $bucket_location->locations_id = $subgroup->vw_locations_id;
                $bucket_location->subgroups_id = $subgroup->subgroups_id;
                $bucket_location->sub_seccion_id = $this->sub_seccion_id;
                $bucket_location->save();
            }
        }

        // Se verifica si hay documentos cargados
        if($request->cont_images > 0 && $event != null){

            for ($i=0; $i <$request->cont_images ; $i++) {
                // Se almacena el documento
                $fileName = time()."_events";
                $doc = $request['images_'.$i];
                $extension = $doc->getClientOriginalExtension();
                $fileName = $fileName.'_'.$i.'.'.$extension;
                $path = '/events/'.$fileName;
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
                    $bucket_file->origin_record_id = $event->id;
                    $bucket_file->file_id = $file->id;
                    $bucket_file->save();
                }
            }
        }

        // Se verifica si hay documentos cargados
        if($request->cont_files > 0 && $event != null){

            for ($i=0; $i <$request->cont_files ; $i++) {
                // Se almacena el documento
                $fileName = time()."_events";
                $doc = $request['files_'.$i];
                $extension = $doc->getClientOriginalExtension();
                $fileName = $fileName.'_'.$i.'.'.$extension;
                $path = '/events/'.$fileName;
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
                    $bucket_file->origin_record_id = $event->id;
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
            "comment" => "evento => ".$event->id,
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return ['success'=> 1, 'data'=>$event];
    }


    public function typeEventsAll(){
        $types = TypeEvent::all();

        return ['success' => 1,'data' => $types];
    }

    public function eventDelete($_id){

        if(isset($_id) && $_id > 0){
            $event = Event::where('id',$_id)->first();

            if($event != null){
                $this->GeneralFunctionsRepository->deleteFile(['url'=>$event->photo]);

                BucketLocation::where('origin_record_id',$event->id)
                                ->where('sub_seccion_id',$this->sub_seccion_id)
                                ->delete();

                $event->delete();

                /* Start - Auditoria */
                $params=[
                    "sub_seccion_id" =>$this->sub_seccion_id,
                    "ip" => $this->GeneralFunctionsRepository->getIp(),
                    "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
                    "comment" => "evento => ".$_id,
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

    public function deleteImg($_file_id,$_event_id){

        if((isset($_file_id) && $_file_id > 0) && (isset($_event_id) && $_event_id > 0)){
            $bucketFile = BucketFile::where('sub_seccion_id',$this->sub_seccion_id)->where('file_id',$_file_id)->where('origin_record_id',$_event_id)->first();

            if($bucketFile != null){
                $file = File::where('id',$_file_id)->first();

                if($file != null){
                    $this->GeneralFunctionsRepository->deleteFile(['url'=>$file->file]);
                    $file->delete();
                    $bucketFile->delete();

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

        $record = Event::find($_record_id);

        if($record != null){
            $photo = null;
            $users_email = $this->GeneralFunctionsRepository->getUsersByLocation($_record_id,$_sub_seccion_id);
            if($record->photo != null){
                $cover_img_name = $this->GeneralFunctionsRepository->getCoverNameImg($record->photo);
                $photo="https://www.grupodmi.com.mx/intranet/img/comunicados/$cover_img_name";
            }
            

            $mail_data = [
                'sub_seccion' => "event",
                'recipient_emails' =>$users_email,
                'subject' => "Eventos - $record->title",
                'link' => url('events'),
                'title' => "Eventos - $record->title",
                'description' => $record->description,
                'link_data' => null,
                'photo' =>$photo,
            ];

            $this->GeneralFunctionsRepository->sendEmails($mail_data);
            
            //Se envia la notificación del comunicado
            $this->GeneralFunctionsRepository->preparingNotificationCommunique($mail_data,$_record_id,$_sub_seccion_id);
            

        }

    }
}
