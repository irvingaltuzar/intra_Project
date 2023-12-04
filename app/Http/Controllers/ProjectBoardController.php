<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectBoard;
use App\Models\ProjectBoardCategory;
use App\Repositories\GeneralFunctionsRepository;


class ProjectBoardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->sub_seccion_id = 12;
        $this->GeneralFunctionsRepository = new GeneralFunctionsRepository();
    }


    public function showBoard(){

        $projects = ProjectBoard::get();
        $categories = ProjectBoardCategory::get();

        /* Start - Auditoria */
            $params=[
                "sub_seccion_id" =>34,
                "ip" => $this->GeneralFunctionsRepository->getIp(),
                "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
                "comment" => "Project Board",
            ];
            $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        $compact = compact('projects');

        return view("project_board.board")->with($compact);

    }

}
