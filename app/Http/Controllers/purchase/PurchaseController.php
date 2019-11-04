<?php

namespace App\Http\Controllers\purchase;

use App\Http\Controllers\Controller;
use Config;
use Facades\App\ClassPhp\Consecutive;
use Facades\App\ClassPhp\log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{

    //funcion para insertar la orden de compra
    public function create(Request $request)
    {

        $provider   = (int) $request->input("head.providers_name.idproviders");
        $id_company = (int) $request->input("company");
        $contract   = (int) $request->input("contract");

        $cellar       = (int) $request->input("head.purchases_cellar");
        $state_purc   = (int) $request->input("head.purchases_state_purc");
        $date         = $request->input("head.purchases_date");
        $observations = (String) mb_strtoupper($request->input("head.purchases_observations"));
        $deliver_date = $request->input("head.purchases_deliver_date");
        $body         = $request->input("body");

        $identification = $request->input("user");

        $bodydata = $body;

        $documento   = Config::get('Config.compras.documento');
        $Consecutive = Consecutive::query_consecutive($id_company, 'COM');

        $Insert = DB::table('purchases')->insertGetId(
            ['consecutive_purc'      => $Consecutive->consecutive,
                'provider'               => $provider,
                'purchases_cellar'       => $cellar,
                'purchases_state_purc'   => $state_purc,
                'purchases_date'         => $date,
                'purchases_observations' => $observations,
                'purchases_deliver_date' => $deliver_date,
                'purchases_id_contract'  => $contract]
        );

        if (!$Insert) {
            $response   = false;
            $ConseAtual = false;

        } else {
            $response   = true;
            $ConseAtual = (Int) $Consecutive->consecutive + 1;

            $createbody = PurchaseController::rrecorrerbody($bodydata, $Consecutive->consecutive, $id_company, $Insert);

            $update = Consecutive::Updateconsecutive($id_company, $documento, $ConseAtual);

            $text_insert = Config::get('Config.compras.text_insert');

            log::insert_log($identification, $text_insert, $Consecutive->consecutive, $id_company);
            $purchases = PurchaseController::search_purchases_insert($Insert);

        }

        return response()->json(['status' => 'ok', 'data' => $response, 'consecutive_purc' => $Consecutive->consecutive, 'purchases' => $purchases], 200);

    }

//funcion para consultar las ordenes de compras por fecha

    public function search_order(Request $request)
    {
        $start_date = $request->input("start_date");
        $end_date   = $request->input("end_date");
        $company    = $request->input("company");

        $purchases = DB::table('purchases')
            ->join('contract', 'purchases.purchases_id_contract', '=', 'contract.idcontract')
            ->join('business', 'contract.id_empresa', '=', 'business.idbusiness')
            ->join('providers', 'purchases.provider', '=', 'providers.idproviders')
            ->join('state_moves', 'purchases.purchases_state_purc', '=', 'state_moves.idstate_moves')
            ->select('purchases.*', 'providers.providers_name', 'state_moves.name_moves')
            ->whereBetween('purchases_date', [$start_date, $end_date])
            ->where('idbusiness', '=', $company)
            ->get();

        return response()->json(['status' => 'ok', 'purchases' => $purchases], 200);
    }

    public function search_purchases_insert($idpurchases)
    {

        $purchases = DB::table('purchases')
            ->join('providers', 'purchases.provider', '=', 'providers.idproviders')
            ->where('idpurchases', $idpurchases)
            ->select('purchases.*', 'providers.providers_name')
            ->first();

        return $purchases;

    }

//funcion para consultar una orden de compra completa
    public function search_purchases(Request $request)
    {

        $idpurchases = $request->input("idpurchases");

        $purchases = DB::table('purchases')
            ->join('providers', 'purchases.provider', '=', 'providers.idproviders')
            ->where('idpurchases', $idpurchases)
            ->select('purchases.*', 'providers.providers_name')
            ->first();

        if (!$purchases) {

            $response = false;

        } else {

            $response     = true;
            $resul_detail = PurchaseController::search_detail($idpurchases);

        }

        return response()->json(['status' => 'ok', 'data' => $response, 'purchases' => $purchases, 'detail_purchases' => $resul_detail, 'move' => 1], 200);

    }

    public function search_detail($idpurchases)
    {
        $detail_purchases = DB::table('detail_purchases')
            ->join('materiales', 'detail_purchases.cod_material', '=', 'materiales.idmateriales')
            ->join('unity', 'materiales.unity', '=', 'unity.idUnity')
            ->where('id_purchases', $idpurchases)

            ->select('detail_purchases.*', 'materiales.description', 'materiales.code', 'detail_purchases.requested_amount as request_amount', 'detail_purchases.vlriva as vlriva', 'detail_purchases.discount as supply_discount'

                , 'detail_purchases.iva as supply_iva', 'materiales.code as cod_mater', 'detail_purchases.unit_value as supply_vlru', 'unity.name_Unity')
            ->get();

        return $detail_purchases;
    }

// funcion para atualizar las ordenes de compras
    public function update(Request $request)
    {

        $provider     = (int) $request->input("head.provider");
        $idpurchases  = (int) $request->input("head.idpurchases");
        $consecutive  = (int) $request->input("head.consecutive_purc");
        $id_company   = (int) $request->input("head.id_company");
        $cellar       = (int) $request->input("head.purchases_cellar");
        $state_purc   = (int) $request->input("head.purchases_state_purc");
        $date         = $request->input("head.purchases_date");
        $observations = (String) $request->input("head.purchases_observations");
        $deliver_date = $request->input("head.purchases_deliver_date");

        $body           = $request->input("body");
        $identification = $request->input("user");

        $bodydata = $body;

        try {

            $update = DB::table('purchases')
                ->where('idpurchases', $idpurchases)
                ->update(
                    ['provider' => $provider, 'purchases_cellar' => $cellar, 'purchases_state_purc' => $state_purc, 'purchases_date' => $date, 'purchases_observations' => $observations, 'purchases_deliver_date' => $deliver_date, 'purchases_cellar' => $cellar]
                );

            $response = true;

        } catch (\Exception $e) {

            $response = false;
        }

        PurchaseController::rrecorrerbody($bodydata, $consecutive, $id_company, $idpurchases);
        $detail_purchases = PurchaseController::search_detail($idpurchases);
        log::insert_log($identification, 'ATUALIZO ORDEN DE COMPRA', $consecutive, $id_company);

        return response()->json(['status' => 'ok', 'data' => $response, 'detail_purchases' => $detail_purchases], 200);

    }

// funcion para recorrer los datos del body
    public function rrecorrerbody($bodydata, $consecutive, $id_company, $Insert)
    {

        for ($i = 0; $i < count($bodydata); $i++) {

            $cod_mater         = isset($bodydata[$i]['idmateriales']) ? $bodydata[$i]['idmateriales'] : null;
            $request_amount    = isset($bodydata[$i]["request_amount"]) ? $bodydata[$i]["request_amount"] : null;
            $supply_vlru       = isset($bodydata[$i]["supply_vlru"]) ? $bodydata[$i]["supply_vlru"] : null;
            $supply_discount   = isset($bodydata[$i]["supply_discount"]) ? $bodydata[$i]["supply_discount"] : null;
            $supply_iva        = isset($bodydata[$i]["supply_iva"]) ? $bodydata[$i]["supply_iva"] : null;
            $vlriva            = isset($bodydata[$i]["vlriva"]) ? $bodydata[$i]["vlriva"] : null;
            $subtotal          = isset($bodydata[$i]["subtotal"]) ? $bodydata[$i]["subtotal"] : null;
            $total             = isset($bodydata[$i]["total"]) ? $bodydata[$i]["total"] : null;
            $iddetail_shopping = isset($bodydata[$i]["iddetail_shopping"]) ? $bodydata[$i]["iddetail_shopping"] : null;

            if ($iddetail_shopping != null) {

                $updatebody = PurchaseController::updatebody($iddetail_shopping, $consecutive, $id_company, $cod_mater, $request_amount, $supply_vlru, $supply_discount, $supply_iva, $vlriva, $subtotal, $total, $Insert);

            } else {

                $createbody = PurchaseController::createbody($iddetail_shopping, $consecutive, $id_company, $cod_mater, $request_amount, $supply_vlru, $supply_discount, $supply_iva, $vlriva, $subtotal, $total, $Insert);

            }

        }
    }

// funcion para insertar los datos en el body
    public function updatebody($iddetail_shopping, $consecutive, $id_company, $cod_mater, $request_amount, $supply_vlru, $supply_discount, $supply_iva, $vlriva, $subtotal, $total, $Insert)
    {

        if ($iddetail_shopping != null) {

            try {

                $detail_purchases = DB::table('detail_purchases')
                    ->where('iddetail_shopping', $iddetail_shopping)
                    ->update(
                        [
                            'requested_amount' => $request_amount,
                            'unit_value'       => $supply_vlru,
                            'discount'         => $supply_discount,
                            'iva'              => $supply_iva,
                            'vlriva'           => $vlriva,
                            'subtotal'         => $subtotal,
                            'total'            => $total,

                        ]
                    );
            } catch (\Exception $e) {

                die();
            }
        }

    }

// funcion para crear los materiales en el body
    public function createbody($iddetail_shopping, $consecutive, $id_company, $cod_mater, $request_amount, $supply_vlru, $supply_discount, $supply_iva, $vlriva, $subtotal, $total, $Insert)
    {

        if ($cod_mater != null) {
            $inserbody = DB::table('detail_purchases')->insert(
                [
                    'id_purchases'     => $Insert,
                    'cod_material'     => $cod_mater,
                    'requested_amount' => $request_amount,
                    'unit_value'       => $supply_vlru,
                    'discount'         => $supply_discount,
                    'iva'              => $supply_iva,
                    'vlriva'           => $vlriva,
                    'subtotal'         => $subtotal,
                    'total'            => $total,

                ]
            );

        }

    }

    public function delete(Request $request)
    {

        $iddetail_shopping = (int) $request->input("iddetail_shopping");
        $identification    = $request->input("user");

        try {

            $delete = DB::table('detail_purchases')
                ->where('iddetail_shopping', '=', $iddetail_shopping)
                ->delete();

            $response = true;

            log::insert_log($identification, 'ELIMINAR COMPRAS', $iddetail_shopping, 0);

        } catch (Exception $e) {
            $response = false;

        }

        return response()->json(['status' => 'ok', 'data' => $response], 200);
    }
}
