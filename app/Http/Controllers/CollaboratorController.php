<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TemplanteCollaborator;
use App\Models\Location;
use App\Models\BirthCollaborator;
use App\Models\CondolenceCollaborator;
use App\Repositories\GeneralFunctionsRepository;
use App\Models\Promotion;
use App\Models\BucketLocation;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CollaboratorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->sub_seccion_id ="";
        $this->GeneralFunctionsRepository = new GeneralFunctionsRepository();
    }
    public function index(){
        /* Start - Auditoria */
            $params=[
                "sub_seccion_id" =>28,
                "ip" => $this->GeneralFunctionsRepository->getIp(),
                "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
                "comment" => "Colaboradores",
            ];
            $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        $months = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
        $birthdays=[];
        $new_staff=[];
        $anniversaries=[];
        $message_anniversaries=[];
        $message_new_staff=[];
        $promotions=[];
        $birthdayCollaborator=[];
        $condolence=[];
        foreach ($this->templante('aniversario') as $value) {
            $message_anniversaries[$value->position_company]=['message'=>$value->message , 'photo' => $value->photo];
        }
        foreach ($this->templante('ingreso') as $value) {
            $message_new_staff[$value->position_company]=['message'=>$value->message , 'photo' => $value->photo];
        }
        for ($i=1; $i <=12 ; $i++) {
            array_push($promotions,$this->promotion($i));
            array_push($birthdayCollaborator,$this->birthday($i));
            array_push($birthdays,$this->birth($i));
            array_push($new_staff,$this->new_staff($i));
            array_push($condolence,$this->condolence($i));
            array_push($anniversaries,$this->anniversaries($i));
        }
        $url=url('/');
        return view('collaborators.colaboradores',['birthdays'=>$birthdays, 'new_staff' => $new_staff,
        'promotions'=>$promotions,'birthdayCollaborators' => $birthdayCollaborator, 'condolences' => $condolence,
        'message_anniversaries' => $message_anniversaries, 'message_new_staff' => $message_new_staff,
        'anniversaries' => $anniversaries,'months'=>$months, 'url' =>$url]);
    }
    protected function birth($mont){

        return User::with('publication_birthday')->select('vw_users.*','vw_locations.photo as photo_location')
        ->join('vw_locations', 'vw_users.location', '=', 'vw_locations.name')
        ->whereMonth('birth',$mont)->orderByRaw('DAY(birth)')->get();
    }
    protected function new_staff($mont){

        $year = Carbon::now()->format('Y');
        return User::with('commanding_staff')->select('vw_users.*','vw_locations.photo as photo_location')
        ->join('vw_locations', 'vw_users.location', '=', 'vw_locations.name')
        ->whereMonth('vw_users.antiquity_date',$mont)->whereYear('vw_users.antiquity_date',$year)->orderByRaw('DAY(vw_users.antiquity_date) desc')->get();
    }
    protected function anniversaries($mont){

        $year = Carbon::now()->format('Y');
        return User::select('vw_users.*','vw_locations.photo as photo_location')
        ->join('vw_locations', 'vw_users.location', '=', 'vw_locations.name')
        ->whereMonth('antiquity_date',$mont)->whereYear('antiquity_date','<',$year)->orderByRaw('DAY(antiquity_date)')->get();
    }

    protected function promotion($mont){

        $year = Carbon::now()->format('Y');
        return Promotion::whereMonth('created_at',$mont)->whereYear('created_at',$year)->orderByRaw('DAY(created_at) desc')->get();
    }

    protected function birthday($mont){

        $year = Carbon::now()->format('Y');
        return BirthCollaborator::with('templanteCollaborator', 'user', 'user.locations')
        ->whereMonth('birth',$mont)->whereYear('birth',$year)->orderByRaw('DAY(birth) desc')->get();
    }

    protected function condolence($mont){

        $year = Carbon::now()->format('Y');
        return CondolenceCollaborator::with('templanteCollaborator', 'user', 'user.locations')->whereMonth('condolence_date',$mont)
        ->whereYear('condolence_date',$year)->orderByRaw('DAY(condolence_date) desc')->get();
    }

    protected function templante($type){
         return TemplanteCollaborator::where('type',$type)->get();
    }

    /***************************************** START - Nacimientos ***************************************** */

    public function birthShow(){

        $temp_request = new Request();
        $temp_request->setMethod("GET");
        $temp_request->query->add(["limit" => 10]);
        $list = $this->birthListAdmin($temp_request);
        $submodule="births";
        $compact = compact('list','submodule');

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>6,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Listado admin => ",
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return view('admin.collaborators.births.list')->with($compact);
    }

    public function birthList(Request $request){

        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '10';
        $search = isset($request->search) ? json_decode(json_encode($request->search),false) : '';
        $births = [];

        $births = BirthCollaborator::with(['user','templanteCollaborator'])->orderBy('created_at',$order_by)
            ->Paginate($limit);

        $births->setPath('/collaborators/births');

        return $births;


    }

    public function birthListAdmin(Request $request){

        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '10';
        $search = isset($request->search) ? json_decode(json_encode($request->search),false) : '';
        $births = [];

        if(isset($search->isSearch) && strlen($search->text) > 0){
            $births = BirthCollaborator::with(['user','templanteCollaborator'])
            ->whereRaw("(birth like '%$search->text%')")
            ->orderBy('created_at',$order_by)->Paginate($limit);
        }else{

            $births = BirthCollaborator::with(['user','templanteCollaborator'])->orderBy('created_at',$order_by)
            ->Paginate($limit);
        }

        $births->setPath('/admin/collaborators/birth-list-admin');

        return $births;


    }

    public function birthSave(Request $request){

        //Se validan los datos que llegan
        $validatedData=$request->validate([
            'birth' => 'required|date',
            'user' => 'required|string|max:255',
            'sex' => 'required|in:Femenino,Masculino|string|max:50',
            'message' => 'required|string',
        ]);

        $birth = new BirthCollaborator();
        $birth->birth = $request->birth;
        $birth->sex = $request->sex;
        $birth->message = $request->message;
        $birth->vw_users_usuario = $request->user;
        $birth->templante_collaborator_id = 6;
        $birth->save();

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>6,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Nacimiento => ".$birth->id,
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        $publication_data = [
            'link' => url('collaborators?section=pane-births'),
            'title' => "Nacimientos - Se agrego una nueva publicación",
        ];

        //Se envia la notificación del comunicado
        $all_users="all";
        $this->GeneralFunctionsRepository->preparingNotificationCommunique($publication_data,null,null,$all_users);


        return ['success'=> 1, 'data'=>$birth];

    }

    public function birthEdit(Request $request){

        //Se validan los datos que llegan
        $validatedData=$request->validate([
            'birth' => 'required|date',
            'user' => 'required|string|max:255',
            'sex' => 'required|in:Femenino,Masculino|string|max:50',
            'message' => 'required|string',
        ]);

        $birth = BirthCollaborator::where('id',$request->birth_id)->first();
        $birth->birth = $request->birth;
        $birth->sex = $request->sex;
        $birth->message = $request->message;
        $birth->vw_users_usuario = $request->user;
        $birth->templante_collaborator_id = 6;
        $birth->save();

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>6,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Nacimiento => ".$birth->id,
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return ['success'=> 1, 'data'=>$birth];

    }

    public function birthDelete($_id){

        if(isset($_id) && $_id > 0){
            $birth = BirthCollaborator::where('id',$_id)->first();

            if($birth != null){
                $birth->delete();

                /* Start - Auditoria */
                $params=[
                    "sub_seccion_id" =>6,
                    "ip" => $this->GeneralFunctionsRepository->getIp(),
                    "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
                    "comment" => "Nacimiento => ".$_id,
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
    /***************************************** END - Nacimientos ***************************************** */



    /***************************************** START - CONDOLENCIAS ***************************************** */

    public function condolenceShow(){

        $temp_request = new Request();
        $temp_request->setMethod('GET');
        $temp_request->query->add(['limit'=>10]);
        $list = $this->condolenceListAdmin($temp_request);
        $submodule="condolences";
        $compact = compact('list','submodule');

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>5,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Listado admin",
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return view('admin.collaborators.condolences.list')->with($compact);

    }

    public function condolenceList(Request $request){

        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '10';
        $search = isset($request->search) ? json_decode(json_encode($request->search),false) : '';
        $condolences = [];

        if(isset($search->isSearch) && strlen($search->text) > 0){
            $condolences = CondolenceCollaborator::with(['user','templanteCollaborator'])
            ->whereRaw("(condolence_date like '%$search->text%'
                or accompanies like '%$search->text%'
                or condolence like '%$search->text%'
                or collaborator like '%$search->text%')")
            ->orderBy('created_at',$order_by)->Paginate($limit);
        }else{

            $condolences = CondolenceCollaborator::with(['user','templanteCollaborator'])->orderBy('created_at',$order_by)
            ->Paginate($limit);
        }

        $condolences->setPath('/collaborators/condolences');

        return $condolences;

    }
    public function condolenceListAdmin(Request $request){

        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '10';
        $search = isset($request->search) ? json_decode(json_encode($request->search),false) : '';
        $condolences = [];

        if(isset($search->isSearch) && strlen($search->text) > 0){
            $condolences = CondolenceCollaborator::with(['user','templanteCollaborator'])
            ->whereRaw("(condolence_date like '%$search->text%'
                or accompanies like '%$search->text%'
                or condolence like '%$search->text%'
                or collaborator like '%$search->text%')")
            ->orderBy('expiration_date',$order_by)->Paginate($limit);
        }else{

            $condolences = CondolenceCollaborator::with(['user','templanteCollaborator'])->orderBy('expiration_date',$order_by)
            ->Paginate($limit);
        }

        $condolences->setPath('/admin/collaborators/condolence-list-admin');

        return $condolences;

    }

    public function condolenceSave(Request $request){
         //Se validan los datos que llegan
         $validatedData=$request->validate([
            'condolence_date' => 'required|date',
            'locations' => 'required|array',
            'expiration_date' => 'required|date',
            'accompanies' => 'required|string|max:255',
            'condolence' => 'required|string|max:255',
            'collaborator' => 'required|string|max:255',
        ]);

        $condolence = new CondolenceCollaborator();
        $condolence->condolence_date = $request->condolence_date;
        $condolence->expiration_date = $request->expiration_date;
        $condolence->accompanies = $request->accompanies;
        $condolence->condolence = $request->condolence;
        $condolence->collaborator = $request->collaborator;
        $condolence->templante_collaborator_id = 7;
        $condolence->save();

        //Se almacenan los id de las ubicaciones creadas
        if($condolence != null){

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
                $bucket_location->origin_record_id = $condolence->id;
                $bucket_location->locations_id = $subgroup->vw_locations_id;
                $bucket_location->subgroups_id = $subgroup->subgroups_id;
                $bucket_location->sub_seccion_id = 5;
                $bucket_location->save();
            }

        }


        $this->sendEmailsCondolences($condolence->id,5);

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>5,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Registro => ".$condolence->id,
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return ['success'=> 1, 'data'=>$condolence];
    }

    public function condolenceEdit(Request $request){
        //Se validan los datos que llegan
        $validatedData=$request->validate([
            'locations' => 'required|array',
           'condolence_date' => 'required|date',
           'expiration_date' => 'required|date',
           'accompanies' => 'required|string|max:255',
           'condolence' => 'required|string|max:255',
           'collaborator' => 'required|string|max:255',
       ]);

       $condolence = CondolenceCollaborator::where('id',$request->condolence_id)->first();
       $condolence->condolence_date = $request->condolence_date;
       $condolence->expiration_date = $request->expiration_date;
       $condolence->accompanies = $request->accompanies;
       $condolence->condolence = $request->condolence;
       $condolence->collaborator = $request->collaborator;
       $condolence->templante_collaborator_id = 7;
       $condolence->save();

       //Se eliminan las ubicaciones actuales y se almacenan los id de las ubicaciones creadas
        if($condolence != null){

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
                $bucket_location->origin_record_id = $condolence->id;
                $bucket_location->locations_id = $subgroup->vw_locations_id;
                $bucket_location->subgroups_id = $subgroup->subgroups_id;
                $bucket_location->sub_seccion_id = 5;
                $bucket_location->save();
            }
        }
       //$this->sendEmailsCondolences($condolence->id,5);

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>5,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Registro => ".$condolence->id,
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

       return ['success'=> 1, 'data'=>$condolence];
    }

   public function condolenceDelete($_id){

        if(isset($_id) && $_id > 0){
            $condolence = CondolenceCollaborator::where('id',$_id)->first();

            if($condolence != null){
                $condolence->delete();

                /* Start - Auditoria */
                $params=[
                    "sub_seccion_id" =>5,
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

    /***************************************** END - CONDOLENCIAS ***************************************** */



    /***************************************** START - ASCENSOS ***************************************** */

    public function promotionShow(){

        $temp_request = new Request();
        $temp_request->setMethod('GET');
        $temp_request->query->add(['limit'=>10]);
        $list = $this->promotionListAdmin($temp_request);
        $submodule="promotions";
        $compact = compact('list','submodule');

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>7,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Listado Admin",
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return view('admin.collaborators.promotions.list')->with($compact);

    }

    public function promotionList(Request $request){

        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '10';
        $search = isset($request->search) ? json_decode(json_encode($request->search),false) : '';
        $promotions = [];

        if(isset($search->isSearch) && strlen($search->text) > 0){
            $promotions = Promotion::with(['logo_location'])
            ->whereRaw("(message like '%$search->text%'
                or new_position_company like '%$search->text%'
                or created_at like '%$search->text%')")
            ->orderBy('created_at',$order_by)->Paginate($limit);
        }else{

            $promotions = Promotion::with(['user.locations','user_top.locations'])->orderBy('created_at',$order_by)
            ->Paginate($limit);
        }

        $promotions->setPath('/collaborators/promotions');
        //dd($promotions);
        return $promotions;

    }

    public function promotionListAdmin(Request $request){

        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $limit = (isset($request->limit) && $request->limit > 0) ? $request->limit : '10';
        $search = isset($request->search) ? json_decode(json_encode($request->search),false) : '';
        $promotions = [];

        if(isset($search->isSearch) && strlen($search->text) > 0){
            $promotions = Promotion::whereRaw("(message like '%$search->text%'
                or new_position_company like '%$search->text%'
                or complete_name like '%$search->text%'
                or created_at like '%$search->text%')")
            ->orderBy('expiration_date',$order_by)->Paginate($limit);
        }else{

            $promotions = Promotion::orderBy('expiration_date',$order_by)
            ->Paginate($limit);
        }

        $promotions->setPath('/admin/collaborators/promotion-list-admin');

        return $promotions;

    }

    public function promotionSave(Request $request){

        //Se validan los datos que llegan
        $validatedData=$request->validate([
            'vw_users_usuario' => 'required|string',
            'vw_users_usuario_top' => 'required|string',
            'new_position_company' => 'required|string',
            'photo' => 'required|image|mimes:jpg,png|max:5000',
            'message' => 'required|string',
            'expiration_date' => 'required|date',
        ]);

        $folder_path_storage = 'storage/promotions/';
		if(!file_exists($folder_path_storage)) {
			mkdir($folder_path_storage, 0777,true);
		}

        // Se almacena la imagen
        $file = $request->file('photo');
        $extension = $file->getClientOriginalExtension();
        $fileName = time().'_promotions.';
        $fileName = $fileName.$extension;
        $path = '/promotions/'.$fileName;
        \Storage::disk('public')->put($path,\File::get($file));

        $user = User::where('full_name',$request->vw_users_usuario)
                        ->where('status','ALTA')
                        ->first();
        $user_top = User::where('full_name',$request->vw_users_usuario_top)
                        ->where('status','ALTA')
                        ->first();

        $promotion = new Promotion();
        /* $promotion->vw_users_usuario = $request->vw_users_usuario; */
        $promotion->user_name = $request->vw_users_usuario;
        /* $promotion->vw_users_usuario_top = $request->vw_users_usuario_top; */
        $promotion->user_top_name = $user_top->full_name;
        $promotion->new_position_company = $request->new_position_company;
        $promotion->photo = 'storage'.$path;
        $promotion->message = $request->message;
        $promotion->expiration_date = $request->expiration_date;
        $promotion->location = $user->location;
        $promotion->save();

        /* $this->sendEmails($promotion->id,7); */

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>7,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Registro => ".$promotion->id,
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        $publication_data = [
            'link' => url('collaborators?section=pane-promotions'),
            'title' => "Ascensos - Se agrego una nueva publicación",
        ];

        //Se envia la notificación del comunicado
        $all_users="all";
        $this->GeneralFunctionsRepository->preparingNotificationCommunique($publication_data,null,null,$all_users);

        return ['success'=> 1, 'data'=>$promotion];
    }

    public function promotionEdit(Request $request){
        //Se validan los datos que llegan
            $validatedData=$request->validate([
                'promotion_id' => 'required|numeric|min:1',
                'vw_users_usuario' => 'required|string',
                'vw_users_usuario_top' => 'required|string',
                'new_position_company' => 'required|string',
                'message' => 'required|string',
                'expiration_date' => 'required|date',
        ]);

        if($request->file('photo') != null){
            $validatePhoto = $request->validate([
                'photo' => 'required|image|mimes:jpg,png|max:5000',
            ]);
            // Se almacena la imagen
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'_promotions.';
            $fileName = $fileName.$extension;
            $path = '/promotions/'.$fileName;
            \Storage::disk('public')->put($path,\File::get($file));
            $this->GeneralFunctionsRepository->uploadImgServerGrupoDMI($request->file('photo'),$fileName);

        }

        $user = User::where('full_name',$request->vw_users_usuario)
                        ->where('status','ALTA')
                        ->first();

        $promotion = Promotion::where('id',$request->promotion_id)->first();
        $promotion->user_name = $request->vw_users_usuario;
        $promotion->user_top_name = $request->vw_users_usuario_top;
        $promotion->new_position_company = $request->new_position_company;
        $promotion->message = $request->message;
        $promotion->expiration_date = $request->expiration_date;
        $promotion->location = $user->location;

        if($request->file('photo') != null){
            $promotion->photo = 'storage'.$path;
        }

        $promotion->save();
        /* $this->sendEmails($promotion->id,7); */

        /* Start - Auditoria */
        $params=[
            "sub_seccion_id" =>7,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Registro => ".$promotion->id,
        ];
        $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return ['success'=> 1, 'data'=>$promotion];
    }

    public function promotionDelete($_id){

        if(isset($_id) && $_id > 0){
            $promotion = Promotion::where('id',$_id)->first();

            if($promotion != null){
                $promotion->delete();

                /* Start - Auditoria */
                $params=[
                    "sub_seccion_id" =>7,
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

    /***************************************** END - ASCENSOS ***************************************** */

    /* Start - Envio de correos de promociones */
    public function sendEmails($_record_id,$_sub_seccion_id){

        $record = Promotion::with(['user.locations','user_top.locations'])->find($_record_id);

        if($record != null){
            $users_email = $this->GeneralFunctionsRepository->getUsersByLocation($_record_id,$_sub_seccion_id,"all");
            $cover_img_name = $this->GeneralFunctionsRepository->getCoverNameImg($record->photo);

            $mail_data = [
                'sub_seccion' => "promotions",
                'recipient_emails' =>$users_email,
                'subject' => "Aviso - Cambio Orginizacional",
                'link' => url('collaborators?section=pane-promotions'),
                'title' => "Ascenso Interno",
                'user' => $record->user->full_name,
                'user_top' => $record->user_top->full_name,
                'new_position_company' => $record->new_position_company,
                'updated_at' => $record->updated_at,

            ];

            $this->GeneralFunctionsRepository->sendEmails($mail_data);

            //Se envia la notificación del comunicado
            $all_users="all";
            $mail_data['title'] = "Ascenso Interno - Cambio Orginizacional";
            $this->GeneralFunctionsRepository->preparingNotificationCommunique($mail_data,$_record_id,$_sub_seccion_id,$all_users);
        }

    }
    /* End - Envio de correos de promociones */

    public function sendEmailsCondolences($_record_id,$_sub_seccion_id){

        $record = CondolenceCollaborator::find($_record_id);

        if($record != null){
            $users_email = $this->GeneralFunctionsRepository->getUsersByLocation($_record_id,$_sub_seccion_id);
            $cover_img_name = $this->GeneralFunctionsRepository->getCoverNameImg($record->photo);

            $mail_data = [
                'sub_seccion' => "condolences",
                'recipient_emails' =>$users_email,
                'subject' => "Condolencias - Nuestras Condolencias $record->accompanies  (Comunicado)",
                'link' => url('collaborators').'?section=pane-condolences',
                'accompanies' => $record->accompanies,
                'condolence' => $record->condolence,
                'collaborator' => $record->collaborator,
                'condolence_date' => $record->condolence_date,

            ];

            $this->GeneralFunctionsRepository->sendEmails($mail_data);

            //Se envia la notificación del comunicado
            $mail_data['title']= $mail_data['subject'];
            $this->GeneralFunctionsRepository->preparingNotificationCommunique($mail_data,$_record_id,$_sub_seccion_id);
        }

    }

}
