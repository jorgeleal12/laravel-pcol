<?php

namespace App\Http\Controllers\certificado;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Certificado extends Controller
{

    public function create(Request $request)
    {
        $company    = $request->input("company");
        $contract   = $request->input("contract");
        $number_ini = $request->input("number_ini");
        $number_end = $request->input("number_end");
        $idemployee = $request->input("idemployee");

        $insert = DB::table('certificados')
            ->insertGetId([
                'number_ini' => $number_ini,
                'number_end' => $number_end,
                'idemploye'  => $idemployee,
                'company'    => $company,
                'contract'   => $contract,
            ]);

        for ($i = $number_ini; $i < $number_end + 1; $i++) {
            $insert = DB::table('asig_certificados')
                ->insertGetId([
                    'numer_cert' => $i,
                    'state'      => 0,
                    'idemploye'  => $idemployee,
                    'company'    => $company,
                    'contract'   => $contract,
                ]);

        }

        return response()->json(['status' => 'ok', 'state' => true], 200);

    }

    public function search(Request $request)
    {
        $company  = $request->input("company");
        $contract = $request->input("contrato");

        $search = DB::table('certificados')
            ->leftjoin('employees', 'certificados.idemploye', '=', 'employees.idemployees')
            ->where('company', $company)
            ->where('contract', $contract)
            ->select('certificados.*', 'employees.name', 'employees.last_name')
            ->get();

        return response()->json(['status' => 'ok', 'result' => $search], 200);

    }

}
