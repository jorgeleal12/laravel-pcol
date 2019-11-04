<?php

namespace App\Http\Controllers\NewControllers\administration\material;

use App\Http\Controllers\Controller;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{
    //

    public function create(Request $request)
    {
        $name_materials        = $request->input("name_materials");
        $state_one_idstate_one = $request->input("state_one_idstate_one");

        $insert = DB::table('materials')
            ->insertGetid([
                'name_materials' => $name_materials,
                'state'          => $state_one_idstate_one,
            ]);

        return response()->json(['status' => 'ok', 'response' => $insert, 'idmaterials' => $insert], 200);
    }

    public function search()
    {

        $search = DB::table('materials')
            ->select('materials.*', 'materials.state as idstate', DB::raw('(CASE WHEN materials.state = "1" THEN "Activo" WHEN materials.state = "2" THEN "Inactivo" ELSE "Por confirmar" END) AS state'))
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function update(Request $request)
    {

        $idmaterials           = $request->input("idmaterials");
        $name_materials        = $request->input("name_materials");
        $state_one_idstate_one = $request->input("state_one_idstate_one");

        $update = DB::table('materials')
            ->where('idmaterials', $idmaterials)
            ->update([
                'name_materials' => $name_materials,
                'state'          => $state_one_idstate_one,

            ]);

        return response()->json(['status' => 'ok', 'response' => $update], 200);
    }

    public function create_certificate(Request $request)
    {
        $codigo                = $request->input("codigo");
        $date_expiration       = date('Y-m-d', strtotime($request->input("date_expiration"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_expiration")));
        $materials_idmaterials = $request->input("materials_idmaterials");

        $insert = DB::table('material_certificate')
            ->insert([
                'codigo'                => $codigo,
                'date_expiration'       => $date_expiration,
                'materials_idmaterials' => $materials_idmaterials,
            ]);
        return response()->json(['status' => 'ok', 'response' => $insert], 200);
    }
    public function search_certificate(Request $request)
    {
        $idmaterial = $request->input('idmaterial');

        $search = DB::table('material_certificate')
            ->where('materials_idmaterials', $idmaterial)
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function send_document(Request $request)
    {

        $Certificados           = 'CERTIFICADOS';
        $idmaterial_certificate = $_POST['idmaterial_certificate'];

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
            $namefile = $random . '.' . $Typedoc[1];
            $carpeta  = public_path('/public/certificados/documentos/' . $idmaterial_certificate . '/');

            if (!File::exists($carpeta)) {

                $path = public_path('/public/certificados/documentos/' . $idmaterial_certificate . '/');
                File::makeDirectory($path, 0777, true);

            }

            $url = 'certificados/documentos/' . $idmaterial_certificate . '/';
            move_uploaded_file($file, $carpeta . $namefile);

            $this->sql_image($idmaterial_certificate, $namefile, $url, $hoy);

        }

    }

    public function sql_image($idmaterial_certificate, $namefile, $url, $hoy)
    {
        $insert = DB::table('material_documents')
            ->insert([
                'url'                                         => $url,
                'name_document'                               => $namefile,
                'date'                                        => $hoy,
                'material_certificate_idmaterial_certificate' => $idmaterial_certificate,

            ]);

    }

    public function search_document(Request $request)
    {
        $idmaterial_certificate = $request->input('idmaterial_certificate');

        $search = DB::table('material_documents')
            ->where('material_certificate_idmaterial_certificate', $idmaterial_certificate)
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }
    public function delete_document(Request $request)
    {

        $idmaterial_documents = $request->input('idmaterial_documents');
        $url                  = $request->input('url');
        $name                 = $request->input('name');

        $carpeta = public_path('/public/' . $url . '/' . $name);
        if (File::exists($carpeta)) {
            $delete = DB::table('material_documents')
                ->where('idmaterial_documents', $idmaterial_documents)
                ->delete();

            File::delete($carpeta);

            return response()->json(['status' => 'ok', 'response' => $delete], 200);
        }
        return response()->json(['status' => 'ok', 'response' => false], 200);
    }

    public function delete(Request $request)
    {
        $idmaterials = $request->input('idmaterials');

        $search = DB::table('material_certificate')
            ->where('materials_idmaterials', $idmaterials)
            ->get();

        if (count($search) > 0) {

            return response()->json(['status' => 'ok', 'response' => false], 200);
        } else {
            $delete = DB::table('materials')
                ->where('idmaterials', $idmaterials)
                ->delete();
            return response()->json(['status' => 'ok', 'response' => true], 200);
        }

    }

    public function update_certificate(Request $request)
    {

        $idmaterial_certificate = $request->input('idmaterial_certificate');
        $codigo                 = $request->input('codigo');
        $date_expiration        = $request->input('date_expiration');

        $update = DB::table('material_certificate')
            ->where('idmaterial_certificate', $idmaterial_certificate)
            ->update([

                'codigo'          => $codigo,
                'date_expiration' => $date_expiration,
            ]);

        return response()->json(['status' => 'ok', 'response' => $update], 200);
    }

    public function delete_certificate(Request $request)
    {
        $idmaterial_certificate = $request->input('idmaterial_certificate');

        $search = DB::table('material_documents')
            ->where('material_certificate_idmaterial_certificate', $idmaterial_certificate)
            ->get();

        if (count($search) > 0) {

            return response()->json(['status' => 'ok', 'response' => false], 200);
        } else {
            $delete = DB::table('material_certificate')
                ->where('idmaterial_certificate', $idmaterial_certificate)
                ->delete();
            return response()->json(['status' => 'ok', 'response' => true], 200);
        }
    }

    public function savemovil(Request $request)
    {

        $idmaterials           = $request->input("idmaterials");
        $name_materials        = $request->input("name_materials");
        $state_one_idstate_one = $request->input("state_one_idstate_one");

        if ($idmaterials == null) {
            $insert = DB::table('materials')
                ->insertGetid([
                    'name_materials' => $name_materials,
                    'state'          => $state_one_idstate_one,
                ]);

            return response()->json(['status' => 'ok', 'response' => true, 'idmaterials' => $insert], 200);
        } else {

            $update = DB::table('materials')
                ->where('idmaterials', $idmaterials)
                ->update([
                    'name_materials' => $name_materials,
                    'state'          => $state_one_idstate_one,

                ]);

            return response()->json(['status' => 'ok', 'response' => false], 200);
        }
    }
}
