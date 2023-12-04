<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;
use App\Models\Comment;
use App\Models\PublicationSection;
use App\Http\Controllers\PublicationController;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Repositories\GeneralFunctionsRepository;

class BlogController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
        $this->GeneralFunctionsRepository = new GeneralFunctionsRepository();
    }

    public function index(){
        $notes = PublicationController::listPublications();
        $compact = compact('notes');

        $params=[
            "sub_seccion_id" =>30,
            "ip" => $this->GeneralFunctionsRepository->getIp(),
            "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
            "comment" => "Revista Digital",
        ];
        $this->GeneralFunctionsRepository->addAudit($params);

        return view('blog.blog')->with($compact);
    }

    public function publication($id){

        $note = Publication::find($id);
        $note->views += 1;
        $note->save();
        $note = Publication::with(['user','comments.childrenComments'])->find($id);
        return view('blog.note')->with(compact('note'));
    }


}
