<?php

namespace App\Http\Controllers\NewControllers\contract;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    //

    public function create(Request $request)
    {
        $company_idcompany = $request->input("company_idcompany");
        $contract_name     = $request->input("contract_name");

        $insert = DB::table('contract')
            ->insert([
                'contract_name'     => $contract_name,
                'company_idcompany' => $company_idcompany,
            ]);

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function searchs(Request $request)
    {
        $search = DB::table('contract')
            ->join('company', 'company.idcompany', '=', 'contract.company_idcompany')
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function update(Request $request)
    {
        $contract_name = $request->input("contract_name");
        $idcontract    = $request->input("idcontract");

        $update = DB::table('contract')
            ->where('idcontract', $idcontract)
            ->update([
                'contract_name' => $contract_name,

            ]);
        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function search_contracts(Request $request)
    {

        $company = $request->input("company");

        $search = DB::table('contract')
            ->where('company_idcompany', $company)
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }
}
