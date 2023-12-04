<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Communique;
use App\Models\File;
use App\Models\BucketLocation;
use App\Models\CommuniqueFile;
use App\Models\Location;
use App\Models\User;
use App\Models\Subgroup;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Repositories\GeneralFunctionsRepository;
use App\Video;
use Image;

class CommunicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->hoy = Carbon::today();
        $this->sub_seccion_id = null;
        $this->GeneralFunctionsRepository = new GeneralFunctionsRepository();
    }

    public function index(){


        $params=[
            "sub_seccion_id" =>27,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Cominicados",
        ];
        $this->GeneralFunctionsRepository->addAudit($params);

        $temp_request = new Request();
        $temp_request->setMethod('GET');
        $temp_request->query->add(['limit'=>3,
                                    'expiration_date' => $this->hoy,
                                    ]);

        $communiques_council = $this->communiques_council($temp_request);
        $communiques_organizational = $this->communiques_organizational($temp_request);

        $temp_request1 = new Request();
        $temp_request1->setMethod('GET');
        $temp_request1->query->add(['limit'=>100,
                                    'expiration_date' => $this->hoy,
                                    ]);

        $communiques_institutional = $this->communiques_institutional($temp_request1);
        $compact = compact('communiques_council','communiques_organizational','communiques_institutional');

        return view('dmi_comunicados.dmi_comunicados')->with($compact);

    }

    public function getListUser(Request $request)
    {
        if($request->type == "location"){
            $subgroups = Subgroup::join('vw_locations','vw_locations.name','=','subgroups.vw_locations_name')
                                ->whereIn('vw_locations.id',$request->locations)
                                ->whereNull('vw_locations.deleted_at')
                                ->whereNull('subgroups.deleted_at')
                                ->select('subgroups.name')
                                ->get()
                                ->pluck('name');

            $users = User::whereIn('group',$subgroups)
                            ->whereNotNull('email')
                            ->where('email', '!=', '')
                            ->orderBy('name', 'asc')->get(); 
    

        }else{
            $relation = "subgroups";

            $users = User::join('subgroups','subgroups.name','=','vw_users.group')
            ->whereIn('subgroups.id',$request->locations)
            ->where('vw_users.status', 'Alta')
            ->where('vw_users.email', '!=', '')
            ->whereNotNull('vw_users.email')
            ->orderBy('vw_users.name', 'asc')
            ->select('vw_users.*')
            ->get();
        }

        return response()->json($users);
    }

    public function show($id){
        $communique=Communique::where('id',$id)->first();
        $compact = compact('communique');

        return $compact;
        //return view('dmi_comunicados.detail_comunicado')->with($compact);
    }

    public function showCommunique($id){
        $communique=Communique::where('id',$id)->first();

        $compact = compact('communique');

        //return $compact;
        return view('dmi_comunicados.detail_comunicado')->with($compact);
    }

    public function communiques_council(Request $request ){

        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '3';
        $search = isset($request->search) ? json_decode(json_encode($request->search),false) : '';
        $expiration_date = isset($request->expiration_date) ? ($request->expiration_date) : '';
        $communiques = [];

        $communiques = Communique::join('bucket_locations','communiques.id','bucket_locations.origin_record_id')
            ->where('bucket_locations.subgroups_id',session('location_user'))
            ->whereNull('communiques.deleted_at')
            ->whereNull('bucket_locations.deleted_at')
            ->where('bucket_locations.sub_seccion_id',2)
            ->where('communiques.type','consejo')
            ->whereDate('communiques.expiration_date', '>=', $expiration_date)->orderBy('communiques.created_at',$order_by)
            ->select('communiques.*')
            ->Paginate($limit);

        $communiques->setPath('/dmi_comunicados/council');
        return $communiques;

    }

    public function communiques_organizational(Request $request){

        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '3';
        $search = isset($request->search) ? json_decode(json_encode($request->search),false) : '';
        $expiration_date = isset($request->expiration_date) ? ($request->expiration_date) : '';
        $communiques = [];

        $communiques = Communique::join('bucket_locations','communiques.id','bucket_locations.origin_record_id')
            ->where('bucket_locations.subgroups_id',session('location_user'))
            ->whereNull('communiques.deleted_at')
            ->whereNull('bucket_locations.deleted_at')
            ->where('bucket_locations.sub_seccion_id',3)
            ->where('communiques.type','organizacional')
            ->whereDate('communiques.expiration_date', '>=', $expiration_date)->orderBy('communiques.created_at',$order_by)
            ->select('communiques.*')
            ->Paginate($limit);

        $communiques->setPath('/dmi_comunicados/organizational');
        return $communiques;

    }

    public function communiques_institutional(Request $request){

        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $limit =(isset($request->limit) && $request->limit > 0) ? $request->limit : '100';
        $search = isset($request->search) ? json_decode(json_encode($request->search),false) : '';
        $expiration_date = isset($request->expiration_date) ? ($request->expiration_date) : '';
        $communiques = [];

        $communiques = Communique::join('bucket_locations','communiques.id','bucket_locations.origin_record_id')
            ->where('bucket_locations.subgroups_id',session('location_user'))
            ->whereNull('communiques.deleted_at')
            ->whereNull('bucket_locations.deleted_at')
            ->where('bucket_locations.sub_seccion_id',4)
            ->where('communiques.type','institucional')
            ->whereDate('communiques.expiration_date', '>=', $expiration_date)
            ->orderBy('communiques.created_at',$order_by)
            ->select('communiques.*')
            ->Paginate($limit);

        $communiques->setPath('/dmi_comunicados/institutional');
        return $communiques;

    }

    public function communiques_council_admin(Request $request ){

        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '3';
        $search = isset($request->search) ? json_decode(json_encode($request->search),false) : '';
        $expiration_date = isset($request->expiration_date) ? ($request->expiration_date) : '';
        $communiques = [];

        if(isset($search->isSearch) && strlen($search->text) > 0){
            $communiques = Communique::where('type','consejo')
            ->whereRaw("(title like '%$search->text%'
            or priority like '%$search->text%'
            or description like '%$search->text%'
            or expiration_date like '%$search->text%')")
            ->orderBy('expiration_date',$order_by)
            ->Paginate($limit);
        }else{

            $communiques = Communique::where('type','consejo')
            ->whereDate('expiration_date', '>=', $expiration_date)->orderBy('expiration_date',$order_by)
            ->Paginate($limit);
        }

        $communiques->setPath('/admin/communiques/list-admin-council');
        return $communiques;

    }

    public function communiques_organizational_admin(Request $request){

        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '3';
        $search = isset($request->search) ? json_decode(json_encode($request->search),false) : '';
        $expiration_date = isset($request->expiration_date) ? ($request->expiration_date) : '';
        $communiques = [];

        if(isset($search->isSearch) && strlen($search->text) > 0){
            $communiques = Communique::where('type','organizacional')
            ->whereRaw("(title like '%$search->text%'
            or priority like '%$search->text%'
            or description like '%$search->text%'
            or expiration_date like '%$search->text%')")
            ->orderBy('expiration_date',$order_by)
            ->Paginate($limit);
        }else{

            $communiques = Communique::where('type','organizacional')
            ->whereDate('expiration_date', '>=', $expiration_date)->orderBy('expiration_date',$order_by)
            ->Paginate($limit);
        }

        $communiques->setPath('/admin/communiques/list-admin-organizational');
        return $communiques;

    }

    public function communiques_institutional_admin(Request $request){

        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $limit =(isset($request->limit) && $request->limit > 0) ? $request->limit : '3';
        $search = isset($request->search) ? json_decode(json_encode($request->search),false) : '';
        $expiration_date = isset($request->expiration_date) ? ($request->expiration_date) : '';
        $communiques = [];

        if(isset($search->isSearch) && strlen($search->text) > 0){
            $communiques = Communique::where('communiques.type','institucional')
            ->whereRaw("(title like '%$search->text%'
            or priority like '%$search->text%'
            or description like '%$search->text%'
            or expiration_date like '%$search->text%')")
            ->orderBy('expiration_date',$order_by)
            ->Paginate($limit);
        }else{

            $communiques = Communique::where('type','institucional')
            ->whereDate('expiration_date', '>=', $expiration_date)
            ->orderBy('expiration_date',$order_by)
            ->Paginate($limit);
        }

        $communiques->setPath('/admin/communiques/list-admin-institutional');
        return $communiques;

    }

    public function listCouncil(){

        $temp_request = new Request();
        $temp_request->setMethod('GET');
        $temp_request->query->add(['limit'=>10]);
        $communiques_list= $this->communiques_council_admin($temp_request);
        $communique_type = "council";
        $compact = compact('communiques_list','communique_type');
        return view('admin.communiques.list')->with($compact);
    }

    public function listOrganizational(){

        $temp_request = new Request();
        $temp_request->setMethod('GET');
        $temp_request->query->add(['limit'=>10]);
        $communiques_list= $this->communiques_organizational_admin($temp_request);
        $communique_type = "organizational";
        $compact = compact('communiques_list','communique_type');
        return view('admin.communiques.list')->with($compact);
    }

    public function listInstitutional(){

        $temp_request = new Request();
        $temp_request->setMethod('GET');
        $temp_request->query->add(['limit'=>10]);
        $communiques_list= $this->communiques_institutional_admin($temp_request);
        $communique_type = "institutional";
        $compact = compact('communiques_list','communique_type');
        return view('admin.communiques.list')->with($compact);
    }

    public function save(Request $request){

        /* Pruebas */

        /* End - Pruebas */


        $communique_type = "";
        $fileName = "";
        $docName ="";
        if($request->communique_type =="council"){
            $communique_type = "consejo";
            $fileName = time().'_council.';
            $docName = '_council';
            $this->sub_seccion_id = 2;
        }else if($request->communique_type =="organizational"){
            $communique_type = "organizacional";
            $fileName = time().'_organizational.';
            $docName = '_organizational';
            $this->sub_seccion_id = 3;
        }else if($request->communique_type =="institutional"){
            $communique_type = "institucional";
            $fileName = time().'_institutional.';
            $docName = '_institutional';
            $this->sub_seccion_id = 4;
        }


        //Se validan los datos que llegan
        $validatedData=$request->validate([
            'locations' => 'required|array',
            'expiration_date' => 'required|date',
            'priority' => 'required|in:Alta,Media,Baja|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'required|image|mimes:jpg,png|max:5000',
            'link' => 'nullable|string|max:255',
        ]);

        // Se almacena la imagen
        $file = $request->file('photo');
        $extension = $file->getClientOriginalExtension();
        $fileName = $fileName.$extension;
        $path = '/communique/'.$fileName;
        \Storage::disk('public')->put($path,\File::get($file));
        $this->GeneralFunctionsRepository->uploadImgServerGrupoDMI($request->file('photo'),$fileName);

        // Se almacena el video
        $path_video=NULL;
        if($request->file('video') != null){
            $fileName = time().$docName.'.';
            $file = $request->file('video');
            $extension = $file->getClientOriginalExtension();
            $fileName = $fileName.$extension;
            $path_video = '/communique/'.$fileName;
            \Storage::disk('public')->put($path_video,\File::get($file));
            $path_video = 'storage'.$path_video;
        }


        $communique = new Communique();
        $communique->title = $request->title;
        $communique->priority = $request->priority;
        $communique->expiration_date = $request->expiration_date;
        $communique->link = $request->link;
        $communique->description = $request->description;
        $communique->photo = 'storage'.$path;
        $communique->video = $path_video;
        $communique->type = $communique_type;
        $communique->save();

        //Se almacenan los id de las ubicaciones creadas
        if($communique != null){

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
                $bucket_location->origin_record_id = $communique->id;
                $bucket_location->locations_id = $subgroup->vw_locations_id;
                $bucket_location->subgroups_id = $subgroup->subgroups_id;
                $bucket_location->sub_seccion_id = $this->sub_seccion_id;
                $bucket_location->save();
            }

        }

        // Se verifica si hay documentos cargados
        if($request->cont_files > 0 && $communique != null){

            for ($i=0; $i <$request->cont_files ; $i++) {
                // Se almacena el documento
                $fileName = time().$docName;
                $doc = $request['files_'.$i];
                $extension = $doc->getClientOriginalExtension();
                $fileName = $fileName.'_'.$i.'.'.$extension;
                $path = '/communique/'.$fileName;
                \Storage::disk('public')->put($path,\File::get($doc));

                $file = new File();
                $file->name = $doc->getClientOriginalName();
                $file->file = 'storage'.$path;
                $file->extension = $doc->getClientOriginalExtension();
                $file->type_file = $doc->getClientOriginalExtension();
                $file->save();

                if($file != null){
                    $communique_file = new CommuniqueFile();
                    $communique_file->file_id = $file->id;
                    $communique_file->communique_id = $communique->id;
                    $communique_file->save();
                }
            }
        }

        $params=[
            "sub_seccion_id" =>$this->sub_seccion_id,
            "ip" => $request->ip(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "comunicado => ".$communique->id,
        ];
        $this->GeneralFunctionsRepository->addAudit($params);

        $this->sendEmails($communique->id,$this->sub_seccion_id);

        return ['success'=> 1, 'data'=>$communique];

    }

    public function edit(Request $request){
        $fileName = "";

        if($request->communique_type =="council"){
            $fileName = time().'_council.';
            $docName = '_council';
            $this->sub_seccion_id = 2;
        }else if($request->communique_type =="organizational"){
            $fileName = time().'_organizational.';
            $docName = '_organizational';
            $this->sub_seccion_id = 3;
        }else if($request->communique_type =="institutional"){
            $fileName = time().'_institutional.';
            $docName = '_institutional';
            $this->sub_seccion_id = 4;
        }

        $path = null;
        //Se validan los datos que llegan
        $validatedData=$request->validate([
            'locations' => 'required|array',
            'expiration_date' => 'required|date',
            'priority' => 'required|in:Alta,Media,Baja|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|string|max:255',
        ]);

        if($request->file('photo') != null){
            $validatePhoto = $request->validate([
                'photo' => 'required|image|mimes:jpg,png|max:5000',
            ]);
            // Se almacena la imagen
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $fileName = $fileName.$extension;
            $path = '/communique/'.$fileName;
            \Storage::disk('public')->put($path,\File::get($file));
            $this->GeneralFunctionsRepository->uploadImgServerGrupoDMI($request->file('photo'),$fileName);

        }

        // Se almacena el video
        $path_video="";
        if($request->file('video') != null){

            $fileName = time().$docName.'.';
            $file = $request->file('video');
            $extension = $file->getClientOriginalExtension();
            $fileName = $fileName.$extension;
            $path_video = '/communique/'.$fileName;
            \Storage::disk('public')->put($path_video,\File::get($file));
        }


        $communique = Communique::where('id',$request->communique)->first();
        $communique->title = $request->title;
        $communique->priority = $request->priority;
        $communique->expiration_date = $request->expiration_date;
        $communique->link = $request->link;
        $communique->description = $request->description;

        if($request->file('photo') != null){
            //Se elimina la imagen física que se reemplazara
            $this->GeneralFunctionsRepository->deleteFile(['url'=>$communique->photo]);
            $communique->photo = 'storage'.$path;
        }
        if($request->file('video') != null){
            //Se elimina el video física que se reemplazara
            $this->GeneralFunctionsRepository->deleteFile(['url'=>$communique->video]);
            $communique->video = 'storage'.$path_video;
        }

        $communique->save();

        //Se eliminan las ubicaciones actuales y se almacenan los id de las ubicaciones creadas
        if($communique != null){

            $locations_old = BucketLocation::where('origin_record_id',$request->communique)
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
                $bucket_location->origin_record_id = $communique->id;
                $bucket_location->locations_id = $subgroup->vw_locations_id;
                $bucket_location->subgroups_id = $subgroup->subgroups_id;
                $bucket_location->sub_seccion_id = $this->sub_seccion_id;
                $bucket_location->save();
            }

        }

        // Se verifica si hay documentos cargados
        if($request->cont_files > 0 && $communique != null){

            for ($i=0; $i <$request->cont_files ; $i++) {
                // Se almacena el documento
                $fileName = time().$docName;
                $doc = $request['files_'.$i];
                $extension = $doc->getClientOriginalExtension();
                $fileName = $fileName.'_'.$i.'.'.$extension;
                $path = '/communique/'.$fileName;
                \Storage::disk('public')->put($path,\File::get($doc));

                $file = new File();
                $file->name = $doc->getClientOriginalName();
                $file->file = 'storage'.$path;
                $file->extension = $doc->getClientOriginalExtension();
                $file->type_file = $doc->getClientOriginalExtension();
                $file->save();

                if($file != null){
                    $communique_file = new CommuniqueFile();
                    $communique_file->file_id = $file->id;
                    $communique_file->communique_id = $communique->id;
                    $communique_file->save();
                }
            }
        }

        //$this->sendEmails($communique->id,$this->sub_seccion_id);

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>$this->sub_seccion_id,
            "ip" => $request->ip(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "comunicado => ".$communique->id,
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return ['success'=> 1, 'data'=>$communique];

    }

    public function delete($_id){

        if(isset($_id) && $_id > 0){
            $communique = Communique::where('id',$_id)->first();

            if($communique != null){
                BucketLocation::where('origin_record_id',$communique->id)
                                ->where('sub_seccion_id',$communique->sub_seccion_id)
                                ->delete();

                $communique->delete();

                $subseccion = 2;
                if($communique->type == "consejo"){
                    $subseccion = 2;
                }else if($communique->type == "organizacional"){
                    $subseccion = 3;
                }else if($communique->type == "institucional"){
                    $subseccion = 4;
                }

                $params=[
                    "sub_seccion_id" =>$subseccion,
                    "ip" => $this->GeneralFunctionsRepository->getIp(),
                    "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
                    "comment" => "comunicado => ".$communique->id,
                ];
                $this->GeneralFunctionsRepository->addAudit($params);

                //Se buscan los archivos que tenga para eliminarlos solo logicamente
                $communiqueFiles = CommuniqueFile::where('communique_id',$_id)->get();
                if($communiqueFiles != null){
                    foreach($communiqueFiles as $comFile){

                        $file = File::where('id',$comFile->file_id)->first();
                        if($file != null){
                            $file->delete();
                        }
                        $comFile->delete();

                        return ['success' => 1, 'message' => "Registro eliminado con éxito"];
                    }
                }else{
                    return ['success' => 1, 'message' => "Registro eliminado con éxito"];
                }

            }else{
                return ['success' => 0, 'message' => "No existe el registro enviado."];

            }

        }else{
            return ['success' => 0, 'message' => "No es valido el registro enviado."];
        }

    }

    public function deleteFile($_file_id,$_communique_id){

        if((isset($_file_id) && $_file_id > 0) && (isset($_communique_id) && $_communique_id > 0)){
            $communiqueFile = CommuniqueFile::where('file_id',$_file_id)->where('communique_id',$_communique_id)->first();

            if($communiqueFile != null){
                $file = File::where('id',$_file_id)->first();

                if($file != null){
                    $this->GeneralFunctionsRepository->deleteFile(['url'=>$file->file]);
                    $file->delete();
                    $communiqueFile->delete();

                    /* Start - Auditoria */
                    $params=[
                        "sub_seccion_id" =>20,
                        "ip" => $this->GeneralFunctionsRepository->getIp(),
                        "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
                        "comment" => "Comunicados",
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

    public function sendEmails($_record_id,$_sub_seccion_id)
    {

        $record = Communique::find($_record_id);

        if($record != null){
            $users_email = $this->GeneralFunctionsRepository->getUsersByLocation($_record_id,$_sub_seccion_id);
            $cover_img_name = $this->GeneralFunctionsRepository->getCoverNameImg($record->photo);

            $mail_data = [
                'sub_seccion' => "communication",
                'recipient_emails' =>$users_email,
                'subject' => "Comunicado - $record->title",
                'link' => url('dmi_comunicados'),
                'title' => "Comunicado - $record->title",
                'description' => $record->description,
                'photo' =>"https://www.grupodmi.com.mx/intranet/img/comunicados/$cover_img_name",
                'link_data' => $record->link,
            ];

            $this->GeneralFunctionsRepository->sendEmails($mail_data);
        }
    }

    public function sendReminder(int $_record_id)
    {
        $record = Communique::find($_record_id);

        if($record != null){
            $users_email = $this->GeneralFunctionsRepository->getUsersByLocation($_record_id, $record->bucket_location[0]->sub_seccion_id);
            $cover_img_name = $this->GeneralFunctionsRepository->getCoverNameImg($record->photo);

            $mail_data = [
                'sub_seccion' => "communication",
                'recipient_emails' =>$users_email,
                'subject' => "Recordatorio - Comunicado - $record->title",
                'link' => url('dmi_comunicados'),
                'title' => "Comunicado - $record->title",
                'description' => $record->description,
                'photo' =>"https://www.grupodmi.com.mx/intranet/img/comunicados/$cover_img_name",
                'link_data' => $record->link,
            ];

            $this->GeneralFunctionsRepository->sendEmails($mail_data);

            return ['success' => 1, 'message' => "Recordatorio enviado con éxito"];
        }
    }


}
