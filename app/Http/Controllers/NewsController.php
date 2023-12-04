<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConmmemorativeDate;
use App\Models\BucketLocation;
use App\Models\Location;
use Illuminate\Support\Facades\Storage;
use App\Repositories\GeneralFunctionsRepository;
use App\Http\Controllers\InternalPostingController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\AreaNoticeController;
use App\Http\Controllers\PolicyController;
use Carbon\Carbon;
use Image;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
        $this->sub_seccion_id = 8;
        $this->GeneralFunctionsRepository = new GeneralFunctionsRepository();
        $this->InternalPostingController = new InternalPostingController();
        $this->PollController = new PollController();
        $this->AreaNoticeController = new AreaNoticeController();
        $this->PolicyController = new PolicyController();
    }

    public function index()
    {
        //
        /* Start - Auditoria */
            $params=[
                "sub_seccion_id" =>29,
                "ip" => $this->GeneralFunctionsRepository->getIp(),
                "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
                "comment" => "Noticias",
            ];
            $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        $hoy = Carbon::today();
        $temp_request = new Request();
        $temp_request->setMethod('GET');
        $temp_request->query->add(['limit'=>10,
                                    'expiration_date' => $hoy
                                    ]);
        $conmmemorative_list = $this->conmmemorativeDateListPublic($temp_request);
        $internal_posting_list = $this->InternalPostingController->internalPostingListPublic($temp_request);
        $poll_list = $this->PollController->publicPollList($temp_request);
        $area_notice_list = $this->AreaNoticeController->publicAreaNoticeList($temp_request);
        $policy_list = $this->PolicyController->publicPolicyList($temp_request);


        $compact = compact('conmmemorative_list','internal_posting_list','poll_list','area_notice_list','policy_list');
        return view('news.layout')->with($compact);

    }

    public function conmmemorativeDateListPublic(Request $request){

        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '10';
        $search = isset($request->search) ? json_decode(json_encode($request->search),false) : '';
        $expiration_date = (isset($request->expiration_date) && $request->expiration_date != null) ? $request->expiration_date : '';
        $conmmemorative_date= [];

        $conmmemorative_date = ConmmemorativeDate::join('bucket_locations','conmmemorative_date.id','bucket_locations.origin_record_id')
        ->where('bucket_locations.subgroups_id',session('location_user'))
        ->whereNull('conmmemorative_date.deleted_at')
        ->whereNull('bucket_locations.deleted_at')
        ->where('bucket_locations.sub_seccion_id',8)
        ->whereDate('conmmemorative_date.expiration_date', '>=', $expiration_date)
        ->orderBy('conmmemorative_date.created_at',$order_by)
        ->select('conmmemorative_date.*')
        ->Paginate($limit);

        $conmmemorative_date->setPath('/news/conmmemorative-date');

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>8,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Listado",
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */


        return $conmmemorative_date;
    }

    public function conmmemorativeDateList(Request $request){

        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '10';
        $search = isset($request->search) ? json_decode(json_encode($request->search),false) : '';
        $expiration_date = (isset($request->expiration_date) && $request->expiration_date != null) ? $request->expiration_date : '';
        $conmmemorative_date= [];

        if(isset($search->isSearch) && strlen($search->text) > 0){
            $conmmemorative_date = ConmmemorativeDate::whereRaw("(title like '%$search->text%'
                or description like '%$search->text%'
                or publication_date like '%$search->text%'
                or expiration_date like '%$search->text%')")
            ->orderBy('expiration_date',$order_by)->Paginate($limit);
        }else{

            $conmmemorative_date = ConmmemorativeDate::whereDate('expiration_date', '>=', $expiration_date)->orderBy('expiration_date',$order_by)
            ->Paginate($limit);
        }

        $conmmemorative_date->setPath('/admin/news/conmmemorative_date-list-admin');

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>8,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Listado",
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return $conmmemorative_date;
    }


    public function conmemmorativeDateShow(){

        $temp_request = new Request();
        $temp_request->setMethod('GET');
        $temp_request->query->add(['limit' => 10]);
        $list = $this->conmmemorativeDateList($temp_request);
        $submodule = "conmmemorative_date";
        $compact = compact('list','submodule');

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>8,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Listado",
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return view('admin.news.conmmemorative_date.list')->with($compact);

    }


    public function conmemmorativeDateSave(Request $request){
        $this->sub_seccion_id = 8;

        //Se validan los datos que llegan
        $validatedData = $request->validate([
            'locations' => 'required|array',
            'title' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpg,png,|max:5000',
            'publication_date' => 'required|date',
            'expiration_date' => 'required|date',
        ]);

        // Se almacena la imagen
        $file = $request->file('photo');
        $extension = $file->getClientOriginalExtension();
        $fileName = time().'_conmmemorative_date.';
        $fileName = $fileName.$extension;
        $path = '/news/'.$fileName;
        \Storage::disk('public')->put($path,\File::get($file));

        $conmmemorative = new ConmmemorativeDate();
        $conmmemorative->title = $request->title;
        $conmmemorative->photo = 'storage'.$path;
        $conmmemorative->description = $request->description;
        $conmmemorative->publication_date = $request->publication_date;
        $conmmemorative->expiration_date = $request->expiration_date;
        $conmmemorative->save();

        //Se almacenan los id de las ubicaciones creadas
        if($conmmemorative != null){

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
                $bucket_location->origin_record_id = $conmmemorative->id;
                $bucket_location->locations_id = $subgroup->vw_locations_id;
                $bucket_location->subgroups_id = $subgroup->subgroups_id;
                $bucket_location->sub_seccion_id = $this->sub_seccion_id;
                $bucket_location->save();
            }

        }

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>$this->sub_seccion_id,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Registro => ".$conmmemorative->id,
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return ['success'=> 1, 'data'=>$conmmemorative];


    }

    public function conmemmorativeDateEdit(Request $request){
        $this->sub_seccion_id = 8;

        $path = null;
        //Se validan los datos que llegan
        $validatedData = $request->validate([
            'locations' => 'required|array',
            'title' => 'required|string|max:255',
            'publication_date' => 'required|date',
            'expiration_date' => 'required|date',
        ]);

        if($request->file('photo') != null){
            $validatePhoto = $request->validate([
                'photo' => 'required|image|mimes:jpg,png|max:5000',
            ]);
            // Se almacena la imagen
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'_conmmemorative_date.';
            $fileName = $fileName.$extension;
            $path = '/news/'.$fileName;
            \Storage::disk('public')->put($path,\File::get($file));

        }

        $conmmemorative = ConmmemorativeDate::where('id',$request->conmmemorative_date)->first();
        $conmmemorative->title = $request->title;
        $conmmemorative->description = $request->description;
        $conmmemorative->publication_date = $request->publication_date;
        $conmmemorative->expiration_date = $request->expiration_date;

        if($request->file('photo') != null){
            $conmmemorative->photo = 'storage'.$path;
        }

        $conmmemorative->save();

        //Se eliminan las ubicaciones actuales y se almacenan los id de las ubicaciones creadas
        if($conmmemorative != null){

            $locations_old = BucketLocation::where('origin_record_id',$request->conmmemorative_date)
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
                $bucket_location->origin_record_id = $conmmemorative->id;
                $bucket_location->locations_id = $subgroup->vw_locations_id;
                $bucket_location->subgroups_id = $subgroup->subgroups_id;
                $bucket_location->sub_seccion_id = $this->sub_seccion_id;
                $bucket_location->save();
            }

        }

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>$this->sub_seccion_id,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Registro => ".$conmmemorative->id,
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return ['success'=> 1, 'data'=>$conmmemorative];

    }

    public function conmemmorativeDateDelete($_id){

        if(isset($_id) && $_id > 0){
            $conmmemorative = ConmmemorativeDate::where('id',$_id)->first();
            if($conmmemorative != null){
                $this->GeneralFunctionsRepository->deleteFile(['url'=>$conmmemorative->photo]);

                BucketLocation::where('origin_record_id',$conmmemorative->id)
                                ->where('sub_seccion_id',$this->sub_seccion_id)
                                ->delete();

                $conmmemorative->delete();

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

}
