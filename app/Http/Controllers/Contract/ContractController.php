<?php

namespace App\Http\Controllers\Contract;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $estado     = (INT) $request->input("data.estado");
        $nit        = (INT) $request->input("data.nit");
        $start_date = $request->input("data.start_date");
        $final_date = $request->input("data.final_date");
        $company    = (INT) $request->input("company");

        $observaciones = (String) $request->input("data.observaciones");
        $contract_name = (String) $request->input("data.contract_name");
        $director      = (String) $request->input("data.director");

        try {

            $insert = DB::table('contract')
                ->insertgetid([

                    'contract_name' => $contract_name,
                    'director'      => $director,
                    'start_date'    => $start_date,
                    'final_date'    => $final_date,
                    'estado'        => $estado,
                    'observaciones' => $observaciones,
                    'id_empresa'    => $company,
                    'nit'           => $nit,
                ]);

            $response = true;
        } catch (\Exception $e) {
            $response = false;
        }

        return response()->json(['response' => $response, 'insert' => $insert], 200);
    }

    public function update(Request $request)
    {

        $estado        = (INT) $request->input("data.estado");
        $nit           = (INT) $request->input("data.nit");
        $start_date    = $request->input("data.start_date");
        $final_date    = $request->input("data.final_date");
        $company       = (INT) $request->input("company");
        $idcontract    = (INT) $request->input("data.idcontract");
        $observaciones = (String) $request->input("data.observaciones");
        $contract_name = (String) $request->input("data.contract_name");
        $director      = (String) $request->input("data.director");

        try {

            $update = DB::table('contract')
                ->where('idcontract', '=', $idcontract)
                ->update([
                    'contract_name' => $contract_name,
                    'director'      => $director,
                    'start_date'    => $start_date,
                    'final_date'    => $final_date,
                    'estado'        => $estado,
                    'observaciones' => $observaciones,
                    'id_empresa'    => $company,
                    'nit'           => $nit,
                ]);

            $response = true;
        } catch (\Exception $e) {
            $response = false;
        }

        return response()->json(['response' => $response, 'update' => $update], 200);
    }

    public function search(Request $request)
    {
        $company  = (INT) $request->input("company");
        $contract = (INT) $request->input("contract");

        try {
            $search = DB::table('contract')
                ->where('idcontract', '=', $contract)
                ->first();
            $response = true;
        } catch (\Exception $e) {
            $response = false;
        }

        return response()->json(['response' => $response, 'search' => $search], 200);
    }

}
