<?php

namespace App\Http\Controllers\Ingresos;

use App\Http\Controllers\Controller;
use Config;
use Facades\App\ClassPhp\Consecutive;
use Facades\App\ClassPhp\log;
use Facades\App\ClassPhp\UpdateInventary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntryController extends Controller
{
    //

    public function search_purchases(Request $request)
    {
        $idpurchases = $request->input("idpurchases");

        $purchases = DB::table('purchases')
            ->join('providers', 'purchases.provider', '=', 'providers.idproviders')
            ->where('idpurchases', $idpurchases)
            ->select('purchases.*', 'providers.providers_name')
            ->first();

        if ($purchases->purchases_state_purc != 1) {

            $resul_detail = DB::table('income')
                ->leftjoin('income_details', 'income_details.idincome', '=', 'income.idincome')
                ->leftjoin('materiales', 'income_details.cod_mater', '=', 'materiales.idmateriales')
                ->leftjoin('unity', 'materiales.unity', '=', 'unity.idUnity')
                ->where('income_idpurchases', $idpurchases)
                ->groupBy('income_details.cod_mater')

                ->select('income_details.cod_mater', 'income_details.unit_value', 'income_details.discount', 'income_details.iva', 'income_details.requested_amount',
                    'income_details.idincome', 'income_details.iddetail_shopping', 'income_details.cod_mater as cod_material', 'income_details.vlriva', 'income_details.id_purchase',
                    'materiales.description', 'materiales.code', 'materiales.code as cod_mater', 'unity.name_Unity', DB::raw('sum(ceceived_amount) AS dcantidad'))
                ->get();

        } else {

            $resul_detail = $this->search_detail($idpurchases);
        }

        return response()->json(['status' => 'ok', 'response' => true, 'purchases' => $purchases, 'detail_purchases' => $resul_detail], 200);
    }

    public function search_detail($idpurchases)
    {
        $detail_purchases = DB::table('detail_purchases')
            ->leftjoin('materiales', 'detail_purchases.cod_material', '=', 'materiales.idmateriales')
            ->join('unity', 'materiales.unity', '=', 'unity.idUnity')
            ->where('id_purchases', $idpurchases)
            ->select('detail_purchases.*', 'materiales.description', 'materiales.code', 'materiales.code as cod_mater', 'unity.name_Unity')
            ->get();

        return $detail_purchases;
    }

    public function save(Request $request)
    {
        $company = $request->input("company");
        $contract = $request->input("contract");
        $user = $request->input("user");

        $idincome = $request->input("head.idincome");
        $income_conse = $request->input("head.income_conse");
        $income_cellar = $request->input("head.income_cellar");
        $income_date = $request->input("head.income_date");
        $income_date_delivery = $request->input("head.income_date_delivery");
        $income_state = $request->input("head.income_state");
        $income_idpurchases = $request->input("head.income_idpurchases");
        $income_move = $request->input("head.income_move");
        $income_invoice = $request->input("head.income_invoice");
        $income_remission = $request->input("head.income_remission");
        $income_observations = $request->input("head.income_observations");
        $income_idprovider = $request->input("head.income_idprovider");
        $consecutive_purc = $request->input("head.consecutive_purc");

        $data = $request->input("data");

        if (!isset($idincome)) {
            $documento = Config::get('Config.ingresos.documento');
            $Consecutive = Consecutive::query_consecutive($company, $documento);
            $consecutivo = $Consecutive->consecutive;

            Consecutive::Updateconsecutive_inventario($company, $documento, $consecutivo);

            $insert_income = DB::table('income')
                ->insertGetId([
                    'income_idcontract' => $contract,
                    'income_conse' => $consecutivo,
                    'income_cellar' => $income_cellar,
                    'income_date' => $income_date,
                    'income_date_delivery' => $income_date_delivery,
                    'income_state' => $income_state,
                    'income_idpurchases' => $income_idpurchases,
                    'income_move' => $income_move,
                    'income_invoice' => $income_invoice,
                    'income_remission' => $income_remission,
                    'income_observations' => $income_observations,
                    'income_idprovider' => $income_idprovider,
                    'consecutive_purc' => $consecutive_purc,
                ]);

            $text_insert = Config::get('Config.ingresos.text_insert');
            log::insert_log($user, $text_insert, $consecutivo, $company);

            $this->recorrer($contract, $insert_income, $data, $income_cellar, $consecutivo, $company, $user);

            $update_purchase = DB::table('purchases')
                ->where('idpurchases', $income_idpurchases)
                ->update([

                    'purchases_state_purc' => $income_state,
                ]);

            return response()->json(['status' => 'ok', 'consecutivo' => $consecutivo, 'response' => true, 'id_ingreso' => $insert_income], 200);

        } else {

        }
    }

    public function recorrer($contract, $insert_income, $data, $income_cellar, $consecutivo, $company, $user)
    {
        for ($i = 0; $i < count($data); $i++) {

            $idincome_details = isset($data[$i]['idincome_details']) ? $data[$i]['idincome_details'] : null;
            $cod_material = isset($data[$i]['cod_material']) ? $data[$i]['cod_material'] : null;

            $unit_value = isset($data[$i]['unit_value']) ? $data[$i]['unit_value'] : 0;
            $discount = isset($data[$i]['discount']) ? $data[$i]['discount'] : 0;
            $iva = isset($data[$i]['iva']) ? $data[$i]['iva'] : 0;
            $requested_amount = isset($data[$i]['requested_amount']) ? $data[$i]['requested_amount'] : 0;
            $ceceived_amount = isset($data[$i]['ceceived_amount']) ? $data[$i]['ceceived_amount'] : 0;
            $vlriva = isset($data[$i]['vlriva']) ? $data[$i]['vlriva'] : 0;
            $subtotal = isset($data[$i]['subtotal']) ? $data[$i]['subtotal'] : 0;
            $total = isset($data[$i]['total']) ? $data[$i]['total'] : 0;

            $idincome = isset($data[$i]['idincome']) ? $data[$i]['idincome'] : null;
            $iddetail_shopping = isset($data[$i]['iddetail_shopping']) ? $data[$i]['iddetail_shopping'] : null;
            $id_purchase = isset($data[$i]['id_purchase']) ? $data[$i]['id_purchase'] : null;

            if ($idincome_details == null) {

                $insert_details = DB::table('income_details')
                    ->insert([
                        'cod_mater' => $cod_material,
                        'unit_value' => $unit_value,
                        'discount' => $discount,
                        'iva' => $iva,
                        'requested_amount' => $requested_amount,
                        'ceceived_amount' => $ceceived_amount,
                        'iddetail_shopping' => $iddetail_shopping,
                        'vlriva' => $vlriva,
                        'subtotal' => $subtotal,
                        'total' => $total,
                        'idincome' => $insert_income,

                    ]);

                UpdateInventary::sumarinventario($insert_income, $income_cellar, $consecutivo, $cod_material, $ceceived_amount, 'CREO INGRESO', $company, $user);

            }

        }
    }

    public function search_incom(Request $request)
    {
        $start_date = $request->input("start_date");
        $end_date = $request->input("end_date");
        $company = $request->input("company");

        $income = DB::table('income')
            ->leftjoin('contract', 'income.income_idcontract', '=', 'contract.idcontract')
            ->leftjoin('providers', 'income.income_idprovider', '=', 'providers.idproviders')
            ->leftjoin('state_moves', 'income.income_state', '=', 'state_moves.idstate_moves')
            ->leftjoin('purchases', 'income.income_idpurchases', '=', 'purchases.idpurchases')
            ->select('income.*', 'providers.providers_name', 'state_moves.idpurchases')
            ->where('contract.id_empresa', '=', $company)
            ->whereBetween('income_date', [$start_date, $end_date])
            ->select('income.idincome', 'state_moves.name_moves', 'income.income_date', 'income.income_date', 'purchases.consecutive_purc', 'providers.providers_name', 'income.income_conse')
            ->get();
        return response()->json(['status' => 'ok', 'income' => $income], 200);
    }
    public function search_conse(Request $request)
    {

        $consecutive = $request->input("consecutive");
        $company = $request->input("company");

        $income = DB::table('income')
            ->leftjoin('contract', 'income.income_idcontract', '=', 'contract.idcontract')
            ->leftjoin('providers', 'income.income_idprovider', '=', 'providers.idproviders')
            ->leftjoin('state_moves', 'income.income_state', '=', 'state_moves.idstate_moves')
            ->leftjoin('purchases', 'income.income_idpurchases', '=', 'purchases.idpurchases')
            ->select('income.*', 'providers.providers_name', 'state_moves.idpurchases')
            ->where('contract.id_empresa', '=', $company)
            ->where('income.income_conse', $consecutive)
            ->select('income.idincome', 'state_moves.name_moves', 'income.income_date', 'income.income_date', 'purchases.consecutive_purc', 'providers.providers_name', 'income.income_conse')
            ->get();
        return response()->json(['status' => 'ok', 'income' => $income], 200);
    }

    public function search_one_income(Request $request)
    {

        $idincome = $request->input("idincome");

        $Search_income = DB::table('income')
            ->leftjoin('providers', 'income.income_idprovider', '=', 'providers.idproviders')
            ->where('idincome', $idincome)
            ->first();

        $income_details = $this->search_detailincome($idincome);

        return response()->json(['status' => 'ok', 'income' => $Search_income, 'income_details' => $income_details], 200);

    }

    public function search_detailincome($idincome)
    {

        $body = DB::table('income_details')
            ->leftjoin('materiales', 'income_details.cod_mater', '=', 'materiales.idmateriales')
            ->leftjoin('unity', 'materiales.unity', '=', 'unity.idUnity')
            ->where('idincome', $idincome)
            ->select(
                'income_details.unit_value',
                'income_details.discount',
                'income_details.iva',
                'income_details.requested_amount',
                'income_details.idincome',
                'income_details.iddetail_shopping',
                'income_details.vlriva',
                'income_details.subtotal',
                'income_details.total',
                'income_details.id_purchase',
                'income_details.cod_mater as cod_material',
                'materiales.description',
                'materiales.code',
                'unity.name_Unity',
                'income_details.ceceived_amount',
                'income_details.ceceived_amount',
                'income_details.idincome_details',
                'income_details.cod_mater')
            ->get();

        foreach ($body as $search) {

            $ingresos = DB::table('income_details')
                ->where('iddetail_shopping', $search->iddetail_shopping)
                ->where('cod_mater', $search->cod_mater)
                ->groupBy('income_details.iddetail_shopping')
                ->select(DB::raw('sum(income_details.ceceived_amount) AS dcantidad'))
                ->first();

            if ($ingresos->dcantidad == 0) {
                $dcantidad = 0;

            } else {
                $dcantidad = $ingresos->dcantidad - $search->ceceived_amount;
            }

            $detail[] = [
                'idincome_details' => $search->idincome_details,
                'cod_mater' => $search->code,
                'discount' => $search->discount,
                'iva' => $search->iva,
                'requested_amount' => $search->requested_amount,
                'ceceived_amount' => $search->ceceived_amount,
                'idincome' => $search->idincome,
                'iddetail_shopping' => $search->iddetail_shopping,
                'vlriva' => $search->vlriva,
                'subtotal' => $search->subtotal,
                'total' => $search->total,
                'id_purchase' => $search->id_purchase,
                'dcantidad' => $dcantidad,
                'cod_material' => $search->cod_material,
                'name_Unity' => $search->name_Unity,
                'description' => $search->description,
                'code' => $search->code,
                'unit_value' => $search->unit_value,

            ];

        }

        return $detail;
    }

    public function update_income(Request $request)
    {
        $idincome = $request->input("head.idincome");
        $income_conse = $request->input("head.income_conse");
        $income_cellar = $request->input("head.income_cellar");
        $income_date = $request->input("head.income_date");
        $income_date_delivery = $request->input("head.income_date_delivery");
        $income_state = $request->input("head.income_state");
        $income_idpurchases = $request->input("head.income_idpurchases");
        $income_move = $request->input("head.income_move");
        $income_invoice = $request->input("head.income_invoice");
        $income_remission = $request->input("head.income_remission");
        $income_observations = $request->input("head.income_observations");
        $income_idprovider = $request->input("head.income_idprovider");
        $consecutive_purc = $request->input("head.consecutive_purc");

        $insert_income = DB::table('income')
            ->where('idincome', $idincome)
            ->update([
                'income_date' => $income_date,
                'income_date_delivery' => $income_date_delivery,
                'income_invoice' => $income_invoice,
                'income_remission' => $income_remission,
                'income_observations' => $income_observations,

            ]);

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function edit_income(Request $request)
    {

        $idincome_details = $request->input("data.idincome_details");
        $income_conse = $request->input("data.income_conse");
        $cod_mater = $request->input("data.cod_mater");
        $iddetail_shopping = $request->input("data.iddetail_shopping");
        $requested_amount = $request->input("data.requested_amount");
        $cantidadan = $request->input("data.cantidadan");
        $ceceived_amount = $request->input("data.ceceived_amount");
        $subtotal = $request->input("data.subtotal");
        $total = $request->input("data.total");
        $vlriva = $request->input("data.vlriva");
        $income = $request->input("income");
        $income_idpurchases = $request->input("income_idpurchases");
        $income_conse = $request->input("income_conse");
        $user = $request->input("user");
        $income_cellar = $request->input("income_cellar");
        $company = $request->input("company");
        $resl = false;

        $idprofile = $request->input("idprofile");
        $password = $request->input("password");

        $search_pass = DB::table('edit_income')
            ->where('password', $password)
            ->where('idprofile', $idprofile)
            ->where('edit_income', 1)
            ->first();

        if (!$search_pass) {

            return response()->json(['status' => 'ok', 'response' => true, 'password' => false], 200);

        }

        if ($ceceived_amount > $cantidadan) {

            $cantidad = $ceceived_amount - $cantidadan;

            UpdateInventary::sumarinventario($income, $income_cellar, $income_conse, $cod_mater, $cantidad, 'ATUALIZO INGRESO', $company, $user);

            $update = DB::table('income_details')
                ->where('idincome_details', $idincome_details)
                ->update([
                    'ceceived_amount' => $ceceived_amount,
                    'subtotal' => $subtotal,
                    'total' => $total,
                ]);
        }

        if ($cantidadan > $ceceived_amount) {

            $cantidad = $cantidadan - $ceceived_amount;

            $saldo = UpdateInventary::inventario_atual($income_cellar, $cod_mater, $cantidad);

            if ($cantidad > $saldo) {

                return response()->json(['status' => 'ok', 'saldo' => true], 200);
            }

            UpdateInventary::restarinventario($income, $income_cellar, $income_conse, $cod_mater, $cantidad, 'ATUALIZO INGRESO', $company, $user);

            $update = DB::table('income_details')
                ->where('idincome_details', $idincome_details)
                ->update([
                    'ceceived_amount' => $ceceived_amount,
                    'subtotal' => $subtotal,
                    'total' => $total,
                ]);

        }

        $resul_detail = $this->search_detailincome($income);
        $total = $this->total($income, $income_idpurchases);

        return response()->json(['status' => 'ok', 'response' => true, 'resul_detail' => $resul_detail, 'total' => $total], 200);

    }

    public function search_bodyincome($income, $income_idpurchases)
    {

        $resul_detail = DB::table('income_details')

            ->leftjoin('materiales', 'income_details.cod_mater', '=', 'materiales.idmateriales')
            ->leftjoin('unity', 'materiales.unity', '=', 'unity.idUnity')
            ->where('income_details.idincome', $income)
            ->groupBy('income_details.cod_mater')

            ->select('income_details.cod_mater', 'income_details.unit_value', 'income_details.discount', 'income_details.iva', 'income_details.requested_amount',
                'income_details.idincome', 'income_details.iddetail_shopping', 'income_details.cod_mater as cod_material', 'income_details.vlriva', 'income_details.id_purchase', 'income_details.ceceived_amount',
                'materiales.description', 'materiales.code', 'materiales.code as cod_mater', 'unity.name_Unity', 'income_details.idincome_details', 'income_details.subtotal', 'income_details.total')
            ->get();

        return $resul_detail;
    }

    public function total($income, $income_idpurchases)
    {

        $resul_detail = DB::table('income')
            ->leftjoin('income_details', 'income_details.idincome', '=', 'income.idincome')
            ->leftjoin('materiales', 'income_details.cod_mater', '=', 'materiales.idmateriales')
            ->leftjoin('unity', 'materiales.unity', '=', 'unity.idUnity')
            ->where('income_idpurchases', $income_idpurchases)
            ->groupBy('income_details.cod_mater')

            ->select('income_details.cod_mater', 'income_details.unit_value', 'income_details.discount', 'income_details.iva', 'income_details.requested_amount',
                'income_details.idincome', 'income_details.iddetail_shopping', 'income_details.cod_mater as cod_material', 'income_details.vlriva', 'income_details.id_purchase',
                'materiales.description', 'materiales.code', 'materiales.code as cod_mater', 'unity.name_Unity', DB::raw('sum(ceceived_amount) AS dcantidad'))
            ->get();

        return $resul_detail;
    }

    public function income_details(Request $request)
    {
        $income = $request->input("income");
        $cod_mate = $request->input("cod_mate");
        $income_idpurchases = $request->input("income_idpurchases");

        $resul_detail = DB::table('income')
            ->leftjoin('income_details', 'income_details.idincome', '=', 'income.idincome')
            ->leftjoin('materiales', 'income_details.cod_mater', '=', 'materiales.idmateriales')
            ->leftjoin('unity', 'materiales.unity', '=', 'unity.idUnity')
            ->where('income_idpurchases', $income_idpurchases)
            ->where('income_details.cod_mater', $cod_mate)
            ->groupBy('income_details.cod_mater')

            ->select('income_details.cod_mater', 'income_details.unit_value', 'income_details.discount', 'income_details.iva', 'income_details.requested_amount',
                'income_details.idincome', 'income_details.iddetail_shopping', 'income_details.vlriva', 'income_details.id_purchase',
                'materiales.description', 'materiales.code', 'materiales.code as cod_mater', 'unity.name_Unity', DB::raw('sum(ceceived_amount) AS dcantidad'))
            ->first();

        return response()->json(['status' => 'ok', 'response' => $resul_detail], 200);
    }

    public function edit_state(Request $request)
    {

        $company = $request->input("company");
        $contract = $request->input("contract");
        $user = $request->input("user");

        $idincome = $request->input("head.idincome");
        $income_conse = $request->input("head.income_conse");
        $income_cellar = $request->input("head.income_cellar");
        $income_date = $request->input("head.income_date");
        $income_date_delivery = $request->input("head.income_date_delivery");
        $income_state = $request->input("head.income_state");
        $income_idpurchases = $request->input("head.income_idpurchases");
        $income_move = $request->input("head.income_move");
        $income_invoice = $request->input("head.income_invoice");
        $income_remission = $request->input("head.income_remission");
        $income_observations = $request->input("head.income_observations");
        $income_idprovider = $request->input("head.income_idprovider");
        $consecutive_purc = $request->input("head.consecutive_purc");

        $insert_income = DB::table('income')
            ->where('idincome', $idincome)
            ->update([
                'income_state' => $income_state,
            ]);

        $update_purchase = DB::table('purchases')
            ->where('idpurchases', $income_idpurchases)
            ->update([

                'purchases_state_purc' => $income_state,
            ]);

        $text_insert = Config::get('Config.ingresos.text_update');
        log::insert_log($user, $text_insert, $income_conse, $company);

        return response()->json(['status' => 'ok', 'response' => true], 200);

    }

    public function historico(Request $request)
    {

        $company = $request->input("company");
        $consecutive = $request->input("consecutive");
        $params1 = $request->input("params1");
        $params2 = $request->input("params2");

        $search = DB::table('historical_inventory')
            ->leftjoin('materiales', 'historical_inventory.id_code', '=', 'materiales.idmateriales')
            ->leftjoin('employees', 'historical_inventory.user', '=', 'employees.Users_id_identification')
            ->where('conse', $consecutive)
            ->where('idcompany', $company)
            ->whereIn('tipo', [$params2, $params1])
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

}
