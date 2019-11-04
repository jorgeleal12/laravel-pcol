<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    //

    public function create(Request $request)
    {

        $company   = $request->input("company");
        $contract  = $request->input("contract");
        $cedula    = $request->input("data.cedula");
        $name      = $request->input("data.name");
        $last_name = $request->input("data.last_name");
        $address   = $request->input("data.address");
        $phone     = $request->input("data.phone");
        $cel       = $request->input("data.cel");
        $city      = $request->input("data.city");
        $barrio    = $request->input("data.barrio");

        $search = DB::table('client')
            ->where('cedula', $cedula)
            ->first();

        if ($search) {
            return response()->json(['status' => 'ok', 'state' => false], 200);
        }

        $insert = DB::table('client')
            ->insertGetid([
                'cedula'    => $cedula,
                'Name'      => $name,
                'Last_name' => $last_name,
                'phone'     => $phone,
                'cel'       => $cel,
                'city'      => $city,
                'barrio'    => $barrio,
                'company'   => $company,
            ]);

        return response()->json(['status' => 'ok', 'state' => true, 'idclient' => $insert], 200);

    }
}
