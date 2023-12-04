<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;
use App\Models\Comment;
use App\Models\PublicationSection;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PublicationController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }

    public static function listPublications(Request $request = null){

        $limit = (isset($limit) && $limit > 0) ? $limit : 10;
        $notes = Publication::with(['user','comments'])->where('publications_section_id',2)
        ->orderBy('created_at','desc')
        ->Paginate($limit);
        $notes->setPath('/publications/list');
        return $notes;

    }

    public function show($id){
        $note = Publication::with(['user','comments.childrenComments'])->find($id);
        return $note;
    }

    public function addPublication(Request $request){

        if((isset($request->title) && strlen($request->title) > 0) && isset($request->description) && strlen($request->description) > 0){

            $add = new Publication();
            $add->publications_section_id = 2;
            $add->vw_users_usuario = auth()->user()->usuario;
            $add->title = $request->title;
            $add->description = $request->description;
            $add->save();

            $note = Publication::with(['user','comments.childrenComments'])->find($add->id);
            return ['success' => 1, 'data' => $note];
        }else{
            return ['success' => 0, 'message' => 'Campos incompletos, verifique que esten completos.'];
        }

    }

}
