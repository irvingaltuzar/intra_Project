<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrganizationChartController;
use App\Http\Controllers\CommunicationController;
use App\Http\Controllers\CollaboratorController;
use App\Http\Controllers\BenefitController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\TestEladioController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SitemapsController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TempSessionController;
use App\Http\Controllers\InternalPostingController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\AreaNoticeController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\PrestacionController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\FoundationCapsuleController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\MediaFunctionsController;
use App\Http\Controllers\ProjectBoardController;
use App\Repositories\GeneralFunctionsRepository;
use App\Models\AreaNotice;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('test/', function () {
    $users = User::whereHas('locations', function ($q) {
        $q->whereIn('id', [1, 2, 3]);
    })
    ->where('status', 'Alta')
    ->where('email', '!=', '')
    ->get()
    ->groupBy('location');

    return response()->json($users);
});

Route::prefix('test')->group(function(){
    Route::get('/eladio',[TestEladioController::class,'index']);
    Route::get('/upload-image-serverdmi',function(){
        return view('test.uploadImage');
    });
    Route::post('/save-upload-image-serverdmi',[TestEladioController::class,'saveUploadImageServerDMI']);
    Route::get('/send-mail',[TestEladioController::class,'sendMail']);
});

Route::get('email-test', [TestController::class, 'test']);

/* Start - Rutas publicas */
Route::get('view-email',function (){
    return new \App\Mail\SendEmails(['subject' => 'asunto del correo']);
});

Route::get('wallpaper', [PublicController::class, 'wallpaperWelcome']);
Route::post("login-user-ad",[LoginController::class,'loginAD']);
/* Route::get('redirect-admin',[LoginController::class,'logoutAndRedirectAdmin'])->name('redirectAdmin'); */

/* End - Rutas publicas */

Auth::routes();
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::get('logout',[LoginController::class,'logout'])->name('logout');
Route::get('expire-session',[TempSessionController::class,'expire_session_inactivity']);
Route::get('expire-session-from-alfa',[TempSessionController::class,'expire_session_inactivity_alfa']);


