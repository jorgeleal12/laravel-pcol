<?php

namespace App\Http\Controllers\Odi;

use App\Http\Controllers\Controller;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OdiController extends Controller
{
    //

    public function search_consec(Request $request)
    {

        $consecutive = $request->input("term");
        $company     = $request->input("company");

        $search = DB::table('odi')
            ->where('id_company', '=', $company)
            ->where('consecutive', 'like', $consecutive . '%')
            ->orderBy('consecutive', 'ASC')
            ->select('odi.consecutive')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'consecutive' => $search], 200);

    }

    public function search_pedido(Request $request)
    {

        $peido   = $request->input("term");
        $company = $request->input("company");

        $search = DB::table('odi')
            ->where('id_company', '=', $company)
            ->where('pedido', 'like', $peido . '%')
            ->orderBy('pedido', 'ASC')
            ->select('odi.pedido')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'Pedido' => $search], 200);
    }

    public function search_ot(Request $request)
    {

        $ot      = $request->input("term");
        $company = $request->input("company");

        $search = DB::table('odi')
            ->where('id_company', '=', $company)
            ->where('ot', 'like', $ot . '%')
            ->orderBy('ot', 'ASC')
            ->select('odi.ot')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'ot' => $search], 200);
    }

    public function search_cedula(Request $request)
    {

        $Cedula  = $request->input("term");
        $company = $request->input("company");

        $search = DB::table('odi')
            ->where('id_company', '=', $company)
            ->where('cc', 'like', $Cedula . '%')
            ->orderBy('cc', 'ASC')
            ->select('odi.cc')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'Cedula' => $search], 200);
    }
    public function search_address(Request $request)
    {

        $Direccion = $request->input("term");
        $company   = $request->input("company");

        $search = DB::table('odi')
            ->where('id_company', '=', $company)
            ->where('address', 'like', $Direccion . '%')
            ->orderBy('address', 'ASC')
            ->select('odi.address')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'Direccion' => $search], 200);
    }
    public function search_instal(Request $request)
    {

        $Instalacion = $request->input("term");
        $company     = $request->input("company");

        $search = DB::table('worki')
            ->where('id_company', '=', $company)
            ->where('Instalacion', 'like', $Instalacion . '%')
            ->orderBy('Instalacion', 'ASC')
            ->select('worki.Instalacion')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'Instalacion' => $search], 200);
    }
    public function searchconsec(Request $request)
    {
        $consec  = $request->input("consec");
        $company = $request->input("company");

        $search = DB::table('odi')
            ->where('id_company', '=', $company)
            ->where('consecutive', '=', $consec)
            ->orderBy('consecutive', 'ASC')
            ->select('odi.*')
            ->get();

        return response()->json(['status' => 'ok', 'result' => $search], 200);
    }

    public function searchot(Request $request)
    {
        $ot      = $request->input("ot");
        $company = $request->input("company");

        $search = DB::table('odi')
            ->where('id_company', '=', $company)
            ->where('ot', '=', $ot)
            ->orderBy('ot', 'ASC')
            ->select('odi.*')
            ->get();

        return response()->json(['status' => 'ok', 'result' => $search], 200);
    }

    public function searchpedido(Request $request)
    {
        $pedido  = $request->input("pedido");
        $company = $request->input("company");

        $search = DB::table('odi')
            ->where('id_company', '=', $company)
            ->where('pedido', '=', $pedido)
            ->orderBy('pedido', 'ASC')
            ->select('odi.*')
            ->get();

        return response()->json(['status' => 'ok', 'result' => $search], 200);
    }

    public function searchcedula(Request $request)
    {
        $cedula  = $request->input("cedula");
        $company = $request->input("company");

        $search = DB::table('odi')
            ->where('id_company', '=', $company)
            ->where('cc', '=', $cedula)
            ->orderBy('cc', 'ASC')
            ->select('odi.*')
            ->get();

        return response()->json(['status' => 'ok', 'result' => $search], 200);
    }

    public function address(Request $request)
    {
        $address = $request->input("address");
        $company = $request->input("company");

        $search = DB::table('odi')
            ->where('id_company', '=', $company)
            ->where('address', '=', $address)
            ->orderBy('address', 'ASC')
            ->select('odi.*')
            ->get();

        return response()->json(['status' => 'ok', 'result' => $search], 200);
    }

    public function searchtipoobr(Request $request)
    {
        $search = DB::table('tipoobr_odi')
            ->get();

        return response()->json(['status' => 'ok', 'result' => $search], 200);
    }

    public function onFileChange(Request $request)
    {
        $data     = $request->input("data");
        $contract = $request->input("contract");
        $company  = $request->input("company");

        for ($i = 0; $i < count($data); $i++) {

            $DEPARTAMENTO        = isset($data[$i]["A"]) ? $data[$i]["A"] : null;
            $MUNICIPIO           = isset($data[$i]["B"]) ? $data[$i]["B"] : null;
            $ZONA                = isset($data[$i]["C"]) ? $data[$i]["C"] : null;
            $BARRIO              = isset($data[$i]["D"]) ? $data[$i]["D"] : null;
            $CEDULA              = isset($data[$i]["E"]) ? $data[$i]["E"] : null;
            $MEDIO_RECEPCION     = isset($data[$i]["F"]) ? $data[$i]["F"] : null;
            $TIPO_DE_OBRA        = isset($data[$i]["G"]) ? $data[$i]["G"] : null;
            $OT                  = isset($data[$i]["H"]) ? $data[$i]["H"] : null;
            $CODIGO_PROGRAMACION = isset($data[$i]["I"]) ? $data[$i]["I"] : null;
            $FECHA_ASIG          = isset($data[$i]["J"]) ? $data[$i]["J"] : null;
            $FECHA_INSTALACION   = isset($data[$i]["K"]) ? $data[$i]["K"] : null;
            $DIRECCION           = isset($data[$i]["L"]) ? $data[$i]["L"] : null;
            $CATEGORIA           = isset($data[$i]["M"]) ? $data[$i]["M"] : null;
            $NOMBRE              = isset($data[$i]["N"]) ? $data[$i]["N"] : null;
            $TELEFONO            = isset($data[$i]["O"]) ? $data[$i]["O"] : null;
            $MEDIDOR             = isset($data[$i]["P"]) ? $data[$i]["P"] : null;
            $FECHA_VENCIMIENTO   = isset($data[$i]["Q"]) ? $data[$i]["Q"] : null;
            $OBSERVACION         = isset($data[$i]["R"]) ? $data[$i]["R"] : null;

            $create = DB::table('odi')
                ->insert([
                    'department'       => $DEPARTAMENTO,
                    'municipio'        => $MUNICIPIO,
                    'zona'             => $ZONA,
                    'neightborthood'   => $BARRIO,
                    'cc'               => $CEDULA,
                    'type_work'        => $TIPO_DE_OBRA,
                    'ot'               => $OT,
                    'programationcode' => $CODIGO_PROGRAMACION,
                    'date_assignment'  => $FECHA_ASIG,
                    'instalation'      => $FECHA_INSTALACION,
                    'address'          => $DIRECCION,
                    'id_contract'      => $contract,
                    'id_company'       => $company,
                    'user'             => $NOMBRE,
                    'phone'            => $TELEFONO,
                    'medidor'          => $MEDIDOR,
                    'expiration'       => $FECHA_VENCIMIENTO,
                    'obs'              => $OBSERVACION,
                ]);

        }

        return response()->json(['status' => 'ok', 'create' => $create], 200);
    }

    public function searchodi(Request $request)
    {
        $company = $request->input("company");
        $idodi   = $request->input("idodi");

        $search = DB::table('odi')
            ->where('idodi', '=', $idodi)
            ->select('odi.*',

                DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=odi.assignment) AS nameassignment")
                ,

                DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=odi.inspector) AS nameinspection"))

            ->first();
        return response()->json(['status' => 'ok', 'result' => $search], 200);
    }

    public function update(Request $request)
    {
        $LecturaMedidor   = $request->input("LecturaMedidor");
        $address          = $request->input("address");
        $assignment       = $request->input("assignment");
        $cc               = $request->input("cc");
        $cod_instalacion  = $request->input("cod_instalacion");
        $date_assignment  = $request->input("date_assignment");
        $date_programing  = $request->input("date_programing");
        $department       = $request->input("department");
        $etaparegulador   = $request->input("etaparegulador");
        $exitTime         = $request->input("exitTime");
        $exitsCloses      = $request->input("exitsCloses");
        $expiration       = $request->input("expiration");
        $expiredDays      = $request->input("expiredDays");
        $id_company       = $request->input("id_company");
        $id_contract      = $request->input("id_contract");
        $idodi            = $request->input("idodi");
        $inspection       = $request->input("inspection");
        $inspector        = $request->input("inspector");
        $instalation      = $request->input("instalation");
        $legatizationdate = $request->input("legatizationdate");
        $marcaMedidor     = $request->input("marcaMedidor");
        $medidor          = $request->input("medidor");
        $municipio        = $request->input("municipio");
        $neightborthood   = $request->input("neightborthood");
        $numberVisits     = $request->input("numberVisits");
        $obs              = $request->input("obs");
        $obscr            = $request->input("obscr");
        $ot               = $request->input("ot");
        $pedido           = $request->input("pedido");
        $phone            = $request->input("phone");
        $programationcode = $request->input("programationcode");
        $regulador        = $request->input("regulador");
        $revisionType     = $request->input("revisionType");
        $selloMedidor     = $request->input("selloMedidor");
        $selloregulador   = $request->input("selloregulador");
        $serieMedidor     = $request->input("serieMedidor");
        $startTime        = $request->input("startTime");
        $state            = $request->input("state");
        $typeMedidor      = $request->input("typeMedidor");
        $type_work        = $request->input("type_work");
        $user             = $request->input("user");
        $zona             = $request->input("zona");
        $idassignment     = $request->input("idassignment");
        $legalizationdate = $request->input("legalizationdate");
        $inspector        = $request->input("inspector");
        $marcaRegulador   = $request->input("marcaRegulador");
        $testPressure     = $request->input("testPressure");

        $nacta     = $request->input("nacta");
        $seriesepo = $request->input("seriesepo");

        $hermeticidad = $request->input("hermeticidad");

        $eov          = $request->input("eov");
        $trazado      = $request->input("trazado");
        $evMateriales = $request->input("evMateriales");
        $conVent      = $request->input("conVent");
        $medMonCar    = $request->input("medMonCar");
        $ubArtGas     = $request->input("ubArtGas");

        $requiredVent  = $request->input("requiredVent");
        $availableVent = $request->input("availableVent");
        $type_gas      = $request->input("type_gas");

        $update = DB::table('odi')
            ->where('idodi', $idodi)
            ->update([
                'assignment'       => $idassignment,
                'date_assignment'  => $date_assignment,
                'type_work'        => $type_work,
                'state'            => $state,
                'numberVisits'     => $numberVisits,
                'instalation'      => $instalation,
                'inspection'       => $inspection,
                'expiration'       => $expiration,
                'startTime'        => $startTime,
                'exitTime'         => $exitTime,
                'inspector'        => $inspector,
                'date_programing'  => $date_programing,
                'legalizationdate' => $legalizationdate,
                //'expiredDays'      => ,
                'revisionType'     => $revisionType,
                'obs'              => $obs,
                'medidor'          => $medidor,
                'marcaMedidor'     => $marcaMedidor,
                'typeMedidor'      => $typeMedidor,
                'serieMedidor'     => $serieMedidor,
                'selloMedidor'     => $selloMedidor,
                'exitsCloses'      => $exitsCloses,
                'LecturaMedidor'   => $LecturaMedidor,
                'obscr'            => $obscr,
                'legatizationdate' => $legatizationdate,
                'regulador'        => $regulador,
                'marcaRegulador'   => $marcaRegulador,
                'selloregulador'   => $selloregulador,
                'etaparegulador'   => $etaparegulador,
                'testPressure'     => $testPressure,
                'hermeticidad'     => $hermeticidad,
                'eov'              => $eov,
                'trazado'          => $trazado,
                'evMateriales'     => $evMateriales,
                'conVent'          => $conVent,
                'medMonCar'        => $medMonCar,
                'ubArtGas'         => $ubArtGas,
                'seriesepo'        => $seriesepo,
                'nacta'            => $nacta,

                'requiredVent'     => $requiredVent,
                'availableVent'    => $availableVent,
                'type_gas'         => $type_gas,

            ]);
        return response()->json(['status' => 'ok', 'result' => true], 200);
    }

    public function imagesend(Request $request)
    {

        // $contract      = $param['contract'];

        $company_name  = $_POST["company_name"];
        $contract_name = $_POST['contract_name'];
        $idodi         = $_POST['idodi'];

        $company_name = str_replace(' ', '', $company_name);
        $image        = $_FILES;
        $hoy          = date("Y-m-d H:i");

        foreach ($image as &$image) {

            $name = $image['name'];
            $file = $image['tmp_name'];
            $type = $image['type'];

            $Typedoc = explode("/", $type);

            $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

            $strlength = strlen($characters);

            $random       = '';
            $company_name = str_replace(' ', '', $company_name);

            for ($i = 0; $i < 15; $i++) {
                $random .= $characters[rand(0, $strlength - 1)];
            }

            if ($Typedoc[1] == 'jpeg' or $Typedoc[1] == 'jpg') {

                $namefile = $random . '.' . $Typedoc[1];
                $carpeta  = public_path('/public/odi/images/' . $company_name . '/' . $contract_name . '/' . $idodi . '/');

                if (!File::exists($carpeta)) {

                    $path = public_path('/public/odi/images/' . $company_name . '/' . $contract_name . '/' . $idodi . '/');
                    File::makeDirectory($path, 0777, true);

                }
                //$img = Image::make($file)->resize(1920, 1080);

                $url = '/odi/images/' . $company_name . '/' . $contract_name . '/' . $idodi . '/';
                move_uploaded_file($file, $carpeta . $namefile);

                //$img->save($carpeta . $namefile, 50);

                $this->insert_image($namefile, $url, $idodi, $hoy);
                // public function insert_image($idacta1, $namefile, $url, $idacta, $hoy)
            }

            if ($Typedoc[1] == 'pdf') {

                $namefile = $random . '.' . $Typedoc[1];
                $carpeta  = public_path('/public/odi/pdf/' . $company_name . '/' . $contract_name . '/' . $idodi . '/');
                if (!File::exists($carpeta)) {
                    $path = public_path('/public/odi/pdf/' . $company_name . '/' . $contract_name . '/' . $idodi . '/');
                    File::makeDirectory($path, 0777, true);
                }
                $url = '/odi/pdf/' . $company_name . '/' . $contract_name . '/' . $idodi . '/';
                move_uploaded_file($file, $carpeta . $namefile);

                // ExternalController::insert_image($idacta, $obr_anillos_oti, $namefile, $url, $idodi, $hoy);
            }

        }
        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function insert_image($namefile, $url, $idodi, $hoy)
    {

        $insert = DB::table('image_odi')
            ->insert([
                'name_image' => $namefile,
                'url'        => $url,
                'idodi'      => $idodi,
                'date'       => $hoy,
            ]);
    }

    public function search_image(Request $request)
    {
        $idodi = $request->input("idodi");
        $url1  = $request->input("url");

        $search = DB::table('image_odi')
            ->where('idodi', '=', $idodi)
            ->select('name_image', DB::raw("CONCAT('$url1', url,name_image) AS small"), DB::raw("CONCAT('$url1', url,name_image) AS medium"), DB::raw("CONCAT('$url1', url,name_image) AS big"))
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

}
