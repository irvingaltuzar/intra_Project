<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Models\ControlPlaza;
use App\Models\UserOrganigrama;
use App\Repositories\GeneralFunctionsRepository;


class OrganizationChartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->GeneralFunctionsRepository = new GeneralFunctionsRepository();
    }

    public $name_jf = "";
    public $primer_puesto = "";
    public $grupo = "";
    public $extension = "";
    public $misdatos = "";
    public $datos = "";

    public function organization_chart(){
        /* Start - Auditoria */
            $params=[
                "sub_seccion_id" =>26,
                "ip" => $this->GeneralFunctionsRepository->getIp(),
                "event" =>(new \ReflectionClass($this))->getShortName()." => ". __FUNCTION__,
                "comment" => "Organigrama",
            ];
            $this->GeneralFunctionsRepository->addAudit($params);
        /* End - Auditoria */

        return view('organization_chart.organigrama');
    }

    public function show($type){
        $pilar = '';

        if($type == 'bienes_raices'){
            $title = 'DMI BIENES RAÍCES';
            $pilar = 'P10060109001001';
        }else if($type == 'negocios'){
            $title = 'DMI DESARROLLO DE NEGOCIOS';
            $pilar = 'P10060109001002';
        }else if($type == 'resp_social'){
            $title = 'DMI RESPONSABILIDAD SOCIAL';
            $pilar = 'P10060109001003';
        }

        $organigrama = $this->get_organigrama($pilar);
        $data = compact('title','organigrama','pilar','type');
        return view('organization_chart.view_organization_chart',$data);
    }



    public function get_organigrama($_pilar){

        // ERP
        $serverName = "192.168.3.124";
        $connectionInfo = array( "Database"=>"INTRANET", "UID"=>"Intranet", "PWD"=>"Dmi2017");
        @session_start();
        $conn1 = sqlsrv_connect( $serverName, $connectionInfo);
        if( $conn1 ) {

        }else{
            die( print_r( sqlsrv_errors(), true));
        }




        // ALFA
        $serverName = "192.168.3.171";
        $connectionInfo = array( "Database"=>"db_alfa", "UID"=>"AlfaUsr", "PWD"=>"4lf4Usr.");
        $conn = sqlsrv_connect( $serverName, $connectionInfo);
        if( $conn ) {

        }else{
            die( print_r( sqlsrv_errors(), true));
        }

        $GLOBALS['conn'] = $conn;

        $nombre = $_pilar;
        $separado = "";
        $nombre2 = "";

        $sql2 = "EXECUTE [dbo].[spDMIPersonalPlaza]";
        $stmt2 = sqlsrv_prepare($conn1, $sql2);
        if( !$stmt2 ) {
                die( print_r( sqlsrv_errors(), true));
        } else {
                //echo "OK------->";
                //echo "se ejecuto correctamente<br/>";
                if(sqlsrv_execute($stmt2)){
                    $sql_intelisis = "select * from DMIPersonalPlaza";
                    $stmt = sqlsrv_prepare($conn1, $sql_intelisis);
                    if( !$stmt ) {
                        ( print_r( sqlsrv_errors(), true));
                    }
                    if(sqlsrv_execute($stmt)){
                        while($res = sqlsrv_fetch_array($stmt)){
                            $nombre2 .= $separado;
                            $nombre2 .= $res["Personal"]."||".$res["Plaza"]."||".$res["SubPlaza"]."||".utf8_encode($res["Nombre"]);
                            $separado = "##";
                        }
                    }

                    $this->primer_puesto = "";
                    $this->grupo = "";
                    $this->extension = "";
                    $this->name_jf = "";
                    $sql_intelisis2 = "select * from DMIPersonalPlaza where Plaza = '".$nombre."'";
                    //echo "-->".$sql_intelisis2;
                    $stmt2 = sqlsrv_prepare($conn1, $sql_intelisis2);
                    if( !$stmt2 ) {
                        ( print_r( sqlsrv_errors(), true));
                    }else{
                        $sql6 = "   select a.personal_id, concat(a.name,' ',a.last_name) as naame, a.position_company as puesto
                                    from personal_intelisis as a where a.status = 'ALTA' and a.plaza_id = '".$nombre."'";
                        //echo "-->".$sql6;

                        $stmt_6 = sqlsrv_prepare($conn, $sql6);
                        if( !$stmt_6 ) {
                            ( print_r( sqlsrv_errors(), true));
                        }
                        $vacio1 = 1;
                        if(sqlsrv_execute($stmt_6)){
                            while($res_6 = sqlsrv_fetch_array($stmt_6)){
                                $this->name_jf = utf8_encode($res_6["naame"]);
                                $this->primer_puesto = utf8_encode($res_6["puesto"]);
                                $this->grupo = '';
                                $this->extension = '';
                                $vacio1 = 0;
                            }
                        }
                        if ($vacio1 == 1){
                            $this->name_jf = "Vacante";
                        }

                    }
                    //echo $this->extension;
                    if(sqlsrv_execute($stmt2)){
                        while($res2 = sqlsrv_fetch_array($stmt2)){
                            //print_r($res)."\n";
                            //echo $res["Personal"];
                            //echo $res["Plaza"];
                            //echo $res["SubPlaza"];
                            $sql5 = "   select a.personal_id, concat(a.name,' ',a.last_name) as naame, a.position_company as puesto
                                        from personal_intelisis as a where a.status = 'ALTA' and a.personal_id = '".$res2["Personal"]."'";

                            $stmt_5 = sqlsrv_prepare($conn, $sql5);
                            if( !$stmt_5 ) {
                                ( print_r( sqlsrv_errors(), true));
                            }

                            if(sqlsrv_execute($stmt_5)){
                                while($res_6a = sqlsrv_fetch_array($stmt_5)){
                                    $this->name_jf = utf8_encode($res_6a["naame"]);
                                    $this->primer_puesto = utf8_encode($res_6a["puesto"]);
                                    $this->grupo = '';
                                    $this->extension = '';
                                }
                            }
                        }
                    }

                    $nombre2 = explode("##", $nombre2);
                    $separado1 = "";
                    $this->contado_parent = 0;
                    $this->contador_siempre = 0;
                    $name = "";
                    $reportaa = "";
                    $puesto = "";
                    $grup = "";
                    $exten = "";
                    $ban_dc = 0;





                    $this->recur($nombre2,$nombre,$this->contado_parent,$conn);

                    if(isset($_POST["nombre2"])){
                    $res_1 = $this->misdatos;
                    $datos = "";
                    $this->misdatos = "";
                    $nombre = $_POST["nombre2"];

                    $sql_intelisis = "select * from DMIPersonalPlaza";
                    $stmt = sqlsrv_prepare($conn1, $sql_intelisis);
                    if( !$stmt ) {
                        ( print_r( sqlsrv_errors(), true));
                    }
                    if(sqlsrv_execute($stmt)){
                        while($res = sqlsrv_fetch_array($stmt)){
                            //print_r($res)."\n";
                            //echo $res["Personal"];
                            //echo $res["Plaza"];
                            //echo $res["SubPlaza"];
                            //echo $res["Nombre"];
                            $nombre2 .= $separado;
                            $nombre2 .= $res["Personal"]."||".$res["Plaza"]."||".$res["SubPlaza"]."||".utf8_encode($res["Nombre"]);
                            $separado = "##";
                        }
                    }
                    //echo $nombre."\n";
                    $this->primer_puesto = "";
                    $this->grupo = "";
                    $this->extension = "";
                    $this->name_jf = "";
                    $sql_intelisis2 = "select * from DMIPersonalPlaza where Plaza = '".$nombre."'";
                    $stmt2 = sqlsrv_prepare($conn1, $sql_intelisis2);
                    if( !$stmt2 ) {
                        ( print_r( sqlsrv_errors(), true));
                    }else{
                        $sql6 = "select a.idpersonal_n, concat(a.nombre,' ',a.apellidos) as naame, a.reportaa, a.puesto, a.grupo, a.extension
                                from personal_intelisis as a where a.estatus = 'ALTA' and a.id_plaza = '".$nombre."'";
                        $result6 = $conn->query($sql6);
                        if ($result6->num_rows > 0) {
                            while($row6 = $result6->fetch_assoc()) {
                                $this->name_jf = utf8_encode($row6["naame"]);
                                $this->primer_puesto = utf8_encode($row6["puesto"]);
                                $this->grupo = utf8_encode($row6["grupo"]);
                                $this->extension = $row6["extension"];
                            }
                        }else{
                            $this->name_jf = "Vacante";
                        }
                    }
                    //echo $this->extension;
                    if(sqlsrv_execute($stmt2)){
                        while($res2 = sqlsrv_fetch_array($stmt2)){
                            //print_r($res)."\n";
                            //echo $res["Personal"];
                            //echo $res["Plaza"];
                            //echo $res["SubPlaza"];
                            $sql5 = "select a.idpersonal_n, concat(a.nombre,' ',a.apellidos) as naame, a.reportaa, a.puesto, a.grupo, a.extension
                                from personal_intelisis as a where a.estatus = 'ALTA' and a.idpersonal_n = '".$res2["Personal"]."'";
                            $result5 = $conn->query($sql5);
                            if ($result5->num_rows > 0) {
                                while($row5 = $result5->fetch_assoc()) {
                                    $this->name_jf = utf8_encode($row5["naame"]);
                                    $this->primer_puesto = utf8_encode($row5["puesto"]);
                                    $this->grupo = utf8_encode($row5["grupo"]);
                                    $this->extension = $row5["extension"];
                                }
                            }
                        }
                    }
                    //echo $nombre2;
                    $nombre2 = explode("##", $nombre2);
                    $separado1 = "";
                    $contado_parent = 0;
                    $contador_siempre = 0;
                    recur($nombre2,$nombre,$contado_parent,$conn);
                    $res_2 = $this->misdatos;

                }

                if(isset($_POST["nombre3"])){
                    $datos = "";
                    $this->misdatos = "";
                    $nombre = $_POST["nombre3"];//"JUAN RAFAEL MUÑOZ DE COTE SERRANO"; //

                    $sql_intelisis = "select * from DMIPersonalPlaza";
                    $stmt = sqlsrv_prepare($conn1, $sql_intelisis);
                    if( !$stmt ) {
                        ( print_r( sqlsrv_errors(), true));
                    }
                    if(sqlsrv_execute($stmt)){
                        while($res = sqlsrv_fetch_array($stmt)){
                            //print_r($res)."\n";
                            //echo $res["Personal"];
                            //echo $res["Plaza"];
                            //echo $res["SubPlaza"];
                            //echo $res["Nombre"];
                            $nombre2 .= $separado;
                            $nombre2 .= $res["Personal"]."||".$res["Plaza"]."||".$res["SubPlaza"]."||".utf8_encode($res["Nombre"]);
                            $separado = "##";
                        }
                    }
                    //echo $nombre."\n";
                    $this->primer_puesto = "";
                    $this->grupo = "";
                    $this->extension = "";
                    $sql5 = "select * from personal_intelisis where concat(nombre,' ',apellidos) = '".utf8_decode($nombre)."'";
                    $result5 = $conn->query($sql5);
                    if ($result5->num_rows > 0) {
                        while($row5 = $result5->fetch_assoc()) {
                            $this->primer_puesto = $row5["puesto"];
                            $this->grupo = $row5["grupo"];
                            $this->extension = $row5["extension"];
                        }
                    }
                    //echo $nombre2;
                    $nombre2 = explode("##", $nombre2);
                    $separado1 = "";
                    $contado_parent = 0;
                    $contador_siempre = 0;
                    recur($nombre2,$nombre,$contado_parent,$conn);
                    $res_3 = $this->misdatos;
                }

                if(isset($_POST["nombre4"])){
                    $datos = "";
                    $this->misdatos = "";
                    $nombre = $_POST["nombre4"];//"JUAN RAFAEL MUÑOZ DE COTE SERRANO"; //

                    $sql_intelisis = "select * from DMIPersonalPlaza";
                    $stmt = sqlsrv_prepare($conn1, $sql_intelisis);
                    if( !$stmt ) {
                        ( print_r( sqlsrv_errors(), true));
                    }
                    if(sqlsrv_execute($stmt)){
                        while($res = sqlsrv_fetch_array($stmt)){
                            //print_r($res)."\n";
                            //echo $res["Personal"];
                            //echo $res["Plaza"];
                            //echo $res["SubPlaza"];
                            //echo $res["Nombre"];
                            $nombre2 .= $separado;
                            $nombre2 .= $res["Personal"]."||".$res["Plaza"]."||".$res["SubPlaza"]."||".utf8_encode($res["Nombre"]);
                            $separado = "##";
                        }
                    }
                    //echo $nombre."\n";
                    $this->primer_puesto = "";
                    $this->grupo = "";
                    $this->extension = "";
                    $sql5 = "select * from personal_intelisis where concat(nombre,' ',apellidos) = '".utf8_decode($nombre)."'";
                    $result5 = $conn->query($sql5);
                    if ($result5->num_rows > 0) {
                        while($row5 = $result5->fetch_assoc()) {
                            $this->primer_puesto = $row5["puesto"];
                            $this->grupo = $row5["grupo"];
                            $this->extension = $row5["extension"];
                        }
                    }
                    //echo $nombre2;
                    $nombre2 = explode("##", $nombre2);
                    $separado1 = "";
                    $contado_parent = 0;
                    $contador_siempre = 0;
                    recur($nombre2,$nombre,$contado_parent,$conn);
                    $res_4 = $this->misdatos;
                }
                }
        }




        if(isset($_POST["nombre4"])){
            $this->misdatos = array(
                'datos1' => utf8_encode($res_1),
                'datos2' => utf8_encode($res_2),
                'datos3' => utf8_encode($res_3),
                'datos4' => utf8_encode($res_4)
            );
        }else if(isset($_POST["nombre3"])){
            $this->misdatos = array(
                'datos1' => utf8_encode($res_1),
                'datos2' => utf8_encode($res_2),
                'datos3' => utf8_encode($res_3)
            );
        }else if(isset($_POST["nombre2"])){
            $this->misdatos = array(
                'datos1' => utf8_encode($res_1),
                'datos2' => utf8_encode($res_2)
            );
        }
        return  json_encode($this->misdatos, JSON_FORCE_OBJECT);

        sqlsrv_close( $conn );

    }

    function recur($nombre2,$nombre,$parentesco){

        for($i = 0; $i < count($nombre2); $i++){
            $nombre3 = explode("||", $nombre2[$i]);
            $pos = strpos($nombre3[2], $nombre);


            if ($pos === false) {
                //echo $nombre3[0];
            } else {

                $sqld = "   select a.personal_id, concat(a.name,' ',a.last_name) as naame, a.position_company as puesto
                            from personal_intelisis as a where a.status = 'ALTA' and a.personal_id = '".$nombre3[0]."'";
                //echo $sqld;
                $stmt_5a = sqlsrv_prepare($GLOBALS['conn'], $sqld);
                if( !$stmt_5a ) {
                    ( print_r( sqlsrv_errors(), true));
                }

                $vacio1a = 1;
                if(sqlsrv_execute($stmt_5a)){
                    while($res_6b = sqlsrv_fetch_array($stmt_5a)){
                        $name = utf8_encode($res_6b["naame"]);
                        $puesto = utf8_encode($res_6b["puesto"]);
                        $grup = '';
                        $exten = '';
                        $reportaa = '';
                        $vacio1a = 0;
                    }
                }
                if ($vacio1a == 1){
                    $name = "Vacante";
                }

                //echo $nombre."=> ".$nombre3[0]."\n";
                if($this->contado_parent == 0 && $this->contador_siempre == 0){
                    //echo $this->primer_puesto;
                    //echo $this->name_jf."-".$this->extension;
                    $this->misdatos .= $this->contador_siempre."||".$this->name_jf."||p||".$this->primer_puesto."||".$this->grupo."||".$this->extension."||@@";
                    $this->contador_siempre = $this->contador_siempre + 1;
                    $this->contado_parent = $this->contado_parent + 1;
                }

                if ($nombre3[3] == 'DIRECTOR DE FES' && $nombre3[0] == null){
                    // Esta es la plaza de la Lic. Alejandra Leaño, sin embargo la plaza no está asignada a un id de personal, por lo que es necesario
                    // setearla para que aparezca un nombre
                    $name = "ALEJANDRA LEAÑO ESPINOSA";
                }

                if ($nombre3[3] == 'DIRECTOR DE CLUBES DEPORTIVOS' && $nombre3[0] == null){
                    // Esta es la plaza del Lic. JULIO ROBERTO SANCHEZ MEJORADA FERNANDEZ, sin embargo la plaza no está asignada a un id de personal, por lo que es necesario
                    // setearla para que aparezca un nombre
                    $name = "JULIO ROBERTO SANCHEZ MEJORADA FERNANDEZ";
                }

                if ($nombre3[3] == 'DIRECTOR DMI AGROINDUSTRIAL' && $nombre3[0] == null){
                    // Esta es la plaza del Lic. ALEJANDRO ARAMBULA GONZALEZ, sin embargo la plaza no está asignada a un id de personal, por lo que es necesario
                    // setearla para que aparezca un nombre
                    $name = "ALEJANDRO ARAMBULA GONZALEZ";
                }


                //$this->misdatos .= $separado1;
                $this->misdatos .= $this->contador_siempre."||".$name."||".$parentesco."||".$nombre3[3]."@@";
                $aux_qui = $this->contador_siempre;
                $this->contador_siempre = $this->contador_siempre + 1;

                $this->recur($nombre2,$nombre3[1],$aux_qui);
            }
        }
    }

    public function frameOrganigramaOld($type){
        $pilar = '';

        if($type == 'bienes_raices'){
            $title = 'DMI BIENES RAÍCES';
            $pilar = 'P10060109001001';
        }else if($type == 'negocios'){
            $title = 'DMI DESARROLLO DE NEGOCIOS';
            $pilar = 'P10060109001002';
        }else if($type == 'resp_social'){
            $title = 'DMI RESPONSABILIDAD SOCIAL';
            $pilar = 'P10060109001003';
        }

        /* $organigrama = $this->get_organigrama($pilar);
        $data = compact('title','organigrama','pilar');
        return view('organization_chart.frame_organigrama',$data); */
        return redirect("/organization_chart/dmi_RESPONSABILIDAD_SOCIAL.php?a=".$pilar);
    }


}