Route::group(['middleware' => ['auth']],function(){
/* ============================================================== */

    /* **************** Rutas Genericas **************** */
    Route::get('sistemas-administrativos',[TempSessionController::class,'redirectAlfa'])->name('sistemasAdministrativos');
    Route::get('multimedia/{submodulo}/{id}',[MediaFunctionsController::class,'multimediaVideo']);
    Route::get('get-last-check',[GeneralFunctionsRepository::class,'getLastCkeckFromSistemasAdministrativos']);
    /* **************** Rutas Genericas **************** */

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/fundacion', [FoundationCapsuleController::class, 'fundacion'])->name('fundacion');
    Route::get('/foundation-capsule', [FoundationCapsuleController::class, 'publicFoundationCapsuleList']);
    Route::get('/perfil', [HomeController::class, 'perfil'])->name('perfil');
    Route::get('/desarrollo', [HomeController::class, 'growth'])->name('desarrollo');
    Route::get('/aviso_privacidad', [HomeController::class, 'noticePrivacy'])->name('noticePrivacy');
    Route::get('/beneficios_prestaciones', [BenefitController::class, 'benefit'])->name('benefit');
    Route::get('/beneficio_prestacion/{id}', [BenefitController::class, 'show'])->name('benefit.show');
    Route::prefix('/benefits')->group(function(){
        Route::get('benefits',[BenefitController::class, 'benefitsListPublic']);
        Route::get('prestaciones',[PrestacionController::class, 'prestacionListPublic']);
    });

    Route::get('/quienes_somos', [HomeController::class, 'about_us'])->name('quienesSomos');
    Route::get('project/{id}',[HomeController::class, 'show_project']);

    Route::get('/sitemaps',[SitemapsController::class, 'show'])->name('sitemaps');

    Route::get('/directorio', [HomeController::class, 'directory'])->name('directory');
    Route::get('/get_list_directory', [HomeController::class, 'get_list_directory']);

    Route::prefix('/dmi_comunicados')->group(function(){
        Route::get('/', [CommunicationController::class, 'index'])->name('communication');
        Route::get('/communique/{id}', [CommunicationController::class, 'show']);
        Route::get('/profile/{id}', [CommunicationController::class, 'showCommunique']);
        Route::get('/council', [CommunicationController::class, 'communiques_council']);
        Route::get('/organizational', [CommunicationController::class, 'communiques_organizational']);
        Route::get('/institutional', [CommunicationController::class, 'communiques_institutional']);
    });

    Route::prefix('/news')->group(function(){
        Route::get('/', [NewsController::class,'index'])->name('news');
        /* Route::get('/conmmemorative-date',[NewsController::class,'conmmemorativeDateList']); */
        Route::get('/internal-posting',[InternalPostingController::class,'internalPostingList']);
        Route::get('/poll',[PollController::class,'publicPollList']);
        Route::get('/area-notice',[AreaNoticeController::class,'publicAreaNoticeList']);
        Route::get('/policy',[PolicyController::class,'publicPolicyList']);

    });

    Route::prefix('/events')->group(function(){
        Route::get('/',[EventController::class,'index'])->name('events');
        Route::get('/list',[EventController::class,'eventList']);
    });

    Route::prefix('trainings')->group(function (){
        Route::get('/',[TrainingController::class,'index'])->name('trainings');
        Route::get('/list',[TrainingController::class,'trainingList']);
    });

    Route::prefix('/organigrama')->group(function(){
        Route::get('/',[OrganizationChartController::class,'organization_chart'])->name('organigrama');
        Route::get('/{type}',[OrganizationChartController::class,'show']);
        Route::get('/frame/{type}',[OrganizationChartController::class,'frameOrganigramaOld']);
    });

    Route::prefix('/blog')->group(function(){
        Route::get('/',[BlogController::class,'index'])->name('blog');
        Route::get('/notes/{id}',[BlogController::class,'publication']);
        Route::get('/notes/list',[BlogController::class,'listPublications']);
        Route::get('/notes/publications/1',[BlogController::class,'listPublications']);
    });

    /* Colaboradores (Naciemientos y Condolencias)  */
    Route::prefix('/collaborators')->group(function (){
        Route::get('/',[CollaboratorController::class, 'index'])->name('collaborators');
        Route::get('/births',[CollaboratorController::class,'birthList']);
        Route::get('/condolences',[CollaboratorController::class,'condolenceList']);
        Route::get('/promotions',[CollaboratorController::class,'promotionList']);
    });

    /* Publicationes y comentarios */
    Route::prefix('/publications')->group(function(){
        Route::get('/list',[PublicationController::class,'listPublications']);
        Route::post('/add',[PublicationController::class,'addPublication']);
        Route::get('/show/{id}',[PublicationController::class,'show']);
        Route::get('/list/comments',[CommentController::class,'get_list_comments']);
        Route::post('/add/comments',[CommentController::class,'add_comments']);
    });

    /* Project Board */
    Route::get('/project-board', [ProjectBoardController::class, 'showBoard'])->name('show_board');


/* ============================================================== */
});




