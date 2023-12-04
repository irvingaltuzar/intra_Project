<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poll;
use App\Models\BucketLocation;
use App\Models\Location;
use Illuminate\Support\Facades\Storage;
use App\Repositories\GeneralFunctionsRepository;
use Carbon\Carbon;
use Image;

class PollController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->sub_seccion_id = 10;
        $this->GeneralFunctionsRepository = new GeneralFunctionsRepository();
    }

    public function pollShow(){

        $temp_request = new Request();
        $temp_request->setMethod('GET');
        $temp_request->query->add(['limit' => 10]);
        $list = $this->adminPollList($temp_request);
        $submodule = "poll";
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

        return view('admin.news.poll.list')->with($compact);

    }

    public function publicPollList(Request $request){

        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '10';
        $poll_list = Poll::join('bucket_locations','polls.id','bucket_locations.origin_record_id')
        ->where('bucket_locations.subgroups_id',session('location_user'))
        ->whereNull('polls.deleted_at')
        ->whereNull('bucket_locations.deleted_at')
        ->where('bucket_locations.sub_seccion_id',10)
        ->whereDate('polls.expiration_date', '>=', Carbon::now())
        ->orderBy('polls.created_at','desc')
        ->select('polls.*')
        ->Paginate($limit);

        $poll_list->setPath('/news/poll');

        return $poll_list;
    }


    public function adminPollList(Request $request){

        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '10';
        $search = isset($request->search) ? json_decode(json_encode($request->search),false) : '';
        $expiration_date = (isset($request->expiration_date) && $request->expiration_date != null) ? $request->expiration_date : '';
        $poll_list= [];

        if(isset($search->isSearch) && strlen($search->text) > 0){
            $poll_list = Poll::whereRaw("(title like '%$search->text%'
                or description like '%$search->text%'
                or expiration_date like '%$search->text%')")
            ->orderBy('expiration_date',$order_by)->Paginate($limit);
        }else{
            $poll_list = Poll::orderBy('expiration_date',$order_by)
            ->Paginate($limit);
        }

        $poll_list->setPath('/admin/news/poll-list');

        return $poll_list;
    }

    public function pollSave(Request $request){

        //Se validan los datos que llegan
        $validatedData = $request->validate([
            'locations' => 'required|array',
            'title' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpg,png,|max:5000',
            'description' => 'nullable|string',
            'link' => 'required|string',
            'expiration_date' => 'required|date',
        ]);

        // Se almacena la imagen
        $file = $request->file('photo');
        $extension = $file->getClientOriginalExtension();
        $fileName = time().'_poll.';
        $fileName = $fileName.$extension;
        $path = '/news/'.$fileName;
        \Storage::disk('public')->put($path,\File::get($file));
        $this->GeneralFunctionsRepository->uploadImgServerGrupoDMI($request->file('photo'),$fileName);

        $newRecord = new Poll();
        $newRecord->title = $request->title;
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

    public function pollEdit(Request $request){

        $path = null;
        //Se validan los datos que llegan
        $validatedData = $request->validate([
            'locations' => 'required|array',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'required|string',
            'expiration_date' => 'required|date',
        ]);

        if($request->file('photo') != null){
            $validatePhoto = $request->validate([
                'photo' => 'required|image|mimes:jpg,png|max:5000',
            ]);
            // Se almacena la imagen
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'_poll.';
            $fileName = $fileName.$extension;
            $path = '/news/'.$fileName;
            \Storage::disk('public')->put($path,\File::get($file));
            $this->GeneralFunctionsRepository->uploadImgServerGrupoDMI($request->file('photo'),$fileName);

        }

        $editRecord = Poll::where('id',$request->poll)->first();
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

            $locations_old = BucketLocation::where('origin_record_id',$request->poll)
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

    public function pollDelete($_id){

        if(isset($_id) && $_id > 0){
            $record = Poll::where('id',$_id)->first();
            if($record != null){
                $this->GeneralFunctionsRepository->deleteFile(['url'=>$record->photo]);

                BucketLocation::where('origin_record_id',$record->id)
                                ->where('sub_seccion_id',$this->sub_seccion_id)
                                ->delete();

                /* Start - Auditoria */
                $params=[
                    "sub_seccion_id" =>$this->sub_seccion_id,
                    "ip" => $this->GeneralFunctionsRepository->getIp(),
                    "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
                    "comment" => "Registro => ".$_id,
                ];
                $this->GeneralFunctionsRepository->addAudit($params);
                /* End - Auditoria */

                $record->delete();
                return ['success' => 1, 'message' => "Registro eliminado con éxito"];
            }else{
                return ['success' => 0, 'message' => "No existe el registro enviado."];
            }

        }else{
            return ['success' => 0, 'message' => "No es valido el registro enviado."];
        }

    }


    public function sendEmails($_record_id,$_sub_seccion_id){

        $record = Poll::find($_record_id);

        if($record != null){
            $users_email = $this->GeneralFunctionsRepository->getUsersByLocation($_record_id,$_sub_seccion_id);
            $cover_img_name = $this->GeneralFunctionsRepository->getCoverNameImg($record->photo);

            $mail_data = [
                'sub_seccion' => "poll",
                'recipient_emails' =>$users_email,
                'subject' => "Encuesta - $record->title",
                'link' => url('news').'?section=pane-surveys',
                'title' => "Encuesta - $record->title",
                'description' => $record->description,
                'link_data' => $record->link,
                'photo' =>"https://www.grupodmi.com.mx/intranet/img/comunicados/$cover_img_name",
            ];

            $this->GeneralFunctionsRepository->sendEmails($mail_data);
        }

    }



}
