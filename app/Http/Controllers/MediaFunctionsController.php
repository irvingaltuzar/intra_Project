<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AreaNotice;
use App\Models\FoundationCapsule;
use App\Models\BucketLocation;
use Illuminate\Support\Facades\Storage;
use App\Repositories\GeneralFunctionsRepository;
use Carbon\Carbon;
use Image;
use App\Models\BucketFile;
use App\Models\File;



class MediaFunctionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->GeneralFunctionsRepository = new GeneralFunctionsRepository();
    }

    public function multimediaVideo($_sub_seccion, $_record_id){

        if($_sub_seccion == 'area_notice'){
            $record = AreaNotice::find($_record_id);
            return view('componentes_generales.multimediaVideo')->with(compact('record'));

        }else if($_sub_seccion == 'foundation_capsule'){
            $record = FoundationCapsule::find($_record_id);
            return view('componentes_generales.multimediaVideo')->with(compact('record'));

        }else{
            return redirect('/');
        }

    }



}
