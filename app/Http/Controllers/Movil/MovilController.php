<?php

namespace App\Http\Controllers\Movil;

use App\Http\Controllers\Controller;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

header('Access-Control-Allow-Origin: *');
class MovilController extends Controller
{
    //
    public function search_programming(Request $request)
    {
        $cedula          = $request->input("cedula");
        $search_employee = DB::table('employees')
            ->where('Users_id_identification', '=', $cedula)
            ->select('employees.idemployees')
            ->first();
        $fecha = date("Y-m-d");

        $idemployees = $search_employee->idemployees;

        $search_obr = DB::table('worki')
            ->join('municipality', 'worki.Municipio', '=', 'municipality.id_dane')
            ->join('contract', 'worki.idcontrato', '=', 'contract.idcontract')
            ->join('business', 'worki.id_company', '=', 'business.idbusiness')
            ->where('programado_A', '=', $idemployees)
            ->where('Fecha_Prog', '=', $fecha)
            ->select('municipality.name_municipality', 'worki.Instalacion', 'worki.idworkI', 'worki.consecutive', 'worki.Pedido', 'worki.Direccion'
                , 'worki.Solicitante', 'worki.Telefono', 'worki.x', 'worki.y', 'contract.contract_name', 'business.company_name', 'worki.Estrato', 'worki.consecutive', 'worki.lng', 'worki.lat')
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search_obr], 200);
    }

    public function search_programming_oym(Request $request)
    {
        $cedula          = $request->input("cedula");
        $search_employee = DB::table('employees')
            ->where('Users_id_identification', '=', $cedula)
            ->select('employees.idemployees')
            ->first();
        $fecha = date("Y-m-d");

        $idemployees = $search_employee->idemployees;

        $search_obr = DB::table('oym')
            ->join('municipality', 'oym.municipio', '=', 'municipality.id_dane')
            ->join('contract', 'oym.idcontract', '=', 'contract.idcontract')
            ->join('business', 'oym.company', '=', 'business.idbusiness')
            ->where('idprogramado', '=', $idemployees)
            ->where('Fecha_Prog', '=', $fecha)
            ->select('municipality.name_municipality', 'oym.id_oym', 'oym.consecutive', 'oym.pedido', 'oym.address'
                , 'oym.user', 'oym.phone', 'contract.contract_name', 'business.company_name')
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search_obr], 200);
    }

    public function search_programming2(Request $request)
    {
        $cedula = $request->input("cedula");
        $fecha  = $request->input("fecha");
        $month2 = $request->input("month2");

        $search_employee = DB::table('employees')
            ->where('Users_id_identification', '=', $cedula)
            ->select('employees.idemployees')
            ->first();

        $idemployees = $search_employee->idemployees;

        $search_obr = DB::table('worki')
            ->join('municipality', 'worki.Municipio', '=', 'municipality.id_dane')
            ->join('contract', 'worki.idcontrato', '=', 'contract.idcontract')
            ->join('business', 'worki.id_company', '=', 'business.idbusiness')
            ->where('programado_A', '=', $idemployees)
            ->whereBetween('Fecha_Prog', [$fecha, $month2])
            ->select('municipality.name_municipality', 'worki.Instalacion', 'worki.idworkI', 'worki.consecutive', 'worki.Pedido', 'worki.Direccion'
                , 'worki.Solicitante', 'worki.Telefono', 'worki.x', 'worki.y', 'contract.contract_name', 'business.company_name', 'worki.Estrato', 'worki.consecutive', 'worki.lng', 'worki.lat')
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search_obr], 200);
    }
    public function send_image(Request $request)
    {

        $param  = $_POST['params'];
        $obj    = json_decode($param, true);
        $id_obr = $obj["idworkI"];
        // $contract      = $param['contract'];
        $company_name  = $obj['company_name'];
        $contract_name = $obj['contract_name'];
        $consec        = $obj['consecutive'];

        $image = $_FILES;

        $name = $image['file']['name'];
        $file = $image['file']['tmp_name'];

        $type    = $image['file']['type'];
        $hoy     = date("Y_m_d_H_i_s");
        $Typedoc = explode(".", $name);

        $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        $strlength = strlen($characters);

        $random       = '';
        $company_name = str_replace(' ', '', $company_name);

        for ($i = 0; $i < 15; $i++) {
            $random .= $characters[rand(0, $strlength - 1)];
        }

        if ($Typedoc[1] == 'jpeg' or $Typedoc[1] == 'jpg') {

            $namefile = $random . '.' . $Typedoc[1];
            $carpeta  = public_path('/public/internas/images/' . $company_name . '/' . $contract_name . '/' . $consec . '/');

            if (!File::exists($carpeta)) {
                $path = public_path('/public/internas/images/' . $company_name . '/' . $contract_name . '/' . $consec . '/');
                File::makeDirectory($path, 0777, true);
            }
            $url = '/internas/images/' . $company_name . '/' . $contract_name . '/' . $consec . '/';
            move_uploaded_file($file, $carpeta . $namefile);

            MovilController::insert_image($id_obr, $namefile, $url, $hoy);
        }

        if ($Typedoc[1] == 'pdf') {

            $namefile = $random . '.' . $Typedoc[1];
            $carpeta  = public_path('/public/internas/pdf/' . $company_name . '/' . $contract_name . '/' . $consec . '/');
            if (!File::exists($carpeta)) {
                $path = public_path('/public/internas/pdf/' . $company_name . '/' . $contract_name . '/' . $consec . '/');
                File::makeDirectory($path, 0777, true);
            }
            $url = '/internas/pdf/' . $company_name . '/' . $contract_name . '/' . $consec . '/';
            move_uploaded_file($file, $carpeta . $namefile);
            MovilController::insert_image($id_obr, $namefile, $url, $hoy);
        }

        $nombre_fichero = $carpeta . $namefile;

        if (file_exists($nombre_fichero)) {
            $response = true;
        } else {
            $response = false;
        }

        return response()->json(['status' => 'ok', 'response' => $response], 200);
    }

    public function insert_image($id_obr, $namefile, $carpeta, $hoy)
    {

        $insert = DB::table('image_internas')
            ->insert([
                'id_obr'     => $id_obr,
                'name_image' => $namefile,
                'url'        => $carpeta,
            ]);
    }

    public function send_image_oym(Request $request)
    {

        $param  = $_POST['params'];
        $obj    = json_decode($param, true);
        $id_oym = $obj["id_oym"];
        // $contract      = $param['contract'];
        $company_name  = $obj['company_name'];
        $contract_name = $obj['contract_name'];
        $consec        = $obj['consecutive'];

        $image = $_FILES;

        $name = $image['file']['name'];
        $file = $image['file']['tmp_name'];

        $type    = $image['file']['type'];
        $hoy     = date("Y_m_d_H_i_s");
        $Typedoc = explode(".", $name);

        $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        $strlength = strlen($characters);

        $random       = '';
        $company_name = str_replace(' ', '', $company_name);

        $data = $this->search_data($consec);

        $activity = DB::table('list_activity_oym')
            ->where('id_activity', $data->activity)
            ->first();

        for ($i = 0; $i < 15; $i++) {
            $random .= $characters[rand(0, $strlength - 1)];
        }

        if ($Typedoc[1] == 'jpeg' or $Typedoc[1] == 'jpg') {

            $namefile = $random . '.' . $Typedoc[1];
            $carpeta  = public_path('/public/oym/images/' . $company_name . '/' . $contract_name . '/' . $data->pedido . '-' . $data->ot . '-' . $data->cod_instalacion . '-' . $activity->name_activity . '/');

            if (!File::exists($carpeta)) {
                $path = public_path('/public/oym/images/' . $company_name . '/' . $contract_name . '/' . $data->pedido . '-' . $data->ot . '-' . $data->cod_instalacion . '-' . $activity->name_activity . '/');
                File::makeDirectory($path, 0777, true);
            }
            $url = '/oym/images/' . $company_name . '/' . $contract_name . '/' . $data->pedido . '-' . $data->ot . '-' . $data->cod_instalacion . '-' . $activity->name_activity . '/';
            move_uploaded_file($file, $carpeta . $namefile);

            $path1 = public_path('/public' . $url . $namefile);

            MovilController::insert_image_oym($id_oym, $namefile, $url, $name);
        }

        if ($Typedoc[1] == 'pdf') {

            $namefile = $random . '.' . $Typedoc[1];
            $carpeta  = public_path('/public/oym/pdf/' . $company_name . '/' . $contract_name . '/' . $consec . '/');
            if (!File::exists($carpeta)) {
                $path = public_path('/public/oym/pdf/' . $company_name . '/' . $contract_name . '/' . $consec . '/');
                File::makeDirectory($path, 0777, true);
            }
            $url = '/oym/pdf/' . $company_name . '/' . $contract_name . '/' . $consec . '/';
            move_uploaded_file($file, $carpeta . $namefile);
            MovilController::insert_image_oym($id_oym, $namefile, $url, $name);
        }

        $nombre_fichero = $carpeta . $namefile;

        if (file_exists($nombre_fichero)) {
            $response = true;
        } else {
            $response = false;
        }

        return response()->json(['status' => 'ok', 'response' => $response], 200);
    }

    public function search_data($consec)
    {

        $data = DB::table('oym')
            ->where('consecutive', $consec)
            ->first();
        return $data;

    }

    public function insert_image_oym($id_oym, $namefile, $carpeta, $name)
    {
        $hoy    = date("Y_m_d_H_i_s");
        $insert = DB::table('images_oym')
            ->insert([
                'id_oym'         => $id_oym,
                'name_image'     => $namefile,
                'url'            => $carpeta,
                'fecha'          => $hoy,
                'name_image_app' => $name,
            ]);
    }

    public function search_image(Request $request)
    {
        $idworkI = $request->input("idworkI");

        $search_image = DB::table('image_internas')
            ->where('id_obr', '=', $idworkI)
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search_image], 200);
    }

    public function search_image_oym(Request $request)
    {
        $id_oym       = $request->input("id_oym");
        $search_image = DB::table('images_oym')
            ->where('id_oym', '=', $id_oym)
            ->select('images_oym.*', 'images_oym.id_images as idimage_internas')
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search_image], 200);
    }

    public function search_imageone_oym(Request $request)
    {
        $ruta = $request->input("ruta");

        $res = DB::table('images_oym')
            ->where('id_images', '=', $ruta)
            ->first();

        return response()->json(['status' => 'ok', 'response' => $res], 200);
    }

    public function search_imageone(Request $request)
    {
        $ruta = $request->input("ruta");

        $res = DB::table('image_internas')
            ->where('idimage_internas', '=', $ruta)
            ->first();

        return response()->json(['status' => 'ok', 'response' => $res], 200);
    }

    public function search_consec(Request $request)
    {
        $consec = $request->input("consec");

        $search_obr = DB::table('worki')
            ->leftjoin('municipality', 'worki.Municipio', '=', 'municipality.id_dane')
            ->leftjoin('contract', 'worki.idcontrato', '=', 'contract.idcontract')
            ->leftjoin('business', 'worki.id_company', '=', 'business.idbusiness')
            ->where('worki.consecutive', '=', $consec)
            ->select('municipality.name_municipality', 'worki.Instalacion', 'worki.idworkI', 'worki.consecutive', 'worki.Pedido', 'worki.Direccion'
                , 'worki.Solicitante', 'worki.Telefono', 'worki.x', 'worki.y', 'contract.contract_name', 'business.company_name', 'worki.Estrato', 'worki.consecutive', 'worki.lng', 'worki.lat')
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search_obr], 200);
    }

    public function search_consec_oym(Request $request)
    {
        $consec = $request->input("consec");

        $search_obr = DB::table('oym')
            ->join('municipality', 'oym.municipio', '=', 'municipality.id_dane')
            ->join('contract', 'oym.idcontract', '=', 'contract.idcontract')
            ->join('business', 'oym.company', '=', 'business.idbusiness')
            ->where('oym.consecutive', '=', $consec)
            ->select('municipality.name_municipality', 'oym.id_oym', 'oym.consecutive', 'oym.pedido', 'oym.address'
                , 'oym.user', 'oym.phone', 'contract.contract_name', 'business.company_name')
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search_obr], 200);

    }

    public function search_items(Request $request)
    {

        $idwork = $request->input("idwork");

        $search = DB::table('items_aplicables')
            ->where('id_obr', $idwork)
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

}
