<?php

namespace App\Http\Controllers\Income;

use App\Http\Controllers\Controller;
use Config;
use Facades\App\ClassPhp\Consecutive;
use Facades\App\ClassPhp\log;
use Facades\App\ClassPhp\UpdateInventary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomeController extends Controller
{
    //

    // FUNCION PARA INSERTAR EL HEAD DEL INGRESO
    public function create(Request $request)
    {

        $consecutive_purc     = $request->input("head.consecutive_purc");
        $cellar               = $request->input("head.purchases_cellar");
        $idincome             = $request->input("head.idincome");
        $income_date          = $request->input("head.purchases_date");
        $income_date_delivery = $request->input("head.purchases_deliver_date");

        $income_invoice      = $request->input("head.income_invoice");
        $income_observations = $request->input("head.income_observations");
        $income_remission    = $request->input("head.income_remission");
        $income_state        = $request->input("head.purchases_state_purc");
        $idpurchases         = $request->input("head.idpurchases");

        $income_move = $request->input("head.income_move");

        $p              = $request->input("head.provider");
        $p1             = $request->input("head.providers_name.idproviders");
        $identification = $request->input("user");
        $contract       = $request->input("contract");
        $id_company     = $request->input("company");
        $provider       = isset($p) ? $p : $p1;
        $idpurchases1   = isset($idpurchases) ? $idpurchases : 0;
        $body           = $request->input("body");

        try {
            $documento   = Config::get('Config.ingresos.documento');
            $Consecutive = Consecutive::query_consecutive($id_company, $documento);

            $income = DB::table('income')->insertGetId(
                ['income_conse'        => $Consecutive->consecutive,

                    'income_cellar'        => $cellar,
                    'income_idpurchases'   => $idpurchases,
                    'income_date'          => $income_date,
                    'income_date_delivery' => $income_date_delivery,
                    'income_state'         => $income_state,
                    'income_idpurchases'   => $idpurchases,
                    'income_move'          => $income_move,
                    'income_invoice'       => $income_invoice,
                    'income_remission'     => $income_remission,
                    'income_observations'  => $income_observations,
                    'income_idprovider'    => $provider,
                    'consecutive_purc'     => $consecutive_purc,
                    'income_idcontract'    => $contract]
            );

            $response   = true;
            $ConseAtual = (Int) $Consecutive->consecutive + 1;
            $update     = Consecutive::Updateconsecutive($id_company, $documento, $ConseAtual);

        } catch (\Exception $e) {

            $response = false;
        }

        $recorrer = IncomeController::recorrer($body, $id_company, $Consecutive->consecutive, $income, $cellar, $idpurchases);

        $text_insert = Config::get('Config.ingresos.text_insert');

        log::insert_log($identification, $text_insert, $Consecutive->consecutive, $id_company);

        if ($idpurchases1 != 0) {
            $updatepurchase = DB::table('purchases')

                ->where('idpurchases', '=', $idpurchases)
                ->update([
                    'purchases_state_purc' => $income_state,
                ]);

        }

        return response()->json(['status' => 'ok', 'data' => $response, 'income_conse' => $Consecutive->consecutive, 'idincome' => $income], 200);
    }

// FUNCION PARA RECORRER EL DATA BODY
    public function recorrer($bodyda, $id_company, $Consecutive, $income, $cellar, $idpurchases)
    {

        for ($i = 0; $i < count($bodyda); $i++) {

            $request_amount    = isset($bodyda[$i]["requested_amount"]) ? $bodyda[$i]["requested_amount"] : 0;
            $amount_receipt    = isset($bodyda[$i]["ceceived_amount"]) ? $bodyda[$i]["ceceived_amount"] : 0;
            $cod_material      = isset($bodyda[$i]["cod_material"]) ? $bodyda[$i]["cod_material"] : $bodyda[$i]["idmateriales"];
            $unit_value        = isset($bodyda[$i]["unit_value"]) ? $bodyda[$i]["unit_value"] : 0;
            $iva               = isset($bodyda[$i]["iva"]) ? $bodyda[$i]["iva"] : 0;
            $discount          = isset($bodyda[$i]["discount"]) ? $bodyda[$i]["discount"] : 0;
            $vlriva            = isset($bodyda[$i]["vlriva"]) ? $bodyda[$i]["vlriva"] : 0;
            $subtotal          = isset($bodyda[$i]["isubtotal"]) ? $bodyda[$i]["isubtotal"] : $bodyda[$i]["subtotal"];
            $total             = isset($bodyda[$i]["itotal"]) ? $bodyda[$i]["itotal"] : $bodyda[$i]["total"];
            $iddetail_shopping = isset($bodyda[$i]["iddetail_shopping"]) ? $bodyda[$i]["iddetail_shopping"] : 0;
            $idincome_details  = isset($bodyda[$i]["idincome_details"]) ? $bodyda[$i]["idincome_details"] : 0;

            if ($idincome_details != 0) {

                $updatebody = IncomeController::updatebody($cod_material, $iddetail_shopping, $request_amount, $amount_receipt, $unit_value, $discount, $iva, $vlriva, $subtotal, $total

                    , $Consecutive, $id_company, $cellar, $income, $idincome_details, $idpurchases);

            } else {

                if ($cod_material != 0) {

                    $insertbody = IncomeController::createbody($cod_material, $iddetail_shopping, $request_amount, $amount_receipt, $unit_value, $discount, $iva, $vlriva, $subtotal, $total

                        , $Consecutive, $id_company, $cellar, $income, $idpurchases);

                }

            }
        }
    }

// funcion para insertar el material de cada ingreso
    public function createbody($cod_material, $iddetail_shopping, $request_amount, $amount_receipt, $unit_value, $discount, $iva, $vlriva, $subtotal, $total

        , $Consecutive, $id_company, $cellar, $income, $idpurchases) {
        $tipo          = 'INGRESO';
        $detail_income = DB::table('income_details')->insert(
            ['idincome'         => $income,

                'cod_mater'         => $cod_material,
                'unit_value'        => $unit_value,
                'discount'          => $discount,
                'iva'               => $iva,
                'vlriva'            => $vlriva,
                'subtotal'          => $subtotal,
                'total'             => $total,
                'requested_amount'  => $request_amount,
                'ceceived_amount'   => $amount_receipt,
                'iddetail_shopping' => $iddetail_shopping,
                'id_purchase'       => $idpurchases,

            ]

        );

        $text_insert     = Config::get('Config.ingresos.text_insert');
        $UpdateInventary = UpdateInventary::AddInventary($id_company, $cellar, $cod_material, $amount_receipt, $Consecutive, $income, $text_insert);

    }

// funcion para buscar por facha los ingresos
    public function search_date(Request $request)
    {
        try {

            $start_date = $request->input("start_date");
            $end_date   = $request->input("end_date");
            $company    = $request->input("company");

            $income = DB::table('income')
                ->join('contract', 'income.income_idcontract', '=', 'contract.idcontract')
                ->join('providers', 'income.income_idprovider', '=', 'providers.idproviders')
                ->join('state_moves', 'income.income_state', '=', 'state_moves.idstate_moves')
                ->select('income.*', 'providers.providers_name', 'state_moves.name_moves')
                ->where('contract.id_empresa', '=', $company)
                ->whereBetween('income_date', [$start_date, $end_date])
                ->get();

        } catch (Exception $e) {

        }

        return response()->json(['status' => 'ok', 'income' => $income], 200);
    }

// funcion para buscar la cabezara del ingreso
    public function search(Request $request)
    {

        $idincome = $request->input("idincome");

        try {

            $income = DB::table('income')
                ->join('providers', 'income.income_idprovider', '=', 'providers.idproviders')
                ->where('idincome', $idincome)
                ->select('income.income_move',
                    'income.income_cellar as purchases_cellar',
                    'income.income_date as purchases_date',
                    'income.income_state as purchases_state_purc',
                    'providers.providers_name',
                    'income.income_date_delivery as purchases_deliver_date',
                    'income.income_invoice',
                    'income.income_remission',
                    'income.income_observations',
                    'income.income_idprovider',
                    'income.income_conse',
                    'income.consecutive_purc',
                    'income.idincome',
                    'income.income_idcontract')
                ->first();

            $response = true;

            $resul_detail = IncomeController::search_detail($idincome);

        } catch (Exception $e) {

            $response = false;
        }

        return response()->json(['status' => 'ok', 'data' => $response, 'income' => $income, 'detail_income' => $resul_detail], 200);

    }

// funcion para buscar los detalles del ingreso o el body
    public function search_detail($idincome)
    {

        try {
            $detail_purchases = DB::table('income_details')
                ->join('materiales', 'income_details.cod_mater', '=', 'materiales.idmateriales')
                ->join('unity', 'materiales.unity', '=', 'unity.idUnity')
                ->where('idincome', $idincome)
                ->select('income_details.idincome_details',
                    'income_details.idincome_details',
                    'income_details.iddetail_shopping',
                    'income_details.requested_amount',
                    'income_details.ceceived_amount',
                    'income_details.unit_value',
                    'income_details.discount',
                    'income_details.iva',
                    'income_details.vlriva',
                    'income_details.subtotal as isubtotal',
                    'income_details.total as itotal',
                    'materiales.code',
                    'materiales.code as cod_mater',
                    'materiales.description',
                    'materiales.idmateriales'
                    , 'unity.name_Unity')
                ->get();

            return $detail_purchases;
        } catch (Exception $e) {

        }

    }

    public function search_detailPurchase($idpurchases)
    {
        $detail_purchases = DB::table('detail_purchases')
            ->join('materiales', 'detail_purchases.cod_material', '=', 'materiales.idmateriales')
            ->where('id_purchases', $idpurchases)

            ->select('detail_purchases.*', 'materiales.description', 'materiales.code', 'detail_purchases.requested_amount as request_amount', 'detail_purchases.vlriva as vlriva', 'detail_purchases.discount as supply_discount'

                , 'detail_purchases.iva as supply_iva', 'materiales.code as cod_mater', 'detail_purchases.unit_value as supply_vlru')
            ->get();

        return $detail_purchases;
    }

    public function update(Request $request)
    {

        $consecutive_purc     = $request->input("head.consecutive_purc");
        $cellar               = $request->input("head.purchases_cellar");
        $idincome             = $request->input("head.idincome");
        $income_date          = $request->input("head.purchases_date");
        $income_date_delivery = $request->input("head.purchases_deliver_date");

        $income_invoice      = $request->input("head.income_invoice");
        $income_observations = $request->input("head.income_observations");
        $income_remission    = $request->input("head.income_remission");
        $income_state        = $request->input("head.purchases_state_purc");
        $idpurchases         = $request->input("head.idpurchases");
        $income_conse        = $request->input("head.income_conse");

        $provider    = $request->input("head.provider");
        $income_move = $request->input("head.income_move");

        $identification = $request->input("user");
        $contract       = $request->input("contract");
        $id_company     = $request->input("company");

        $body = $request->input("body");

        try {

            $update_head = DB::table('income')
                ->where('idincome', $idincome)
                ->update([
                    'income_date'          => $income_date,
                    'income_date_delivery' => $income_date_delivery,
                    'income_state'         => $income_state,
                    'income_invoice'       => $income_invoice,
                    'income_remission'     => $income_remission,
                    'income_observations'  => $income_observations,
                ]);

            $recorrer = IncomeController::recorrer($body, $id_company, $income_conse, $idincome, $cellar, $idpurchases);

            $response = true;

            $income_datail = IncomeController::search_detail($idincome);
            $text_update   = Config::get('Config.ingresos.text_update');
            log::insert_log($identification, $text_update, $income_conse, $id_company);

        } catch (Exception $e) {

            $response = false;
        }

        return response()->json(['status' => 'ok', 'data' => $response, 'income_datail' => $income_datail], 200);
    }

    public function updatebody($cod_material, $iddetail_shopping, $request_amount, $amount_receipt, $unit_value, $discount, $iva, $vlriva, $subtotal, $total

        , $Consecutive, $id_company, $cellar, $income, $idincome_details, $idpurchases) {
        try {

            $cantidadAnterior = IncomeController::DetailBody($idincome_details);
            $ValidarC         = IncomeController::ValidarCatidades($cantidadAnterior, $amount_receipt, $cod_material, $id_company, $Consecutive, $income, $cellar);

            $updatebo = DB::table('income_details')
                ->where('idincome_details', $idincome_details)
                ->update([

                    'unit_value'       => $unit_value,
                    'discount'         => $discount,
                    'iva'              => $iva,
                    'requested_amount' => $request_amount,
                    'ceceived_amount'  => $amount_receipt,

                    'vlriva'           => $vlriva,
                    'subtotal'         => $subtotal,
                    'total'            => $total,
                ]);

        } catch (Exception $e) {

        }
    }

    public function DetailBody($idincome_details)
    {

        $select = DB::table('income_details')
            ->where('idincome_details', $idincome_details)
            ->select('income_details.*')
            ->first();

        return $cantidad_actual = (float) $select->ceceived_amount;
    }

// funcion para valoidar la cantidad anterior con la cantidad actual
    public function ValidarCatidades($cantidadAnterior, $amount_receipt, $code, $id_company, $Consecutive, $income, $cellar)
    {
        $tipo = Config::get('Config.ingresos.text_update');

        // 6               10
        if ($cantidadAnterior < $amount_receipt) {

            $cantidad = (FLOAT) $amount_receipt - $cantidadAnterior;

            $UpdateInventary = UpdateInventary::AddInventary($id_company, $cellar, $code, $cantidad, $Consecutive, $income, $tipo);

        }
        //10             6
        if ($cantidadAnterior > $amount_receipt) {

            $cantidad = (FLOAT) $cantidadAnterior - $amount_receipt;

            $UpdateInventary = UpdateInventary::subtract($id_company, $cellar, $code, $cantidad, $Consecutive, $income, $tipo);

        }

    }

    public function editpurchase(Request $request)
    {

        $idincome_details  = (int) $request->input("idincome_details");
        $iddetail_shopping = (int) $request->input("iddetail_shopping");

        if (isset($iddetail_shopping)) {

            if ($iddetail_shopping != 0) {

                $purchase = DB::table('detail_purchases')
                    ->leftjoin('materiales', 'detail_purchases.cod_material', '=', 'materiales.idmateriales')
                    ->where('iddetail_shopping', $iddetail_shopping)
                    ->select('detail_purchases.*', 'materiales.idmateriales', 'materiales.code', 'materiales.description')
                    ->first();
            } else {
                $purchase = DB::table('income_details')
                    ->leftjoin('materiales', 'income_details.cod_mater', '=', 'materiales.idmateriales')
                    ->where('idincome_details', $idincome_details)
                    ->select('income_details.*', 'materiales.idmateriales', 'materiales.code', 'materiales.description')
                    ->first();

            }

        } else {

        }

        return response()->json(['status' => 'ok', 'data' => $purchase], 200);

    }

    public function delete(Request $request)
    {
        $idincome_details = (int) $request->input("idincome_details");
        $identification   = (int) $request->input("user");

        try {

            $delete = DB::table('income_details')
                ->where('idincome_details', '=', $idincome_details)
                ->delete();

            $response = true;
            log::insert_log($identification, 'ELIMINA MATERIAL INGRESO', $idincome_details, 0);

        } catch (Exception $e) {
            $response = false;
        }

        return response()->json(['status' => 'ok', 'data' => $response], 200);
    }

    public function edit_mate(Request $request)
    {
        $idincome_details  = (int) $request->input("idincome_details");
        $iddetail_shopping = (int) $request->input("iddetail_shopping");

        $idincome   = (int) $request->input("idincome");
        $idpurchase = (int) $request->input("idpurchase");

        $requested_amount = (float) $request->input("purchases.requested_amount");
        $ceceived_amount  = (float) $request->input("purchases.ceceived_amount");
        $unit_value       = (float) $request->input("purchases.unit_value");
        $iva              = (float) $request->input("purchases.iva");
        $discount         = (float) $request->input("purchases.discount");
        $vlriva           = (float) $request->input("purchases.vlriva");
        $subtotal         = (float) $request->input("purchases.subtotal");
        $total            = (float) $request->input("purchases.total");

        if ($iddetail_shopping != 0) {
            $purchase = DB::table('detail_purchases')
                ->where('iddetail_shopping', '=', $iddetail_shopping)
                ->update([
                    'requested_amount' => $requested_amount,
                    'unit_value'       => $unit_value,
                    'discount'         => $discount,
                    'iva'              => $iva,
                    'vlriva'           => $vlriva,
                    'subtotal'         => $subtotal,
                    'total'            => $total,
                ]);

            $idincome_details == 0;
        }

        if ($idincome_details != '') {
            $purchase = DB::table('income_details')
                ->where('idincome_details', '=', $idincome_details)
                ->update([
                    'requested_amount' => $requested_amount,
                    'ceceived_amount'  => $ceceived_amount,
                    'unit_value'       => $unit_value,
                    'discount'         => $discount,
                    'iva'              => $iva,
                    'vlriva'           => $vlriva,
                    'subtotal'         => $subtotal,
                    'total'            => $total,

                ]);
        }

        if ($iddetail_shopping != 0) {
            $resul_detail = IncomeController::search_detailPurchase($idpurchase);

        }

        if ($idincome_details != 0) {
            $resul_detail = IncomeController::search_detail($idincome);
        }
        return response()->json(['status' => 'ok', 'data' => $resul_detail], 200);
    }

    public function search_income(Request $request)
    {

        $idpurchases = $request->input("idpurchases");

        $purchases = DB::table('purchases')
            ->join('providers', 'purchases.provider', '=', 'providers.idproviders')
            ->where('idpurchases', $idpurchases)
            ->select('purchases.*', 'providers.providers_name')
            ->first();

        $detail_purchases = DB::table('detail_purchases')
            ->join('materiales', 'detail_purchases.cod_material', '=', 'materiales.idmateriales')
            ->join('unity', 'materiales.unity', '=', 'unity.idUnity')
            ->where('id_purchases', $idpurchases)

            ->select('detail_purchases.*', 'materiales.description', 'materiales.code', 'detail_purchases.requested_amount as request_amount', 'detail_purchases.vlriva as vlriva', 'detail_purchases.discount as supply_discount'

                , 'detail_purchases.iva as supply_iva', 'materiales.code as cod_mater', 'detail_purchases.unit_value as supply_vlru', 'unity.name_Unity')
            ->get();

        $search_id = DB::table('income_details')
            ->where('id_purchase', $purchases->idpurchases)
            ->first();

        $number = count($search_id);

        // var_dump($number);
        $detail_purchase = [];

        if ($purchases->purchases_state_purc != 1) {

            //   echo 'hola';
            foreach ($detail_purchases as $search) {

                $search_parcial = DB::table('income_details')
                    ->where('id_purchase', $purchases->idpurchases)
                    ->where('cod_mater', $search->cod_material)
                    ->select(DB::raw('sum(ceceived_amount) AS d_cantidad'))
                    ->first();

                $detail_purchase[] = [
                    'cp'                => $search_parcial->d_cantidad,
                    'cod_mater'         => $search->cod_mater,
                    'cod_material'      => $search->cod_material,
                    'code'              => $search->code,
                    'description'       => $search->description,
                    'discount'          => $search->discount,
                    'id_purchases'      => $search->id_purchases,
                    'iddetail_shopping' => $search->iddetail_shopping,
                    'iva'               => $search->iva,
                    'name_Unity'        => $search->name_Unity,
                    'request_amount'    => $search->request_amount,
                    'requested_amount'  => $search->request_amount,
                    'subtotal'          => $search->subtotal,
                    'supply_discount'   => $search->supply_discount,
                    'supply_iva'        => $search->supply_iva,
                    'supply_vlru'       => $search->supply_vlru,
                    'total'             => $search->total,
                    'unit_value'        => $search->unit_value,
                    'vlriva'            => $search->vlriva,

                ];
            }

        }

        return response()->json(['status' => 'ok', 'purchases' => $purchases, 'detail_purchases' => $detail_purchases, 'detail_purchase' => $detail_purchase, 'move' => 1], 200);

    }

}
