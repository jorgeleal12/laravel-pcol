<?php

namespace App\Http\Controllers\Refund;

use App\Http\Controllers\Controller;
use Config;
use Facades\App\ClassPhp\Consecutive;
use Facades\App\ClassPhp\log;
use Facades\App\ClassPhp\UpdateInventary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    public function create(Request $request)
    {

        $cosec_dispatch = $request->input("head.dispatches_conse");
        $Consecutivo    = $request->input("head.consec_workI");
        $cellar         = $request->input("head.dispatches_cellar");
        $date           = $request->input("head.date");
        $destination    = $request->input("head.dispatches_destino");
        $employeeid     = $request->input("head.idemployees");
        $move           = $request->input("head.dispatches_move");
        $nMovimiento    = $request->input("head.nMovimiento");
        $iddispatches   = $request->input("head.iddispatches");

        $body = $request->input("body");

        $company  = $request->input("company");
        $contract = $request->input("contract");
        $user     = $request->input("user");

        $documento = Config::get('Config.reintegro.documento');

        $Consecutive = Consecutive::query_consecutive($company, $documento);

        $Consec = $Consecutive->consecutive;

        try {

            $head = DB::table('refund')
                ->insertGetId(['refund_move' => $move,
                    'refund_cellar'              => $cellar,
                    'refund_incharge'            => $employeeid,
                    'refund_destino'             => $destination,
                    'refund_workI'               => $Consecutivo,
                    'refund_contract'            => $contract,
                    'nMovimiento'                => $nMovimiento,
                    'refund_date'                => $date,
                    'refund_conse'               => $Consec,
                    'iddispatches'               => $iddispatches,

                ]);

            $ConseAtual = (Int) $Consec + 1;
            $update     = Consecutive::Updateconsecutive($company, $documento, $ConseAtual);

            $recorrer = RefundController::recorrer($body, $head, $company, $cellar, $user, $Consec);

            $text_insert = Config::get('Config.reintegro.text_insert');

            log::insert_log($user, $text_insert, $Consec, $company);
            $response = true;

            $update_dispatches = RefundController::update_dispatches($iddispatches);

        } catch (Exception $e) {

            $response = false;
        }

        return response()->json(['status' => 'ok', 'data' => $response, 'consecutive' => $Consec], 200);

    }

    public function recorrer($body, $head, $company, $cellar, $user, $Consec)
    {

        for ($i = 0; $i < count($body); $i++) {

            $iddetail_refund     = isset($body[$i]["iddetail_refund"]) ? $body[$i]["iddetail_refund"] : 0;
            $iddetail_dispatches = isset($body[$i]["iddetail_dispatches"]) ? $body[$i]["iddetail_dispatches"] : 0;
            $cod_mater           = isset($body[$i]["idmateriales"]) ? $body[$i]["idmateriales"] : 0;
            $quantity            = isset($body[$i]["quantity"]) ? $body[$i]["quantity"] : 0;
            $refund              = isset($body[$i]["refund"]) ? $body[$i]["refund"] : 0;
            $series              = isset($body[$i]["series"]) ? $body[$i]["series"] : 0;

            if ($iddetail_refund != 0) {

                $inser = RefundController::update_body($iddetail_refund, $iddetail_dispatches, $cod_mater, $quantity, $refund, $series, $head, $company, $cellar, $user,
                    $Consec);

            } else {

                $inser = RefundController::inser_body($iddetail_dispatches, $cod_mater, $quantity, $refund, $series, $head, $company, $cellar, $user, $Consec);

            }

        }
    }
    public function inser_body($iddetail_dispatches, $cod_mater, $quantity, $refund, $series, $head, $company, $cellar, $user, $Consec)
    {

        $tipo = Config::get('Config.reintegro.text_insert');

        try {

            $inser_bo = DB::table('detail_refund')->
                insert(['idrefund'   => $head,
                'cod_mater'          => $cod_mater,
                'quantity'           => $quantity,
                'refund'             => $refund,
                'id_detail_dispatch' => $iddetail_dispatches]);

            $UpdateInventary = UpdateInventary::AddInventary($company, $cellar, $cod_mater, $refund, $Consec, $head, $tipo);

        } catch (Exception $e) {

        }

    }

// funcion para atualizar el despacho y ingresar que ya tiene un reintegro
    public function update_dispatches($iddispatches)
    {

        $update = DB::table('dispatches')
            ->where('iddispatches', '=', $iddispatches)
            ->update([
                'refund' => 1,
            ]);
    }

    //funcion para atualizar los reintegros
    public function update(Request $request)
    {

        $cosec_dispatch  = $request->input("head.dispatches_conse");
        $Consecutivo     = $request->input("head.consec_workI");
        $cellar          = $request->input("head.dispatches_cellar");
        $date            = $request->input("head.date");
        $destination     = $request->input("head.dispatches_destino");
        $employeeid      = $request->input("head.idemployees");
        $move            = $request->input("head.dispatches_move");
        $cosec_reintegro = $request->input("head.refund_conse");
        $date            = $request->input("head.date");
        $nMovimiento     = $request->input("head.nMovimiento");
        $idrefund        = $request->input("head.idrefund");

        $body    = $request->input("body");
        $company = $request->input("company");
        $user    = $request->input("user");

        try {

            $update_head = DB::table('refund')
                ->where('idrefund', '=', $idrefund)
                ->update([
                    'nMovimiento' => $nMovimiento,
                    'refund_date' => $date,

                ]);

            $response = true;

        } catch (Exception $e) {
            $response = false;
        }

        $tipo = Config::get('Config.reintegro.text_update');

        $recorrer = RefundController::recorrer($body, $idrefund, $company, $cellar, $user, $cosec_reintegro);

        log::insert_log($user, $tipo, $cosec_reintegro, $company);

        return response()->json(['status' => 'ok', 'data' => $response], 200);

    }

    public function update_body($iddetail_refund, $iddetail_dispatches, $cod_mater, $quantity, $refund, $series, $head, $company, $cellar, $user, $Consec)
    {

        $tipo = Config::get('Config.reintegro.text_update');

        $cantidadAnterior = RefundController::search_mate($iddetail_refund);

        $ValidarC = RefundController::ValidarCatidades($tipo, $iddetail_refund, $cantidadAnterior, $refund, $cod_mater, $company, $Consec, $head, $cellar);

        try {

            $update_body = DB::table('detail_refund')
                ->where('iddetail_refund', '=', $iddetail_refund)
                ->update(['refund' => $refund,
                ]);

        } catch (Exception $e) {

        }
    }

