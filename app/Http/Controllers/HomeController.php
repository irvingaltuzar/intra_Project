<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Project_Dmi;
use App\Models\Project_Dmi_Item;
use App\Models\ConmmemorativeDate;
use App\Models\InternalPosting;
use App\Models\Poll;
use App\Models\AreaNotice;
use App\Models\Policy;
use App\Models\FoundationCapsule;
use Carbon\Carbon;
use App\Models\Communique;
use App\Repositories\GeneralFunctionsRepository;
use GuzzleHttp\Client;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->GeneralFunctionsRepository = new GeneralFunctionsRepository();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $publications = $this->getPublicationsAll();
        $temp_publications=[];
        $modules=['communique','conmmemorative_date','internal_posting','poll','area_notice','policy','foundation_capsules'];
        $publications_limit=[4,1,1,1,1,1,1];
        $have_publications= 0;
        $communiques=['merge'];
        $cont=0;

        foreach($modules as $key_module => $module){

            if(count($publications[$module]) > 0){
                if(isset($temp_publications[$module]) == ""){
                    $temp_publications[$module]=collect();
                }

                for ($i=0; $i < $publications_limit[$key_module]; $i++) {
                    if(isset($publications[$module][$i])){
                        $temp_publications[$module][] = $publications[$module][$i];
                        unset($publications[$module][$i]);
                    }else{
                        break;
                    }

                }
            }else{
                $temp_publications[$module] = collect();
            }

        }

        $communiques=[];
        $communiques= $temp_publications['communique']->concat($communiques);
        $communiques= $communiques->concat($temp_publications['conmmemorative_date']);
        $communiques= $communiques->concat($temp_publications['internal_posting']);
        $communiques= $communiques->concat($temp_publications['poll']);
        $communiques= $communiques->concat($temp_publications['area_notice']);
        $communiques= $communiques->concat($temp_publications['policy']);
        $communiques= $communiques->concat($temp_publications['foundation_capsules']);

        if(sizeof($communiques) >= 10){
            // Ya no se agrega nada
        }else{
            // Se obtiene el numero de comunicados agregados
            $missing = 10 - sizeof($communiques);

            if( $missing > 0){

                $add_item=0;
                $publication_less=0;
                for ($i=0; $i < $missing; $i++) {

                    $publication_less=0;

                    for ($key_module=0; $key_module < sizeof($modules) ; $key_module++) {
                        $endpoint = $modules[$key_module];

                        // Se reordenan las posisiciones de los items
                        $aux_item_module=[];
                        foreach($publications[$endpoint] as $item){
                            $aux_item_module[]=$item;
                        }
                        $publications[$endpoint]=$aux_item_module;


                        if(count($publications[$endpoint]) > 0){
                            // Se agrega otro item a temp_publications
                            $temp_publications[$endpoint][] = $publications[$endpoint][0];
                            unset($publications[$endpoint][0]);
                            $add_item++;
                        }else{
                            $publication_less++;

                            if($publication_less == sizeof($modules)){
                                break;
                            }
                        }


                        if($add_item == $missing){
                            break;
                        }


                    }

                    if($add_item == $missing || $publication_less == sizeof($modules)){
                        break;
                    }

                }

            }else{
                // Ya no se agergan comunicados y se envia el array
            }


        }



        // Se limpia y se vuelve a cargar de nuevo con cada apartado
        $communiques=[];
        $communiques= $temp_publications['communique']->concat($communiques);
        $communiques= $communiques->concat($temp_publications['conmmemorative_date']);
        $communiques= $communiques->concat($temp_publications['internal_posting']);
        $communiques= $communiques->concat($temp_publications['poll']);
        $communiques= $communiques->concat($temp_publications['area_notice']);
        $communiques= $communiques->concat($temp_publications['policy']);
        $communiques= $communiques->concat($temp_publications['foundation_capsules']);

        /* Start - Auditoria */
            $params=[
                "sub_seccion_id" =>23,
                "ip" => $this->GeneralFunctionsRepository->getIp(),
                "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
                "comment" => "Inicio",
            ];
            $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return view('home',["communiques"=>$communiques]);
    }



    function getPublicationsAll(){
        $order_by = isset($request->order_by) ? $request->order_by : 'desc';
        $hoy = Carbon::today();
        $publications = [];
        $start_sub_week = Carbon::now()->subWeek()->startOfWeek()->format("Y-m-d");


        $publications['communique'] = Communique::join('bucket_locations','communiques.id','bucket_locations.origin_record_id')
                                        ->where('bucket_locations.subgroups_id',session('location_user'))
                                        ->whereNull('communiques.deleted_at')
                                        ->whereNull('bucket_locations.deleted_at')
                                        ->whereIn('bucket_locations.sub_seccion_id',[2,3,4])
                                        ->whereDate("communiques.created_at",">=",$start_sub_week)
                                        ->whereDate('communiques.expiration_date', '>=', $hoy)
                                        ->orderBy('communiques.created_at','desc')
                                        ->select('communiques.*')
                                        ->take(10)
                                        ->get();

        $publications['conmmemorative_date'] = ConmmemorativeDate::join('bucket_locations','conmmemorative_date.id','bucket_locations.origin_record_id')
                                        ->where('bucket_locations.subgroups_id',session('location_user'))
                                        ->whereNull('conmmemorative_date.deleted_at')
                                        ->whereNull('bucket_locations.deleted_at')
                                        ->where('bucket_locations.sub_seccion_id',8)
                                        ->whereDate("conmmemorative_date.created_at",">=",$start_sub_week)
                                        ->whereDate('conmmemorative_date.expiration_date', '>=', $hoy)
                                        ->orderBy('conmmemorative_date.created_at','desc')
                                        ->select('conmmemorative_date.*')
                                        ->take(10)
                                        ->get();


        $publications['internal_posting'] = InternalPosting::join('bucket_locations','internal_postings.id','bucket_locations.origin_record_id')
                                        ->where('bucket_locations.subgroups_id',session('location_user'))
                                        ->whereNull('internal_postings.deleted_at')
                                        ->whereNull('bucket_locations.deleted_at')
                                        ->where('bucket_locations.sub_seccion_id',9)
                                        ->whereDate("internal_postings.created_at",">=",$start_sub_week)
                                        ->whereDate('internal_postings.expiration_date', '>=', $hoy)
                                        ->orderBy('internal_postings.created_at','desc')
                                        ->select('internal_postings.*')
                                        ->take(10)
                                        ->get();


        $publications['poll'] = Poll::join('bucket_locations','polls.id','bucket_locations.origin_record_id')
                                        ->where('bucket_locations.subgroups_id',session('location_user'))
                                        ->whereNull('polls.deleted_at')
                                        ->whereNull('bucket_locations.deleted_at')
                                        ->where('bucket_locations.sub_seccion_id',10)
                                        ->whereDate("polls.created_at",">=",$start_sub_week)
                                        ->whereDate('polls.expiration_date', '>=', $hoy)
                                        ->orderBy('polls.created_at','desc')
                                        ->select('polls.*')
                                        ->take(10)
                                        ->get();


        $publications['area_notice'] = AreaNotice::join('bucket_locations','area_notices.id','bucket_locations.origin_record_id')
                                        ->where('bucket_locations.subgroups_id',session('location_user'))
                                        ->whereNull('area_notices.deleted_at')
                                        ->whereNull('bucket_locations.deleted_at')
                                        ->where('bucket_locations.sub_seccion_id',11)
                                        ->whereDate("area_notices.created_at",">=",$start_sub_week)
                                        ->whereDate('area_notices.expiration_date', '>=', $hoy)
                                        ->orderBy('area_notices.created_at','desc')
                                        ->select('area_notices.*')
                                        ->take(10)
                                        ->get();


        $publications['policy'] = Policy::join('bucket_locations','policies.id','bucket_locations.origin_record_id')
                                        ->where('bucket_locations.subgroups_id',session('location_user'))
                                        ->whereNull('policies.deleted_at')
                                        ->whereNull('bucket_locations.deleted_at')
                                        ->where('bucket_locations.sub_seccion_id',12)
                                        ->whereDate("policies.created_at",">=",$start_sub_week)
                                        ->whereDate('policies.expiration_date', '>=', $hoy)
                                        ->orderBy('policies.created_at','desc')
                                        ->select('policies.*')
                                        ->take(10)
                                        ->get();


        $publications['foundation_capsules'] = FoundationCapsule::join('bucket_locations','foundation_capsule.id','bucket_locations.origin_record_id')
                                        ->where('bucket_locations.subgroups_id',session('location_user'))
                                        ->whereNull('foundation_capsule.deleted_at')
                                        ->whereNull('bucket_locations.deleted_at')
                                        ->where('bucket_locations.sub_seccion_id',22)
                                        ->whereDate("foundation_capsule.created_at",">=",$start_sub_week)
                                        ->whereDate('foundation_capsule.expiration_date', '>=', $hoy)
                                        ->orderBy('foundation_capsule.created_at','desc')
                                        ->select('foundation_capsule.*')
                                        ->take(10)
                                        ->get();

        return $publications;
    }

    

    public function about_us(){

        /* Start - Auditoria */
            $params=[
                "sub_seccion_id" =>25,
                "ip" => $this->GeneralFunctionsRepository->getIp(),
                "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
                "comment" => "Quienes Somos",
            ];
            $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        $projects = Project_Dmi::leftjoin('vw_locations', 'vw_locations.id','=','project_dmi.vw_locations_id')
                    ->select('project_dmi.*','vw_locations.photo')
                    ->orderBy('project_dmi.creation_date','asc')
                    ->orderBy('project_dmi.id','asc')
                    ->get();

        $data = compact('projects');
        return view('quienes_somos',$data);
    }

    public function show_project($id){

        $project = Project_Dmi::leftjoin('files', 'files.id','=','project_dmi.files_id')
                    ->where('project_dmi.id',$id)
                    ->select('project_dmi.*','files.file','files.extension')
                    ->first();
        $temp_items = Project_Dmi_Item::leftjoin('files','files.id','=','project_dmi_item.files_id')
                        ->where('project_dmi_id',$project->id)
                        ->select('project_dmi_item.*','files.file','files.type_file')
                        ->get();
        $items = $temp_items->groupBy('section');
        $data = compact('project','items');
        return view('quienes_somos.project_dmi',$data);
    }

    public function growth(){
        return view('desarrollo');
    }
    public function noticePrivacy(){
        return view('aviso_privacidad');
    }
    /* public function fundacion(){

        return view('fundacion');
    } */
    public function perfil(){
        /* Start - Auditoria */
            $params=[
                "sub_seccion_id" =>36,
                "ip" => $this->GeneralFunctionsRepository->getIp(),
                "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
                "comment" => "Mi Perfil",
            ];
            $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        $days=["Dom","Lun","Mar","Mie","Jue","Vie","Sáb"];

        $horario=User::with(['horary'])->where('usuario',Auth::user()->usuario)->where('status','alta')->first();
        $jefe=User::where('plaza_id',Auth::user()->top_plaza_id)->where('status','alta')->first();

        return view('perfil',['user'=>Auth::user(), 'jefe' =>$jefe, 'days' =>$days, "horario"=> $horario->horary]);
    }

    public function directory(Request $request){

        return view('directorio');
    }

    public function get_list_directory(Request $request){

        $order_by = isset($request->order_by) ? $request->order_by : 'asc';
        $per_page = isset($request->per_page) ? $request->per_page : '50';
        $search = $request->search;
        $users=[];

        if($search->isSearch){
            $users =User::where('status','alta')->whereRaw("(email <> '' OR extension <> '')")
            ->whereRaw("(name like '%$search->text%'
            or last_name like '%$search->text%'
            or deparment like '%$search->text%'
            or position_company_full like '%$search->text%'
            or location like '%$search->text%'
            or email like '%$search->text%'
            or extension like '%$search->text%')")
            ->orderBy('name',$order_by)->Paginate($per_page);
        }else{

            $users =User::where('status','alta')->whereRaw("email <> '' OR extension <> ''")->orderBy('name',$order_by)->Paginate($per_page);
        }


        return response()->json(['success'=>1, 'data' => $users]);
    }

}
