<?php

namespace App\Http\Controllers\NewControllers\administration\builder;

use App\Http\Controllers\Controller;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuilderController extends Controller
{
    //

    public function create(Request $request)
    {
        $idbuilder      = $request->input('idbuilder');
        $name_builder   = $request->input('name_builder');
        $identification = $request->input('identification');
        $state          = $request->input('state');

        if (!$idbuilder) {
            $insert = DB::table('builder')
                ->insert([
                    'name_builder'   => $name_builder,
                    'identification' => $identification,
                    'state'          => $state,
                ]);

            return response()->json(['status' => 'ok', 'result' => $insert, 'response' => true], 200);
        } else {
            $update = DB::table('builder')
                ->where('idbuilder', $idbuilder)
                ->update([
                    'name_builder'   => $name_builder,
                    'identification' => $identification,
                    'state'          => $state,
                ]);
            return response()->json(['status' => 'ok', 'result' => $update, 'response' => false], 200);
        }
    }

    public function search()
    {

        $serach = DB::table('builder')
            ->select('builder.*', 'builder.state as idstate', DB::raw('(CASE WHEN builder.state = "1" THEN "Activo" WHEN builder.state = "2" THEN "Inactivo" ELSE "Por confirmar" END) AS state'))
            ->get();

        return response()->json(['status' => 'ok', 'response' => $serach], 200);
    }

    public function create_sic(Request $request)
    {

        $idsic_builder     = $request->input('idsic_builder');
        $code_sic          = $request->input('code_sic');
        $date_expiration   = date('Y-m-d', strtotime($request->input("date_expiration"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_expiration")));
        $builder_idbuilder = $request->input('builder_idbuilder');

        if (!$idsic_builder) {
            $insert = DB::table('sic_builder')
                ->insertGetid([
                    'code_sic'          => $code_sic,
                    'date_expiration'   => $date_expiration,
                    'builder_idbuilder' => $builder_idbuilder,
                ]);

            return response()->json(['status' => 'ok', 'result' => $insert, 'response' => true], 200);

        } else {

            $update = DB::table('sic_builder')
                ->where('idsic_builder', $idsic_builder)
                ->update([
                    'code_sic'        => $code_sic,
                    'date_expiration' => $date_expiration,

                ]);
            $this->delete_document($idsic_builder);
            return response()->json(['status' => 'ok', 'result' => $update, 'response' => false], 200);
        }
    }
    public function search_sic(Request $request)
    {
        $builder_idbuilder = $request->input('builder_idbuilder');

        $serach = DB::table('sic_builder')
            ->where('builder_idbuilder', $builder_idbuilder)
            ->get();

        return response()->json(['status' => 'ok', 'response' => $serach], 200);
    }

    public function send_document(Request $request)
    {

        $namecarpeta   = $request->input('carpeta');
        $type          = $request->input('type');
        $idsic_builder = $_POST['idsic_builder'];

        $image = $_FILES;

        foreach ($image as &$image) {
            $hoy  = date("Y-m-d H:i");
            $name = $image["name"];
            $file = $image['tmp_name'];

            $namefile = $idsic_builder . '-' . $name;

            $carpeta = public_path('/public/' . $namecarpeta . '/documentos/' . $idsic_builder . '/');

            if (!File::exists($carpeta)) {

                $path = public_path('/public/' . $namecarpeta . '/documentos/' . $idsic_builder . '/');
                File::makeDirectory($path, 0777, true);

            }

            $url = $namecarpeta . '/documentos/' . $idsic_builder . '/';
            move_uploaded_file($file, $carpeta . $namefile);

            $this->sql_image($idsic_builder, $namefile, $url, $hoy, $type);

        }

    }

    public function sql_image($idsic_builder, $namefile, $url, $hoy, $type)
    {

        if ($type == 1) {
            $insert = DB::table('document_builder')
                ->insert([
                    'url'                       => $url,
                    'name'                      => $namefile,
                    'date_'                     => $hoy,
                    'type'                      => $type,
                    'sic_builder_idsic_builder' => $idsic_builder,

                ]);

        }

        if ($type == 2) {
            $insert = DB::table('document_builder')
                ->insert([
                    'url'                                         => $url,
                    'name'                                        => $namefile,
                    'date_'                                       => $hoy,
                    'type'                                        => $type,
                    'competitions_builder_idcompetitions_builder' => $idsic_builder,

                ]);

        }

    }

    public function delete_document($idsic_builder)
    {

        $search = DB::table('document_builder')
            ->where('sic_builder_idsic_builder', $idsic_builder)
            ->first();

        $carpeta = public_path('/public/' . $search->url . '/' . $search->name);
        if (File::exists($carpeta)) {
            $delete = DB::table('document_builder')
                ->where('iddocument_builder', $search->iddocument_builder)
                ->delete();

            File::delete($carpeta);

            return response()->json(['status' => 'ok', 'response' => $delete], 200);
        }
        return response()->json(['status' => 'ok', 'response' => false], 200);
    }

    public function search_sic_document(Request $request)
    {
        $idsic_builder = $request->input('idsic_builder');

        $search = DB::table('document_builder')
            ->where('sic_builder_idsic_builder', $idsic_builder)
            ->first();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function delete_sic_document(Request $request)
    {

        $idsic_builder = $request->input('idsic_builder');

        $search = DB::table('document_builder')
            ->where('sic_builder_idsic_builder', $idsic_builder)
            ->first();
        if ($search) {
            $carpeta = public_path('/public/' . $search->url . '/' . $search->name);
            if (File::exists($carpeta)) {
                $deletedoc = DB::table('document_builder')
                    ->where('iddocument_builder', $search->iddocument_builder)
                    ->delete();

                File::delete($carpeta);
            }

        }

        $delete = DB::table('sic_builder')
            ->where('idsic_builder', $idsic_builder)
            ->delete();

        return response()->json(['status' => 'ok', 'response' => $delete], 200);

    }

    public function create_competition(Request $request)
    {
        $type                      = $request->input('type');
        $idcompetitions_builder    = $request->input('idcompetitions_builder');
        $competitions_code         = $request->input('competitions_code');
        $date_expiration           = date('Y-m-d', strtotime($request->input("date_expiration"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_expiration")));
        $builder_idbuilder         = $request->input('builder_idbuilder');
        $competition_idcompetition = $request->input('competition_idcompetition');

        if ($type == 1) {

            $insert = DB::table('competitions_builder')
                ->insert([
                    'competitions_code'         => $competitions_code,
                    'date_expiration'           => $date_expiration,
                    'builder_idbuilder'         => $builder_idbuilder,
                    'competition_idcompetition' => $competition_idcompetition,
                ]);
            return response()->json(['status' => 'ok', 'response' => true], 200);
        }
        if ($type == 2) {

            $update = DB::table('competitions_builder')
                ->where('idcompetitions_builder', $idcompetitions_builder)
                ->update([
                    'competitions_code'         => $competitions_code,
                    'date_expiration'           => $date_expiration,
                    'builder_idbuilder'         => $builder_idbuilder,
                    'competition_idcompetition' => $competition_idcompetition,
                ]);
            return response()->json(['status' => 'ok', 'response' => false], 200);
        }
    }

    public function search_competition(Request $request)
    {
        $builder_idbuilder = $request->input('builder_idbuilder');

        $search = DB::table('competitions_builder')
            ->where('builder_idbuilder', $builder_idbuilder)
            ->join('competition', 'competition.idcompetition', '=', 'competitions_builder.competition_idcompetition')
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function search_document_competition(Request $request)
    {
        $competitions_builder_idcompetitions_builder = $request->input('competitions_builder_idcompetitions_builder');

        $search = DB::table('document_builder')
            ->where('competitions_builder_idcompetitions_builder', $competitions_builder_idcompetitions_builder)
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function delete_document_competition(Request $request)
    {
        $iddocument_builder = $request->input('iddocument_builder');
        $name               = $request->input('name');
        $url                = $request->input('url');

        $carpeta = public_path('/public/' . $url . '/' . $name);

        if (File::exists($carpeta)) {

            File::delete($carpeta);
        }
        $delete = DB::table('document_builder')
            ->where('iddocument_builder', $iddocument_builder)
            ->delete();

        return response()->json(['status' => 'ok', 'response' => $delete], 200);

    }

    public function delete_competition(Request $request)
    {
        $idcompetitions_builder = $request->input('idcompetitions_builder');
        $search                 = DB::table('document_builder')
            ->where('competitions_builder_idcompetitions_builder', $idcompetitions_builder)
            ->get();

        if (count($search) > 0) {
            return response()->json(['status' => 'ok', 'response' => false], 200);
        } else {
            $delete = DB::table('competitions_builder')
                ->where('idcompetitions_builder', $idcompetitions_builder)
                ->delete();
            return response()->json(['status' => 'ok', 'response' => true], 200);
        }
    }

    public function delete(Request $request)
    {
        $idbuilder = $request->input('idbuilder');

        $search = DB::table('sic_builder')
            ->where('builder_idbuilder', $idbuilder)
            ->get();

        $search1 = DB::table('competitions_builder')
            ->where('builder_idbuilder', $idbuilder)
            ->get();

        if (count($search) > 0 || count($search1) > 0) {
            return response()->json(['status' => 'ok', 'response' => false], 200);
        } else {

            $delete = DB::table('builder')
                ->where('idbuilder', $idbuilder)
                ->delete();

            return response()->json(['status' => 'ok', 'response' => true], 200);
        }
    }
}
