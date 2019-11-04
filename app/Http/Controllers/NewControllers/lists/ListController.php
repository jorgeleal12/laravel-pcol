<?php

namespace App\Http\Controllers\NewControllers\lists;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListController extends Controller
{
    //

    public function list_eps(Request $request)
    {

        $search = DB::table('eps')
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function list_arl(Request $request)
    {

        $search = DB::table('arl')
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function list_pension(Request $request)
    {

        $search = DB::table('pension')
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function list_service(Request $request)
    {

        $search = DB::table('type_service')
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function list_photos(Request $request)
    {

        $search = DB::table('photos')
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function list_municipality(Request $request)
    {

        $id_departamento = $request->input("id_departamento");
        $search          = DB::table('municipality')
            ->where('id_departament', $id_departamento)
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function list_type_network(Request $request)
    {
        $type = $request->input("type");

        $search = DB::table('type_network')
            ->where('type_service_idtype_service', $type)
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function list_photos_service(Request $request)
    {
        $type_network_idtype_network = $request->input("type_network_idtype_network");

        $search = DB::table('photos_service')
            ->join('photos', 'photos.idphotos', 'photos_service.photos_idphotos')
            ->where('type_network_idtype_network', $type_network_idtype_network)
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }
}