// funcion para valoidar la cantidad anterior con la cantidad actual
    public function ValidarCatidades($tipo, $iddetail_refund, $cantidadAnterior, $quantity, $cod_mater, $company, $Consec, $head, $cellar)
    {

        $tipo = Config::get('Config.reintegro.text_update');

        // 7               8
        if ($cantidadAnterior < $quantity) {

            $cantidad = (FLOAT) $quantity - $cantidadAnterior;

            $UpdateInventary = UpdateInventary::AddInventary($company, $cellar, $cod_mater, $cantidad, $Consec, $head, $tipo);

        }
        //10             6
        if ($cantidadAnterior > $quantity) {

            $cantidad = (FLOAT) $cantidadAnterior - $quantity;

            $UpdateInventary = UpdateInventary::subtract($company, $cellar, $cod_mater, $cantidad, $Consec, $head, $tipo);

        }

    }

    public function search_date_refund(Request $request)
    {
        try {

            $start_date = $request->input("start_date");
            $end_date   = $request->input("end_date");
            $company    = $request->input("company");

            $refund = DB::table('refund')
                ->leftjoin('employees', 'refund.refund_incharge', '=', 'employees.idemployees')
                ->leftjoin('dispatches', 'dispatches.iddispatches', '=', 'refund.iddispatches')
                ->leftjoin('cellar', 'refund.refund_cellar', '=', 'cellar.idcellar')
                ->where('cellar.id_empresa', '=', $company)

                ->whereBetween('refund_date', [$start_date, $end_date])
                ->select('refund.*', 'employees.name', 'employees.last_name', 'dispatches.dispatches_conse as consec_dispatch')
                ->get();

        } catch (Exception $e) {

        }

        return response()->json(['status' => 'ok', 'refund' => $refund], 200);
    }

    public function search_refund(Request $request)
    {

        $idrefund = (int) $request->input("idrefund");

        $search_head = RefundController::search_head($idrefund);

        //$cellar = $search_head->refund_cellar;

        $search_body = RefundController::search_body($idrefund);

        return response()->json(['status' => 'ok', 'search_head' => $search_head, 'search_body' => $search_body], 200);

    }

    public function search_head($idrefund)
    {

        $head = DB::table('refund')
            ->join('employees', 'refund.refund_incharge', '=', 'employees.idemployees')
            ->join('dispatches', 'refund.iddispatches', '=', 'dispatches.iddispatches')
            ->where('idrefund', '=', $idrefund)
            ->select('refund.refund_destino as dispatches_destino',
                'refund.refund_date as date',
                'refund.nMovimiento',
                'refund.refund_cellar as dispatches_cellar',
                'refund.refund_move as dispatches_move',
                'refund.refund_workI as consec_workI',
                'dispatches.dispatches_conse',
                'refund.refund_conse',
                'refund.idrefund',
                'refund.refund_contract',
                'employees.last_name', 'employees.name', 'employees.idemployees')
            ->first();

        return $head;

    }

    public function search_body($idrefund)
    {

        $body = DB::table('refund')
            ->leftjoin('detail_refund', 'detail_refund.idrefund', '=', 'refund.idrefund')
            ->join('materiales', 'detail_refund.cod_mater', '=', 'materiales.idmateriales')
            ->join('unity', 'materiales.unity', '=', 'unity.idUnity')
        //   ->leftjoin('inventory_cellar', 'materiales.code', '=', 'inventory_cellar.cod_materia')
        //  ->leftjoin('series', 'detail_refund.iddetail_dispatches', '=', 'series.id_detaildispatche')
            ->where('refund.idrefund', '=', $idrefund)

            ->select('detail_refund.*', 'materiales.description', 'materiales.code as cod_mater', 'materiales.idmateriales', 'unity.name_Unity', DB::raw("(SELECT inventary_quantity FROM inventario_cellar where inventario_cellar.id_cellar=refund.refund_cellar and inventario_cellar.id_material=materiales.idmateriales) AS missing"))
            ->get();

        return $body;

    }

    public function search_mate($iddetail_refund)
    {

        $material = DB::table('detail_refund')
            ->where('iddetail_refund', $iddetail_refund)
            ->select('detail_refund.*')
            ->first();

        return $cantidad_atual = $material->refund;

    }

}
