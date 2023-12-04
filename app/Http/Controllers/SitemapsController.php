<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Repositories\GeneralFunctionsRepository;

class SitemapsController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
        $this->GeneralFunctionsRepository = new GeneralFunctionsRepository();
    }

    public function show(){
        /* Start - Auditoria */
            $params=[
                "sub_seccion_id" =>37,
                "ip" => $this->GeneralFunctionsRepository->getIp(),
                "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
                "comment" => "Beneficios y Prestaciones",
            ];
            $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return view('sitemaps');
    }

}
