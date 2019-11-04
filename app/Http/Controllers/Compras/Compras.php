<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\Controller;
use Config;
use Facades\App\ClassPhp\Consecutive;
use Facades\App\ClassPhp\log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Compras extends Controller
{
    //

    public function save(Request $request)
    {

        $company                = $request->input("company");
        $contrato               = $request->input("contrato");
        $user                   = $request->input("user");
        $idpurchases            = $request->input("head.idpurchases");
        $provider               = $request->input("head.provider");
        $purchases_cellar       = $request->input("head.purchases_cellar");
        $purchases_date         = $request->input("head.purchases_date");
        $purchases_deliver_date = $request->input("head.purchases_deliver_date");
        $purchases_observations = $request->input("head.purchases_observations");
        $consecutive_purc       = $request->input("head.consecutive_purc");

        $data = $request->input("data");

        if (!isset($idpurchases)) {

            $documento   = Config::get('Config.compras.documento');
            $Consecutive = Consecutive::query_consecutive($company, 'COM');
            $consecutivo = $Consecutive->consecutive;

            Consecutive::Updateconsecutive_inventario($company, $documento, $consecutivo);

            $insert = DB::table('purchases')
                ->insertGetId([
                    'provider'               => $provider,
                    'purchases_id_contract'  => $contrato,
                    'purchases_cellar'       => $purchases_cellar,
                    'purchases_state_purc'   => 1,
                    'purchases_date'         => $purchases_date,
                    'purchases_observations' => $purchases_observations,
                    'purchases_deliver_date' => $purchases_deliver_date,
                    'consecutive_purc'       => $consecutivo,
                ]);

            $text_insert = Config::get('Config.compras.text_insert');
            log::insert_log($user, $text_insert, $consecutivo, $company);

            $this->recorrer($contrato, $insert, $data);

            return response()->json(['status' => 'ok', 'response' => true, 'consecutive_purc' => $consecutivo, 'id_compra' => $insert], 200);

        } else {

            $update = DB::table('purchases')

                ->where('idpurchases', $idpurchases)
                ->update([
                    'purchases_cellar'       => $purchases_cellar,
                    'purchases_date'         => $purchases_date,
                    'purchases_observations' => $purchases_observations,
                    'purchases_deliver_date' => $purchases_deliver_date,
                ]);

            $text_insert = Config::get('Config.compras.text_insert');
            log::insert_log($user, 'ATUALIZO ORDEN DE COMPRA', $consecutive_purc, $company);

            $this->recorrer($contrato, $idpurchases, $data);
            $resul_detail = $this->search_detail($idpurchases);

            return response()->json(['status' => 'ok', 'update' => true, 'data' => $resul_detail], 200);
        }
        //
    }

    public function recorrer($contrato, $insert, $data)
    {

        for ($i = 0; $i < count($data); $i++) {

            $iddetail_shopping = isset($data[$i]['iddetail_shopping']) ? $data[$i]['iddetail_shopping'] : null;
            $cod_material      = isset($data[$i]['cod_material']) ? $data[$i]['cod_material'] : null;
            $code              = isset($data[$i]['code']) ? $data[$i]['code'] : null;

            $requested_amount = isset($data[$i]['requested_amount']) ? $data[$i]['requested_amount'] : 0;
            $unit_value       = isset($data[$i]['unit_value']) ? $data[$i]['unit_value'] : 0;
            $iva              = isset($data[$i]['iva']) ? $data[$i]['iva'] : 0;
            $discount         = isset($data[$i]['discount']) ? $data[$i]['discount'] : 0;
            $vlriva           = isset($data[$i]['vlriva']) ? $data[$i]['vlriva'] : 0;
            $subtotal         = isset($data[$i]['subtotal']) ? $data[$i]['subtotal'] : 0;
            $total            = isset($data[$i]['total']) ? $data[$i]['total'] : 0;

            if ($iddetail_shopping == null && $cod_material != null) {

                $insert_data = DB::table('detail_purchases')
                    ->insert([
                        'id_purchases'     => $insert,
                        'cod_material'     => $cod_material,
                        'requested_amount' => $requested_amount,
                        'unit_value'       => $unit_value,
                        'discount'         => $discount,
                        'iva'              => $iva,
                        'vlriva'           => $vlriva,
                        'subtotal'         => $subtotal,
                        'total'            => $total,
                    ]);

            }
            if ($iddetail_shopping != null && $cod_material != null) {

                $update_data = DB::table('detail_purchases')
                    ->where('iddetail_shopping', $iddetail_shopping)
                    ->update([
                        'id_purchases'     => $insert,
                        'cod_material'     => $cod_material,
                        'requested_amount' => $requested_amount,
                        'unit_value'       => $unit_value,
                        'discount'         => $discount,
                        'iva'              => $iva,
                        'vlriva'           => $vlriva,
                        'subtotal'         => $subtotal,
                        'total'            => $total,
                    ]);
            }
        }
    }

    public function search_date(Request $request)
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

    public function search_conse(Request $request)
    {

        $consecutive = $request->input("consecutive");
        $company     = $request->input("company");

        $purchases = DB::table('purchases')
            ->join('contract', 'purchases.purchases_id_contract', '=', 'contract.idcontract')
            ->join('business', 'contract.id_empresa', '=', 'business.idbusiness')
            ->join('providers', 'purchases.provider', '=', 'providers.idproviders')
            ->join('state_moves', 'purchases.purchases_state_purc', '=', 'state_moves.idstate_moves')
            ->where('idbusiness', '=', $company)
            ->where('purchases.consecutive_purc', '=', $consecutive)
            ->select('purchases.*', 'providers.providers_name', 'state_moves.name_moves')
            ->get();

        return response()->json(['status' => 'ok', 'purchases' => $purchases], 200);

    }

    //funcion para consultar una orden de compra completa
    public function search_one(Request $request)
    {

        $idpurchases = $request->input("idpurchases");
        $response    = true;
        $purchases   = DB::table('purchases')
            ->join('providers', 'purchases.provider', '=', 'providers.idproviders')
            ->where('idpurchases', $idpurchases)
            ->select('purchases.*', 'providers.providers_name')
            ->first();

        if (!$purchases) {

            $response = false;

        } else {

            $resul_detail = $this->search_detail($idpurchases);

        }

        return response()->json(['status' => 'ok', 'response' => $response, 'purchases' => $purchases, 'detail_purchases' => $resul_detail], 200);

    }

    public function search_detail($idpurchases)
    {
        $detail_purchases = DB::table('detail_purchases')
            ->join('materiales', 'detail_purchases.cod_material', '=', 'materiales.idmateriales')
            ->join('unity', 'materiales.unity', '=', 'unity.idUnity')
            ->where('id_purchases', $idpurchases)
            ->select('detail_purchases.*', 'materiales.description', 'materiales.code', 'materiales.code as cod_mater', 'unity.name_Unity')
            ->get();

        return $detail_purchases;
    }

    public function delete(Request $request)
    {
        $iddetail_shopping = $request->input("iddetail_shopping");

        $delete = DB::table('detail_purchases')
            ->where('iddetail_shopping', $iddetail_shopping)
            ->delete();

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }
}
