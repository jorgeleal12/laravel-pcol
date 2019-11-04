<?php

namespace App\Http\Controllers\NewControllers\odi;

use App\Http\Controllers\Controller;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImageController extends Controller
{
    //

    public function Uploadimage(Request $request)
    {

        $company_name             = 'MLS';
        $contract_name            = 'MEDELLIN';
        $odi                      = $_POST['odi'];
        $idservice_certifications = $_POST['idservice_certifications'];
        $tipe                     = $_POST['tipe'];

        $image = $_FILES;

        foreach ($image as &$image) {
            $hoy  = date("Y-m-d H:i");
            $name = $image["name"];
            $file = $image['tmp_name'];
            $type = $image['type'];

            $Typedoc = explode("/", $type);

            $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            $strlength  = strlen($characters);
            $random     = '';
            for ($i = 0; $i < 15; $i++) {
                $random .= $characters[rand(0, $strlength - 1)];
            }

            if ($Typedoc[1] == 'jpeg' or $Typedoc[1] == 'jpg') {
                $namefile = $random . '.' . $Typedoc[1];
                $carpeta  = public_path('/public/odi/images/' . $company_name . '/' . $contract_name . '/' . $odi . '/');

                if (!File::exists($carpeta)) {

                    $path = public_path('/public/odi/images/' . $company_name . '/' . $contract_name . '/' . $odi . '/');
                    File::makeDirectory($path, 0777, true);

                }

                $url = 'odi/images/' . $company_name . '/' . $contract_name . '/' . $odi . '/';
                move_uploaded_file($file, $carpeta . $namefile);

                $this->sql_image($odi, $namefile, $url, $hoy, $tipe, $idservice_certifications);

            }
        }
    }

    public function sql_image($odi, $namefile, $url, $hoy, $tipe, $idservice_certifications)
    {
        $insert = DB::table('image')
            ->insert([
                'name_image'                                      => $namefile,
                'fecha'                                           => $hoy,
                'url'                                             => $url,
                'odi_idodi'                                       => $odi,
                'idphotos'                                        => $tipe,
                'service_certifications_idservice_certifications' => $idservice_certifications,

            ]);

    }

    public function search_image(Request $request)
    {
        $odi                      = $request->input("odi");
        $idservice_certifications = $request->input("idservice_certifications");

        $search = DB::table('image')
            ->join('photos', 'photos.idphotos', '=', 'image.idphotos')
            ->where('odi_idodi', '=', $odi)
            ->where('service_certifications_idservice_certifications', '=', $idservice_certifications)
            ->select('image.*', 'photos.name_photo')
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function send_image_movil(Request $request)
    {

        $param = $_POST['params'];
        $obj   = json_decode($param, true);

        $odi  = $obj["idodi"];
        $tipe = $obj["tipe"];

        $search_company = DB::table('odi')
            ->join('company', 'company.idcompany', '=', 'odi.company_idcompany')
            ->join('contract', 'contract.company_idcompany', '=', 'company.idcompany')
            ->where('idodi', $odi)
            ->select('contract.contract_name', 'company.company_name')
            ->first();

        $idservice = $obj["idservice"];

        $company_name  = $search_company->company_name;
        $contract_name = $search_company->contract_name;

        $company_name = str_replace(' ', '', $company_name);

        $image = $_FILES;

        $name = $image['file']['name'];
        $file = $image['file']['tmp_name'];
        $type = $image['file']['type'];

        $hoy     = date("Y_m_d_H_i_s");
        $Typedoc = explode(".", $name);

        $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $strlength  = strlen($characters);
        $random     = '';
        for ($i = 0; $i < 15; $i++) {
            $random .= $characters[rand(0, $strlength - 1)];
        }
        $namefile = $random . '.' . $Typedoc[1];
        if ($Typedoc[1] == 'jpeg' or $Typedoc[1] == 'jpg') {

            $carpeta = public_path('/public/odi/images/' . $company_name . '/' . $contract_name . '/' . $odi . '/');

            if (!File::exists($carpeta)) {

                $path = public_path('/public/odi/images/' . $company_name . '/' . $contract_name . '/' . $odi . '/');
                File::makeDirectory($path, 0777, true);

            }

            $url = 'odi/images/' . $company_name . '/' . $contract_name . '/' . $odi . '/';
            move_uploaded_file($file, $carpeta . $namefile);

            $this->sql_image($odi, $namefile, $url, $hoy, $tipe, $idservice);

        }
        $carpeta        = public_path('/public/odi/images/' . $company_name . '/' . $contract_name . '/' . $odi . '/');
        $nombre_fichero = $carpeta . $namefile;

        if (file_exists($nombre_fichero)) {
            $response = true;
        } else {
            $response = false;
        }

        return response()->json(['status' => 'ok', 'response' => $response], 200);

    }

    public function delete_photo(Request $request)
    {

        $idimage    = $request->input('idimage');
        $url        = $request->input('url');
        $name_image = $request->input('name_image');

        $carpeta = public_path('/public/' . $url . '/' . $name_image);

        if (file_exists($carpeta)) {
            $response = true;
            $delete   = DB::table('image')
                ->where('idimage', $idimage)
                ->delete();
            File::delete($carpeta);
        } else {
            $response = false;
        }
        return response()->json(['status' => 'ok', 'response' => $response], 200);
    }

}
