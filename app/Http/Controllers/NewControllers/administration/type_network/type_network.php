<?php

namespace App\Http\Controllers\NewControllers\administration\type_network;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class type_network extends Controller
{
    //
    public function create(Request $request)
    {

        $name_network                = $request->input("name_network");
        $type_service_idtype_service = $request->input("type_service_idtype_service");

        $insert = DB::table('type_network')
            ->insert([
                'name_network'                => $name_network,
                'type_service_idtype_service' => $type_service_idtype_service,
            ]);

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function update(Request $request)
    {

        $idtype_network              = $request->input("idtype_network");
        $name_network                = $request->input("name_network");
        $type_service_idtype_service = $request->input("type_service_idtype_service");

        $insert = DB::table('type_network')
            ->where('idtype_network', $idtype_network)
            ->update([
                'name_network'                => $name_network,
                'type_service_idtype_service' => $type_service_idtype_service,
            ]);

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function delete(Request $request)
    {

        $idtype_network = $request->input("idtype_network");

        $insert = DB::table('type_network')
            ->where('idtype_network', $idtype_network)
            ->delete();

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function search()
    {

        $search = DB::table('type_network')
            ->join('type_service', 'type_service.idtype_service', '=', 'type_network.type_service_idtype_service')
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function create_photo(Request $request)
    {
        $photos_idphotos             = $request->input("photos_idphotos");
        $type_network_idtype_network = $request->input("type_network_idtype_network");

        $insert = DB::table('photos_service')
            ->insert([
                'photos_idphotos'             => $photos_idphotos,
                'type_network_idtype_network' => $type_network_idtype_network,

            ]);

        $search = DB::table('photos_service')
            ->where('type_network_idtype_network', $type_network_idtype_network)
            ->join('photos', 'photos.idphotos', '=', 'photos_service.photos_idphotos')
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function search_photo(Request $request)
    {
        $type_network_idtype_network = $request->input("type_network_idtype_network");

        $search = DB::table('photos_service')
            ->where('type_network_idtype_network', $type_network_idtype_network)
            ->join('photos', 'photos.idphotos', '=', 'photos_service.photos_idphotos')
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function delete_photo(Request $request)
    {
        $idphotos_service = $request->input("idphotos_service");

        $delete = DB::table('photos_service')
            ->where('idphotos_service', $idphotos_service)
            ->delete();

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }
}
