<?php

namespace App\Http\Controllers\NewControllers\company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function create(Request $request)
    {
        $company_name    = $request->input("company_name");
        $company_address = $request->input("company_address");
        $company_nit     = $request->input("company_nit");

        $insert = DB::table('company')
            ->insert([
                'company_name'    => $company_name,
                'company_address' => $company_address,
                'company_nit'     => $company_nit,
            ]);

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function searchs(Request $request)
    {
        $searchs = DB::table('company')
            ->get();

        return response()->json(['status' => 'ok', 'response' => true, 'result' => $searchs], 200);

    }

    public function update(Request $request)
    {
        $company_name    = $request->input("company_name");
        $company_address = $request->input("company_address");
        $company_nit     = $request->input("company_nit");
        $idcompany       = $request->input("idcompany");

        $update = DB::table('company')
            ->where('idcompany', $idcompany)
            ->update([
                'company_name'    => $company_name,
                'company_address' => $company_address,
                'company_nit'     => $company_nit,

            ]);
        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

}
