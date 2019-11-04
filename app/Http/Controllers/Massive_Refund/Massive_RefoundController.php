<?php

namespace App\Http\Controllers\Massive_Refund;

use App\Http\Controllers\Controller;
use Config;
use Facades\App\ClassPhp\Consecutive;
use Facades\App\ClassPhp\log;
use Facades\App\ClassPhp\UpdateInventary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Massive_RefoundController extends Controller
{

    public function massive_refound(Request $request)
    {

        $cellar   = (INT) $request->input("cellar");
        $date_end = $request->input("date_end");
        $date_ini = $request->input("date_ini");
        $employee = $request->input("employeeid");

        $search = DB::select("select unity.name_Unity, materiales.description, materiales.code, materiales.idmateriales, employees.name,employees.last_name, dispatches.dispatches_incharge, detail_dispatches.cod_mater, SUM(detail_dispatches.quantity) as despacho,

( SELECT IFNULL(SUM(detail_refund.refund),0)  FROM refund
JOIN detail_refund ON refund.idrefund=detail_refund.idrefund WHERE refund.refund_cellar=dispatches.dispatches_cellar AND refund.refund_incharge=dispatches.dispatches_incharge AND  detail_refund.cod_mater=detail_dispatches.cod_mater)

 +
(SELECT  IFNULL(SUM(detailmassive_refund.refund),0)  FROM refund_masive JOIN detailmassive_refund ON refund_masive.idrefund_masive=detailmassive_refund.idrefund_masive WHERE dispatches.dispatches_cellar=refund_masive.refund_masive_cellar  and refund_masive.refund_masive_incharge=dispatches.dispatches_incharge)as reintegrosmasivos

FROM dispatches

JOIN detail_dispatches ON dispatches.iddispatches=detail_dispatches.dispatches
JOIN employees ON dispatches.dispatches_incharge=employees.idemployees
JOIN materiales ON materiales.idmateriales=detail_dispatches.cod_mater
JOIN unity on materiales.unity=unity.idUnity

where   dispatches.dispatches_cellar='$cellar' and dispatches.dispatches_incharge='$employee'  and dispatches.dispatches_date between '$date_ini ' and '$date_end'    GROUP BY detail_dispatches.cod_mater");

        return response()->json(['status' => 'ok', 'search' => $search], 200);

    }

    public function insert(Request $request)
    {

        $date         = $request->input("head.refund_masive_date");
        $cellar       = (INT) $request->input("head.refund_masive_cellar");
        $employee     = (INT) $request->input("head.refund_masive_incharge");
        $contract     = (INT) $request->input("contract");
        $refundm_head = (INT) $request->input("head.idrefund_masive");
        $body         = $request->input("body");

        $company = (INT) $request->input("company");
        $user    = (INT) $request->input("user");

        $documento = Config::get('Config.reintegromasivos.documento');

        $Consecutive = Consecutive::query_consecutive($company, $documento);
        $Consec      = $Consecutive->consecutive;

        try {

            $refundm_head = DB::table('refund_masive')
                ->insertGetId([
                    'refund_masive_cellar'   => $cellar,
                    'refund_masive_incharge' => $employee,
                    'refund_masive_date'     => $date,
                    'contract'               => $contract,
                    'refund_masive_conse'    => $Consec,
                ]);

            $recorrer = Massive_RefoundController::recorrer($body, $refundm_head, $Consec, $cellar, $company);

            $ConseAtual = (Int) $Consec + 1;

            $update = Consecutive::Updateconsecutive($company, $documento, $ConseAtual);

            $text_insert = Config::get('Config.reintegromasivos.text_insert');

            log::insert_log($user, $text_insert, $Consec, $company);

            $response = true;

        } catch (\Exception $e) {

            $response = false;
        }
        return response()->json(['status' => 'ok', 'data' => $response, 'consecutive' => $Consec, 'idrefund_masive' => $refundm_head], 200);
    }

    public function recorrer($body, $refundm_head, $Consec, $cellar, $company)
    {

        for ($i = 0; $i < count($body); $i++) {

            $cod_mater       = isset($body[$i]["idmateriales"]) ? $body[$i]["idmateriales"] : 0;
            $iddetail_refund = isset($body[$i]["iddetail_refund"]) ? $body[$i]["iddetail_refund"] : 0;
            $refund          = isset($body[$i]["refund"]) ? $body[$i]["refund"] : 0;

            if ($iddetail_refund != 0) {

                $update_body = Massive_RefoundController::update_body($cod_mater, $iddetail_refund, $refund, $refundm_head, $Consec, $cellar, $company);

            } else {

                $insert_body = Massive_RefoundController::insert_body($cod_mater, $iddetail_refund, $refund, $refundm_head, $Consec, $cellar, $company);
            }

        }

    }

    public function insert_body($cod_mater, $idrefund_masive, $refund, $refundm_head, $Consec, $cellar, $company)
    {
        $text_insert = Config::get('Config.reintegromasivos.text_insert');

        $insert_massive = DB::table('detailmassive_refund')
            ->insert(['idrefund_masive' => $refundm_head,
                'refund'                    => $refund,
                'cod_mater'                 => $cod_mater,

            ]);
        $UpdateInventary = UpdateInventary::AddInventary($company, $cellar, $cod_mater, $refund, $idrefund_masive, $Consec, $text_insert);
    }

//funcion para atualizar la cabezera del reintegro
    public function update_head(Request $request)
    {
        $date            = $request->input("head.refund_masive_date");
        $cellar          = (INT) $request->input("head.refund_masive_cellar");
        $employee        = (INT) $request->input("head.refund_masive_incharge");
        $idrefund_masive = (INT) $request->input("head.idrefund_masive");
        $Consec          = (INT) $request->input("head.refund_masive_conse");
        $body            = $request->input("body");

        $company = (INT) $request->input("company");

        $user = (INT) $request->input("user");

        $recorrer = Massive_RefoundController::recorrer($body, $idrefund_masive, $Consec, $cellar, $company);

        try {

            $update_head = DB::table('refund_masive')
                ->where('idrefund_masive', '=', $idrefund_masive)
                ->update([
                    'refund_masive_date' => $date,
                ]);

            log::insert_log($user, 'ATUALIZO REINTEGRO MASIVO', $Consec, $company);

            $result = true;
        } catch (\Exception $e) {
            $result = false;
        }

        return response()->json(['status' => 'ok', 'result' => $result], 200);
    }

    // funcion para atualizar el cuerpo del reintegro
    public function update_body($cod_mater, $idrefund_masive, $refund, $refundm_head, $Consec, $cellar, $company)
    {
        $tipo             = "ATUALIZA REINTEGRO MASIVO";
        $cantidadAnterior = Massive_RefoundController::search_detail_unity($idrefund_masive, $company);
        $Update_massive   = DB::table('detailmassive_refund')
            ->where('iddetail_refund', '=', $idrefund_masive)
            ->update(['refund' => $refund,

            ]);

        if ($cantidadAnterior < $refund) {

            $cantidad = (FLOAT) $refund - $cantidadAnterior;

            $UpdateInventary = UpdateInventary::AddInventary($company, $cellar, $cod_mater, $cantidad, $refundm_head, $Consec, $tipo);

        }
        //10             6
        if ($cantidadAnterior > $refund) {

            $cantidad = (FLOAT) $cantidadAnterior - $refund;

            $UpdateInventary = UpdateInventary::subtract($company, $cellar, $cod_mater, $cantidad, $refundm_head, $Consec, $tipo);

        }

    }

// funcion para buscar un registro en el cuerpo del reintegro
    public function search_detail_unity($idrefund_masive, $company)
    {

        $search = DB::table('detailmassive_refund')
            ->where('iddetail_refund', '=', $idrefund_masive)
            ->select('detailmassive_refund.refund')
            ->first();

        return $search->refund;
    }

    public function search_massive(Request $request)
    {

        $star_date = $request->input("star_date");
        $end_date  = $request->input("end_date");

        try {

            $search_massive = DB::table('refund_masive')
                ->JOIN('employees', 'employees.idemployees', '=', 'refund_masive.refund_masive_incharge')
                ->JOIN('cellar', 'cellar.idcellar', '=', 'refund_masive.refund_masive_cellar')
                ->whereBetween('refund_masive_date', [$star_date, $end_date])

                ->SELECT('employees.name  as name_employee', 'employees.last_name', 'cellar.name', 'refund_masive.*')
                ->get();

        } catch (\Exception $e) {

            $search_massive = false;

        }
        return response()->json(['status' => 'ok', 'search_massive' => $search_massive], 200);
    }
    //

    public function search_refund_massive(Request $request)
    {

        $idrefund_masive = $request->input("idrefund_masive");
        $contract        = $request->input("contract");
        $company         = $request->input("company");

        $search = DB::TABLE('refund_masive')
            ->join('employees', 'refund_masive.refund_masive_incharge', '=', 'employees.idemployees')
            ->where('idrefund_masive', '=', $idrefund_masive)
            ->select('refund_masive.*', 'employees.name', 'employees.last_name')
            ->first();

        $employee      = $search->refund_masive_incharge;
        $cellar        = $search->refund_masive_cellar;
        $search_detail = Massive_RefoundController::search_detail($idrefund_masive, $company, $employee, $cellar);

        return response()->json(['status' => 'ok', 'search' => $search, 'search_detail' => $search_detail], 200);

    }

    public function search_detail($idrefund_masive, $company, $employee, $cellar)
    {

        $search_detail_massive = DB::select("SELECT D.cod_mater,D.refund,D.iddetail_refund,unity.name_Unity, materiales.description, materiales.code as cod_mater,materiales.idmateriales,

        (SELECT IFNULL(SUM(detail_dispatches.quantity),0) FROM dispatches JOIN detail_dispatches ON dispatches.iddispatches =detail_dispatches.dispatches WHERE detail_dispatches.cod_mater =D.cod_mater AND dispatches.dispatches_incharge='$employee' AND  dispatches.dispatches_cellar='$cellar') as despacho,


       ( SELECT IFNULL(SUM(detail_refund.refund),0)  FROM refund
        JOIN detail_refund ON refund.idrefund=detail_refund.idrefund WHERE refund.refund_cellar='$cellar' AND refund.refund_incharge='$employee' AND detail_refund.cod_mater=D.cod_mater) +

        (SELECT  IFNULL(SUM(detailmassive_refund.refund),0)  FROM refund_masive JOIN detailmassive_refund ON refund_masive.idrefund_masive=detailmassive_refund.idrefund_masive WHERE refund_masive.refund_masive_cellar='$cellar'  and refund_masive.refund_masive_incharge='$employee' and detailmassive_refund.cod_mater= D.cod_mater)as reintegrosmasivos


            FROM


            detailmassive_refund  D


JOIN materiales ON materiales.idmateriales=D.cod_mater
JOIN unity on materiales.unity=unity.idUnity


             WHERE D.idrefund_masive='$idrefund_masive'  ");

        return $search_detail_massive;

    }
}
