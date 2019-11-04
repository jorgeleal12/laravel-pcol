<?php

namespace App\Http\Controllers\NewControllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    //

    public function create(Request $request)
    {

        $idclient    = $request->input('idclient');
        $name_client = $request->input('name_client');
        $id_client   = $request->input('id_client'); //cedula
        $email       = $request->input('email');
        $phone       = $request->input('phone');
        $state       = $request->input('state');

        if ($idclient == null) {

            $insert = DB::table('client')
                ->insertGetid([
                    'name_client' => $name_client,
                    'id_client'   => $id_client,
                    'email'       => $email,
                    'phone'       => $phone,
                    'state'       => $state,
                ]);

            return response()->json(['status' => 'ok', 'response' => true, 'id' => $insert], 200);
        } else {

            $insert = DB::table('client')
                ->where('idclient', $idclient)
                ->update([
                    'name_client' => $name_client,
                    'id_client'   => $id_client,
                    'email'       => $email,
                    'phone'       => $phone,
                    'state'       => $state,
                ]);
            return response()->json(['status' => 'ok', 'response' => false, 'id' => $idclient], 200);
        }
    }

    public function search()
    {
        $search = DB::table('client')
            ->select('client.*', 'client.state as idstate', DB::raw('(CASE WHEN client.state = "1" THEN "Activo" WHEN client.state = "2" THEN "Inactivo" ELSE "Por confirmar" END) AS state'))
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function create_account(Request $request)
    {
        $idclient_account = $request->input('idclient_account');
        $city             = $request->input('city');
        $address          = $request->input('address');
        $indications      = $request->input('indications');
        $state            = $request->input('state');
        $client_idclient  = $request->input('client_idclient');
        $number_acount    = $request->input('number_acount');

        if ($idclient_account == null) {

            $insert = DB::table('client_account')
                ->insertGetid([
                    'city'            => $city,
                    'address'         => $address,
                    'indications'     => $indications,
                    'state'           => $state,
                    'client_idclient' => $client_idclient,
                    'number_acount'   => $number_acount,
                ]);
            return response()->json(['status' => 'ok', 'response' => true, 'idaccount' => $insert], 200);
        } else {
            $update = DB::table('client_account')
                ->where('idclient_account', $idclient_account)
                ->update([
                    'city'          => $city,
                    'address'       => $address,
                    'indications'   => $indications,
                    'state'         => $state,
                    'number_acount' => $number_acount,
                ]);
            return response()->json(['status' => 'ok', 'response' => false], 200);
        }
    }

    public function search_account(Request $request)
    {
        $idclient = $request->input('idclient');

        $search = DB::table('client_account')
            ->join('municipality', 'municipality.idmunicipality', '=', 'client_account.city')
            ->where('client_idclient', $idclient)
            ->select('client_account.*', 'municipality.name_municipality as name_city', 'client_account.state as idstate', DB::raw('(CASE WHEN client_account.state = "1" THEN "Activo" WHEN client_account.state = "2" THEN "Inactivo" ELSE "Por confirmar" END) AS state'))
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);

    }

    public function delete_account(Request $request)
    {

        $idclient_account = $request->input('idclient_account');

        $delete = DB::table('client_account')
            ->where('idclient_account', $idclient_account)
            ->delete();

        return response()->json(['status' => 'ok', 'response' => $delete], 200);
    }
}