/*************************************** Start - Admin  ****************************************/
Route::group(['middleware' => ['admin']],function(){
    Route::prefix('/admin')->group(function(){

        Route::get('/',[AdminController::class,'index'])->name('admin');

        /* =================== Comunicados =================== */
        Route::prefix('/communiques')->group(function(){
            Route::get('/list-council',[CommunicationController::class,'listCouncil'])->name('admin.communiques.council');
            Route::get('/list-admin-council',[CommunicationController::class,'communiques_council_admin']);
            Route::get('/list-admin-organizational',[CommunicationController::class,'communiques_organizational_admin']);
            Route::get('/list-admin-institutional',[CommunicationController::class,'communiques_institutional_admin']);
            Route::post('/get-list-user',[CommunicationController::class,'getListUser']);
            Route::get('/list-organizational',[CommunicationController::class,'listOrganizational'])->name('admin.communiques.organizational');
            Route::get('/list-institutional',[CommunicationController::class,'listInstitutional'])->name('admin.communiques.institutional');
            Route::post('/save',[CommunicationController::class,'save']);
            Route::post('/edit',[CommunicationController::class,'edit']);
            Route::get('/delete/{id}',[CommunicationController::class,'delete']);
            Route::get('/send-reminder/{id}',[CommunicationController::class,'sendReminder']);
            Route::get('/delete-file/{file_id}/{communique_id}',[CommunicationController::class,'deleteFile']);
        });

        /* =================== Nacimientos, Promociones y Condolencias =================== */
        Route::prefix('/collaborators')->group(function(){
            Route::get('/birth-list',[CollaboratorController::class,'birthShow'])->name('admin.collaborators.births');
            Route::get('/birth-list-admin',[CollaboratorController::class,'birthListAdmin']);
            Route::post('/birth-save',[CollaboratorController::class,'birthSave']);
            Route::post('/birth-edit',[CollaboratorController::class,'birthEdit']);
            Route::get('/birth-delete/{id}',[CollaboratorController::class,'birthDelete']);

            Route::get('/condolence-list',[CollaboratorController::class,'condolenceShow'])->name('admin.collaborators.condolences');
            Route::get('/condolence-list-admin',[CollaboratorController::class,'condolenceListAdmin']);
            Route::post('/condolence-save',[CollaboratorController::class,'condolenceSave']);
            Route::post('/condolence-edit',[CollaboratorController::class,'condolenceEdit']);
            Route::get('/condolence-delete/{id}',[CollaboratorController::class,'condolenceDelete']);

            Route::get('/promotion-list',[CollaboratorController::class,'promotionShow'])->name('admin.collaborators.promotions');
            Route::get('/promotion-list-admin',[CollaboratorController::class,'promotionListAdmin']);
            Route::post('/promotion-save',[CollaboratorController::class,'promotionSave']);
            Route::post('/promotion-edit',[CollaboratorController::class,'promotionEdit']);
            Route::get('/promotion-delete/{id}',[CollaboratorController::class,'promotionDelete']);


        });

        /* =================== Fechas Conmemorativas, Posteo interno, Encuestas, Avisos de Área y Políticas =================== */
        Route::prefix('/news')->group(function(){
            Route::get('/conmmemorative_date-list',[NewsController::class,'conmemmorativeDateShow'])->name('admin.news.conmmemorative_date');
            Route::get('/conmmemorative_date-list-admin',[NewsController::class,'conmmemorativeDateList']);
            Route::post('/conmmemorative_date-save',[NewsController::class,'conmemmorativeDateSave']);
            Route::post('/conmmemorative_date-edit',[NewsController::class,'conmemmorativeDateEdit']);
            Route::get('/conmmemorative_date-delete/{id}',[NewsController::class,'conmemmorativeDateDelete']);

            Route::get('/internal-posting-list',[InternalPostingController::class,'internalPostingShow'])->name('admin.news.internal_posting');
            Route::get('/internal-posting-list-admin',[InternalPostingController::class,'internalPostingList']);
            Route::post('/internal-posting-save',[InternalPostingController::class,'internalPostingSave']);
            Route::post('/internal-posting-edit',[InternalPostingController::class,'internalPostingEdit']);
            Route::get('/internal-posting-delete/{id}',[InternalPostingController::class,'internalPostingDelete']);
            Route::get('/internal-posting-delete/send-reminder/{id}',[InternalPostingController::class,'sendReminder']);
            Route::get('/internal-posting-delete-file/{file_id}/{internal_posting_id}',[InternalPostingController::class,'deleteFile']);

            Route::get('/poll',[PollController::class,'pollShow'])->name('admin.news.poll');
            Route::get('/poll-list',[PollController::class,'adminPollList']);
            Route::post('/poll-save',[PollController::class,'pollSave']);
            Route::post('/poll-edit',[PollController::class,'pollEdit']);
            Route::get('/poll-delete/{id}',[PollController::class,'pollDelete']);

            Route::get('/area-notice',[AreaNoticeController::class,'areaNoticeShow'])->name('admin.news.area_notice');
            Route::get('/area-notice-list',[AreaNoticeController::class,'adminAreaNoticeList']);
            Route::post('/area-notice-save',[AreaNoticeController::class,'areaNoticeSave']);
            Route::post('/area-notice-edit',[AreaNoticeController::class,'areaNoticeEdit']);
            Route::get('/area-notice-delete/{id}',[AreaNoticeController::class,'areaNoticeDelete']);
            Route::get('/area-notice/send-reminder/{id}',[AreaNoticeController::class,'sendReminder']);
            Route::get('/area-notice-file/{file_id}/{area_notice_id}',[AreaNoticeController::class,'deleteFile']);


            Route::get('/policy',[PolicyController::class,'policyShow'])->name('admin.news.policy');
            Route::get('/policy-list',[PolicyController::class,'adminPolicyList']);
            Route::post('/policy-save',[PolicyController::class,'policySave']);
            Route::post('/policy-edit',[PolicyController::class,'policyEdit']);
            Route::get('/policy-delete/{id}',[PolicyController::class,'policyDelete']);
            Route::get('/policy-delete-file/{file_id}/{policy_id}',[PolicyController::class,'deleteFile']);


        });

        /* =================== Eventos =================== */
        Route::prefix('/events')->group(function(){
            Route::get('/list',[EventController::class,'eventShow'])->name('admin.events');
            Route::post('/save',[EventController::class,'eventSave']);
            Route::post('/edit',[EventController::class,'eventEdit']);
            Route::get('/delete-file/{id_file}/{id_record}',[EventController::class,'deleteImg']);
            Route::get('/delete/{id}',[EventController::class,'eventDelete']);

        });

        /* =================== Beneficios y Prestaciones =================== */
        Route::prefix('/benefits')->group(function(){
            Route::get('/benefit-list',[BenefitController::class,'benefitsShow'])->name('admin.benefits.benefits');
            Route::get('/benefit-list-admin',[BenefitController::class,'benefitsList']);
            Route::post('/benefit-save',[BenefitController::class,'benefitSave']);
            Route::post('/benefit-edit',[BenefitController::class,'benefitEdit']);
            Route::get('/benefit-delete/{id}',[BenefitController::class,'benefitDelete']);
            Route::get('/benefit-delete-file/{file_id}/{benefit_id}',[BenefitController::class,'deleteFile']);


            Route::get('/prestacion-list',[PrestacionController::class,'prestacionShow'])->name('admin.benefits.prestacion');
            Route::get('/prestacion-list-admin',[PrestacionController::class,'prestacionList']);
            Route::post('/prestacion-save',[PrestacionController::class,'prestacionSave']);
            Route::post('/prestacion-edit',[PrestacionController::class,'prestacionEdit']);
            Route::get('/prestacion-delete/{id}',[PrestacionController::class,'prestacionDelete']);
            Route::get('/prestacion-delete-file/{file_id}/{prestacion_id}',[PrestacionController::class,'deleteFile']);

        });


        /* =================== Capacitaciones =================== */
        Route::get('/training',[TrainingController::class,'trainingShow'])->name('admin.training');
        Route::post('/training-save',[TrainingController::class,'trainingSave']);
        Route::post('/training-edit',[TrainingController::class,'trainingEdit']);
        Route::get('/training-delete/{id}',[TrainingController::class,'trainingDelete']);
        Route::get('/training-delete-file/{file_id}/{training_id}',[TrainingController::class,'deleteFile']);

        /* =================== Fundación =================== */
        Route::get('/foundation-capsule',[FoundationCapsuleController::class,'foundationCapsuleShow'])->name('admin.foundation');
        Route::get('/foundation-capsule-list',[FoundationCapsuleController::class,'adminfoundationCapsuleList']);
        Route::post('/foundation-capsule-save',[FoundationCapsuleController::class,'foundationCapsuleSave']);
        Route::post('/foundation-capsule-edit',[FoundationCapsuleController::class,'foundationCapsuleEdit']);
        Route::get('/foundation-capsule-delete/{id}',[FoundationCapsuleController::class,'foundationCapsuleDelete']);
        Route::get('/foundation-capsule/send-reminder/{id}',[FoundationCapsuleController::class,'sendReminder']);
        Route::get('/foundation-capsule-file/{file_id}/{foundation_capsule_id}',[FoundationCapsuleController::class,'deleteFile']);


        /* =================== Configuraciones =================== */
        Route::prefix("/settings")->group(function(){
            Route::get('sub-seccion/all',[RolController::class,'subSeccionAll']);
            Route::get('rol/all',[RolController::class,'rolAll']);

            Route::get('/rol',[RolController::class,'rolShow'])->name('admin.settings.rol');
            Route::get('/rol-list',[RolController::class,'rolList']);
            Route::post('/rol-save',[RolController::class,'rolSave']);
            Route::post('/rol-edit',[RolController::class,'rolEdit']);
            Route::get('/rol-delete/{id}',[RolController::class,'rolDelete']);
            Route::get('/rol-item-delete/{id}',[RolController::class,'rolItemDelete']);

            Route::get('/permission',[PermissionController::class,'permissionShow'])->name('admin.settings.permissions');
            Route::get('/permission-list',[PermissionController::class,'permissionList']);
            Route::post('/permission-save',[PermissionController::class,'permissionSave']);
            Route::post('/permission-edit',[PermissionController::class,'permissionEdit']);
            Route::get('/permission-delete/{id}',[PermissionController::class,'permissionDelete']);
            Route::get('/permission-is-user-exist',[PermissionController::class,'permissionIsUserExist']);

        });


    });
});



Route::prefix('locations')->group(function(){
    Route::get('/all',[LocationController::class,'all']);
    Route::get('/subgroups',[LocationController::class,'subgroups']);
});
Route::prefix('users')->group(function(){
    Route::get('/all',[UserController::class,'all']);
});

Route::get('type_events/all',[EventController::class,'typeEventsAll']);




/**************************************** End -Admin ****************************************/
