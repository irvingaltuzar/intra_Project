<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InternalPosting;
use App\Models\BucketLocation;
use Illuminate\Support\Facades\Storage;
use App\Repositories\GeneralFunctionsRepository;
use App\Models\BucketFile;
use App\Models\File;
use App\Models\Location;
use Carbon\Carbon;
use Image;

class InternalPostingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
        $this->sub_seccion_id = 9;
        $this->GeneralFunctionsRepository = new GeneralFunctionsRepository();
    }


    public function internalPostingShow(){

        $temp_request = new Request();
        $temp_request->setMethod('GET');
        $temp_request->query->add(['limit' => 10]);
        $list = $this->internalPostingList($temp_request);
        $submodule = "internal_posting";
        $compact = compact('list','submodule');

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>$this->sub_seccion_id,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Listado admin",
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return view('admin.news.internal_posting.list')->with($compact);

    }

    public function internalPostingListPublic(Request $request){

        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '10';
        $search = isset($request->search) ? json_decode(json_encode($request->search),false) : '';
        $expiration_date = (isset($request->expiration_date) && $request->expiration_date != null) ? $request->expiration_date : '';
        $internal_posting_list= [];

        if(isset($search->isSearch) && strlen($search->text) > 0){
            $internal_posting_list = InternalPosting::join('bucket_locations','internal_postings.id','bucket_locations.origin_record_id')
            ->where('bucket_locations.subgroups_id',session('location_user'))
            ->whereNull('internal_postings.deleted_at')
            ->whereNull('bucket_locations.deleted_at')
            ->where('bucket_locations.sub_seccion_id',9)
            ->whereRaw("(internal_postings.title like '%$search->text%'
                or internal_postings.description like '%$search->text%'
                or internal_postings.publication_date like '%$search->text%'
                or internal_postings.expiration_date like '%$search->text%')")
            ->orderBy('internal_postings.created_at',$order_by)
            ->select('internal_postings.*')
            ->Paginate($limit);
        }else{

            $internal_posting_list = InternalPosting::join('bucket_locations','internal_postings.id','bucket_locations.origin_record_id')
            ->where('bucket_locations.subgroups_id',session('location_user'))
            ->whereNull('internal_postings.deleted_at')
            ->whereNull('bucket_locations.deleted_at')
            ->where('bucket_locations.sub_seccion_id',9)
            ->whereDate('internal_postings.expiration_date', '>=', $expiration_date)
            ->orderBy('internal_postings.created_at',$order_by)
            ->select('internal_postings.*')
            ->Paginate($limit);
        }

        $internal_posting_list->setPath('/news/internal-posting');

        return $internal_posting_list;
    }

    public function internalPostingList(Request $request){

        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '10';
        $search = isset($request->search) ? json_decode(json_encode($request->search),false) : '';
        $expiration_date = (isset($request->expiration_date) && $request->expiration_date != null) ? $request->expiration_date : '';
        $internal_posting_list= [];

        if(isset($search->isSearch) && strlen($search->text) > 0){
            $internal_posting_list = InternalPosting::whereRaw("(title like '%$search->text%'
                or description like '%$search->text%'
                or publication_date like '%$search->text%'
                or expiration_date like '%$search->text%')")
            ->orderBy('expiration_date',$order_by)->Paginate($limit);
        }else{

            $internal_posting_list = InternalPosting::whereDate('expiration_date', '>=', $expiration_date)->orderBy('expiration_date',$order_by)
            ->Paginate($limit);
        }

        $internal_posting_list->setPath('/admin/news/internal-posting-list-admin');

        return $internal_posting_list;
    }


    public function internalPostingSave(Request $request){

        //Se validan los datos que llegan
        $validatedData = $request->validate([
            'locations' => 'required|array',
            'title' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpg,png,|max:5000',
            'publication_date' => 'required|date',
            'expiration_date' => 'required|date',
        ]);

        $folder_path_storage = 'storage/news/';
		if(!file_exists($folder_path_storage)) {
			mkdir($folder_path_storage, 0777,true);
		}

        // Se almacena la imagen
        $file = $request->file('photo');
        $extension = $file->getClientOriginalExtension();
        $fileName = time().'_internal_posting.';
        $fileName = $fileName.$extension;
        $path = '/news/'.$fileName;
        \Storage::disk('public')->put($path,\File::get($file));
        $this->GeneralFunctionsRepository->uploadImgServerGrupoDMI($request->file('photo'),$fileName);

        $newRecord = new InternalPosting();
        $newRecord->title = $request->title;
        $newRecord->photo = 'storage'.$path;
        $newRecord->description = $request->description;
        $newRecord->publication_date = $request->publication_date;
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
                $fileName = time()."_internal_posting";
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

        $this->sendEmails($newRecord->id,$this->sub_seccion_id);

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

    public function internalPostingEdit(Request $request){

        $path = null;
        //Se validan los datos que llegan
        $validatedData = $request->validate([
            'locations' => 'required|array',
            'title' => 'required|string|max:255',
            'publication_date' => 'required|date',
            'expiration_date' => 'required|date',
        ]);

        $folder_path_storage = 'storage/news/';
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
            $fileName = time().'_internal_posting.';
            $fileName = $fileName.$extension;
            $path = '/news/'.$fileName;
            \Storage::disk('public')->put($path,\File::get($file));
            $this->GeneralFunctionsRepository->uploadImgServerGrupoDMI($request->file('photo'),$fileName);

        }

        $editRecord = InternalPosting::where('id',$request->internal_posting)->first();
        $editRecord->title = $request->title;
        $editRecord->description = $request->description;
        $editRecord->publication_date = $request->publication_date;
        $editRecord->expiration_date = $request->expiration_date;

        if($request->file('photo') != null){
            $editRecord->photo = 'storage'.$path;
        }

        $editRecord->save();

        //Se eliminan las ubicaciones actuales y se almacenan los id de las ubicaciones creadas
        if($editRecord != null){

            $locations_old = BucketLocation::where('origin_record_id',$request->internal_posting)
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
                $fileName = time()."_internal_posting";
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

        //$this->sendEmails($editRecord->id,$this->sub_seccion_id);

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

    public function internalPostingDelete($_id){

        if(isset($_id) && $_id > 0){
            $record = InternalPosting::where('id',$_id)->first();
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


    public function sendEmails($_record_id,$_sub_seccion_id){

        $record = InternalPosting::find($_record_id);

        if($record != null){
            $users_email = $this->GeneralFunctionsRepository->getUsersByLocation($_record_id,$_sub_seccion_id);
            $cover_img_name = $this->GeneralFunctionsRepository->getCoverNameImg($record->photo);

            $mail_data = [
                'sub_seccion' => "internal_posting",
                'recipient_emails' =>$users_email,
                'subject' => "Posteo Interno - $record->title",
                'link' => url('news').'?section=pane-internal-posting',
                'title' => "Posteo Interno - $record->title",
                'description' => $record->description,
                'photo' =>"https://www.grupodmi.com.mx/intranet/img/comunicados/$cover_img_name",
            ];

            $this->GeneralFunctionsRepository->sendEmails($mail_data);
            //Se envia la notificación del comunicado
            $this->GeneralFunctionsRepository->preparingNotificationCommunique($mail_data,$_record_id,$_sub_seccion_id);
        }

    }

    public function sendReminder(int $_record_id)
    {
        $record = InternalPosting::find($_record_id);

        if($record != null){
            $users_email = $this->GeneralFunctionsRepository->getUsersByLocation($_record_id, $record->bucket_location[0]->sub_seccion_id);
            $cover_img_name = $this->GeneralFunctionsRepository->getCoverNameImg($record->photo);

            $mail_data = [
                'sub_seccion' => "internal_posting",
                'recipient_emails' =>$users_email,
                'subject' => "Recordatorio - Posteo Interno - $record->title",
                'link' => url('news').'?section=pane-internal-posting',
                'title' => "Posteo Interno - $record->title",
                'description' => $record->description,
                'photo' =>"https://www.grupodmi.com.mx/intranet/img/comunicados/$cover_img_name",
            ];

            $this->GeneralFunctionsRepository->sendEmails($mail_data);

            //Se envia la notificación del comunicado
            $mail_data['title'] = $mail_data['subject'];
            $this->GeneralFunctionsRepository->preparingNotificationCommunique($mail_data,$_record_id, $record->bucket_location[0]->sub_seccion_id);

            /* Start - Auditoria */
            $params=[
                "sub_seccion_id" =>$this->sub_seccion_id,
                "ip" => $this->GeneralFunctionsRepository->getIp(),
                "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
                "comment" => "Recordatorio de correo => ".$_record_id,
            ];
            $this->GeneralFunctionsRepository->addAudit($params);
            /* End - Auditoria */

            return ['success' => 1, 'message' => "Recordatorio enviado con éxito"];
        }
    }

}
