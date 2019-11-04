<?php

namespace App\Http\Controllers\NewControllers\administration\photos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PhotosController extends Controller
{
    //
    public function create(Request $request)
    {
        $photo    = $request->input("photo");
        $quantity = $request->input("quantity");
        $min      = $request->input("min");

        $insert = DB::table('photos')
            ->insert([
                'name_photo' => $photo,
                'quantity'   => $quantity,
                'min'        => $min,

            ]);

        return response()->json(['status' => 'ok', 'reponse' => true], 200);
    }

    public function update(Request $request)
    {
        $idphotos = $request->input("idphotos");
        $photo    = $request->input("photo");
        $quantity = $request->input("quantity");
        $min      = $request->input("min");

        $insert = DB::table('photos')
            ->where('idphotos', $idphotos)
            ->update([
                'name_photo' => $photo,
                'quantity'   => $quantity,
                'min'        => $min,

            ]);

        return response()->json(['status' => 'ok', 'reponse' => true], 200);
    }

    public function delete(Request $request)
    {
        $idphotos = $request->input("idphotos");

        $insert = DB::table('photos')
            ->where('idphotos', $idphotos)
            ->delete();

        return response()->json(['status' => 'ok', 'reponse' => true], 200);
    }

    public function search(Request $request)
    {
        $serach = DB::table('photos')
            ->get();

        return response()->json(['status' => 'ok', 'reponse' => $serach], 200);
    }
}
