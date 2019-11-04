<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use Config;
use Facades\App\ClassPhp\Consecutive;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ExternalController extends Controller
{
    //
    public $idacta;
    public $mensage;
    public $acta;
    public $user;
    public $address;
    public $mail;

    public function insert(Request $request)
    {

        $obr_ans         = (int) $request->input("data.obr_ans");
        $obr_consecutivo = (int) $request->input("data.obr_consecutivo");
        $obr_date_end    = $request->input("data.obr_date_end");
        $obr_date_ini    = $request->input("data.obr_date_ini");
        $obr_date        = $request->input("data.obr_date");
        $obr_direccion   = (String) mb_strtoupper($request->input("data.obr_direccion"));
        $obr_extado      = (int) $request->input("data.obr_extado");
        $obr_municipio   = (int) $request->input("data.obr_municipio");
        $obr_nplano      = (String) mb_strtoupper($request->input("data.obr_nplano"));
        $obr_nproyecto   = (String) mb_strtoupper($request->input("data.obr_nproyecto"));
        $obr_notas       = (String) mb_strtoupper($request->input("data.obr_notas"));
        $obr_tipo        = (int) $request->input("data.obr_tipo");

        $company  = (int) $request->input("company");
        $contract = (int) $request->input("contract");

        try {

            $documento   = Config::get('Config.obra_externa.documento');
            $Consecutive = Consecutive::query_consecutive_ext($company, $documento, $contract);

            $insert_external = DB::table('obr_externa')
                ->insertGetId([
                    'obr_ans'         => $obr_ans,
                    'obr_consecutivo' => $Consecutive->consecutive,
                    'obr_date_end'    => $obr_date_end,
                    'obr_date_ini'    => $obr_date_ini,
                    'obr_direccion'   => $obr_direccion,
                    'obr_extado'      => $obr_extado,
                    'obr_municipio'   => $obr_municipio,
                    'obr_nplano'      => $obr_nplano,
                    'obr_nproyecto'   => $obr_nproyecto,
                    'obr_tipo'        => $obr_tipo,
                    'obr_contrato'    => $contract,
                    'obr_date'        => $obr_date,
                    'obr_notas'       => $obr_notas,

                ]);

            $response   = true;
            $ConseAtual = (Int) $Consecutive->consecutive + 1;
            $update     = Consecutive::Updateconsecutive_ext($company, $documento, $ConseAtual, $contract);

        } catch (\Exception $e) {

            $response = false;
        }
        return response()->json(['status' => 'ok', 'data' => $response, 'consecutive' => $Consecutive->consecutive, 'id_obr' => $insert_external], 200);
    }

    public function update(Request $request)
    {
        $obr_ans         = (int) $request->input("data.obr_ans");
        $obr_consecutivo = (int) $request->input("data.obr_consecutivo");
        $obr_date_end    = $request->input("data.obr_date_end");
        $obr_date_ini    = $request->input("data.obr_date_ini");
        $obr_date        = $request->input("data.obr_date");
        $obr_direccion   = (String) mb_strtoupper($request->input("data.obr_direccion"));
        $obr_extado      = (int) $request->input("data.obr_extado");
        $obr_municipio   = (int) $request->input("data.obr_municipio");
        $obr_nplano      = (String) mb_strtoupper($request->input("data.obr_nplano"));
        $obr_nproyecto   = (String) mb_strtoupper($request->input("data.obr_nproyecto"));
        $obr_notas       = (String) mb_strtoupper($request->input("data.obr_notas"));
        $obr_tipo        = (int) $request->input("data.obr_tipo");

        $company  = (int) $request->input("company");
        $contract = (int) $request->input("contract");

        try {

            $update_external = DB::table('obr_externa')
                ->join('contract', 'obr_externa.obr_contrato', '=', 'contract.idcontract')
                ->where('contract.id_empresa', '=', $company)
                ->where('obr_externa.obr_contrato', '=', $contract)
                ->where('obr_consecutivo', '=', $obr_consecutivo)
                ->update([
                    'obr_ans'       => $obr_ans,
                    'obr_date_end'  => $obr_date_end,
                    'obr_date_ini'  => $obr_date_ini,
                    'obr_direccion' => $obr_direccion,
                    'obr_extado'    => $obr_extado,
                    'obr_municipio' => $obr_municipio,
                    'obr_nplano'    => $obr_nplano,
                    'obr_nproyecto' => $obr_nproyecto,
                    'obr_tipo'      => $obr_tipo,
                    'obr_date'      => $obr_date,
                    'obr_notas'     => $obr_notas,

                ]);

            $response = true;

        } catch (\Exception $e) {

            $update_external = false;
            $response        = false;
        }

        return response()->json(['status' => 'ok', 'data' => $response, 'update_external' => $update_external], 200);
    }

    public function search_consec(Request $request)
    {
        $obr_consecutivo = (int) $request->input("consecutive");
        $contract        = (int) $request->input("contract");

        try {

            $search_obr = DB::table('obr_externa')
                ->join('contract', 'obr_externa.obr_contrato', '=', 'contract.idcontract')
                ->where('idobr_externa', '=', $obr_consecutivo)
                ->where('obr_externa.obr_contrato', '=', $contract)
                ->first();
            $response = true;

            $idexterna = $search_obr->idobr_externa;
            $anillo    = ExternalController::searc_anillo($idexterna);

        } catch (\Exception $e) {
            $response = false;
        }

        return response()->json(['status' => 'ok', 'data' => $response, 'search_obr' => $search_obr, 'anillo' => $anillo], 200);
    }

    public function searc_anillo($idexterna)
    {

        $anillo = DB::table('obr_anillos')
            ->join('state_anillo', 'obr_anillos.obr_anillos_state', '=', 'state_anillo.idstate_anillo')
            ->where('idobr_externa', '=', $idexterna)
            ->get();

        return $anillo;
    }

    public function insert_anillo(Request $request)
    {
        $idobr_externa           = (int) $request->input("idobr_externa");
        $obr_anillos_consecutivo = (int) $request->input("obr_consecutivo");
        $obr_anillos_contract    = (int) $request->input("contract");
        $obr_anillos_company     = (int) $request->input("company");

        $obr_anillos_anillo       = (int) $request->input("data.obr_anillos_anillo");
        $obr_anillos_instalacion  = (int) $request->input("data.obr_anillos_instalacion");
        $obr_anillos_pedido       = (String) mb_strtoupper($request->input("data.obr_anillos_pedido"));
        $obr_anillos_type_obr     = (int) $request->input("data.obr_anillos_type_obr");
        $obr_anillos_ot           = (String) mb_strtoupper($request->input("data.obr_anillos_ot"));
        $obr_anillos_state        = (int) $request->input("data.obr_anillos_state");
        $obr_anillos_obser        = (String) $request->input("data.obr_anillos_obser");
        $obr_anillos_cierreanillo = (int) $request->input("data.obr_anillos_cierreanillo");
        $obr_anillos_gasificacion = (int) $request->input("data.obr_anillos_gasificacion");
        $obr_anillos_herme        = (int) $request->input("data.obr_anillos_herme");
        $obr_anillos_refer        = (int) $request->input("data.obr_anillos_refer");
        $obr_anillos_concre       = (int) $request->input("data.obr_anillos_concre");
        $obr_anillos_pav          = (int) $request->input("data.obr_anillos_pav");
        $obr_anillos_gpz          = (String) mb_strtoupper($request->input("data.obr_anillos_gpz"));
        $obr_anillos_oti          = (String) mb_strtoupper($request->input("data.obr_anillos_oti"));
        $obr_anillos_solicitud    = (int) $request->input("data.obr_anillos_solicitud");
        $otiStartDate             = $request->input("data.otiStartDate");
        $otiLastDate              = $request->input("data.otiLastDate");

        try {
            $inser_anillo = DB::table('obr_anillos')
                ->insertGetId([
                    'idobr_externa'            => $idobr_externa,
                    'obr_anillos_consecutivo'  => $obr_anillos_consecutivo,
                    'obr_anillos_contract'     => $obr_anillos_contract,
                    'obr_anillos_anillo'       => $obr_anillos_anillo,
                    'obr_anillos_instalacion'  => $obr_anillos_instalacion,
                    'obr_anillos_pedido'       => $obr_anillos_pedido,
                    'obr_anillos_ot'           => $obr_anillos_ot,
                    'obr_anillos_state'        => $obr_anillos_state,
                    'obr_anillos_obser'        => $obr_anillos_obser,
                    'obr_anillos_cierreanillo' => $obr_anillos_cierreanillo,
                    'obr_anillos_gasificacion' => $obr_anillos_gasificacion,
                    'obr_anillos_herme'        => $obr_anillos_herme,
                    'obr_anillos_refer'        => $obr_anillos_refer,
                    'obr_anillos_concre'       => $obr_anillos_concre,
                    'obr_anillos_pav'          => $obr_anillos_pav,
                    'obr_anillos_oti'          => $obr_anillos_oti,
                    'obr_anillos_solicitud'    => $obr_anillos_solicitud,
                    'obr_anillos_gpz'          => $obr_anillos_gpz,

                    'otiStartDate'             => $otiStartDate,
                    'otiLastDate'              => $otiLastDate,

                ]);
            $result = true;

        } catch (\Exception $e) {
            $result = false;
        }

        return response()->json(['status' => 'ok', 'result' => $result, 'Idoti' => $inser_anillo], 200);
    }

    public function autoconsecutive(Request $request)
    {
        $term     = (int) $request->input("term");
        $contract = (int) $request->input("contract");

        $consecutive = DB::table('obr_externa')
            ->where('obr_consecutivo', 'like', $term . '%')
            ->where('obr_contrato', '=', $contract)
            ->take(10)
            ->get();
        return response()->json(['status' => 'ok', 'consecutive' => $consecutive], 200);

    }

    public function oti(Request $request)
    {

        $term     = $request->input("term");
        $contract = $request->input("contract");

        $obr_anillos = DB::table('obr_anillos')
            ->leftjoin('obr_externa', 'obr_anillos.idobr_externa', '=', 'obr_externa.idobr_externa')
            ->where('obr_anillos_oti', 'like', $term . '%')
            ->where('obr_anillos_contract', '=', $contract)
            ->take(10)
            ->get();
        return response()->json(['status' => 'ok', 'obr_anillos' => $obr_anillos], 200);
    }

    public function consecutive(Request $request)
    {
        $oti     = (int) $request->input("oti");
        $company = (int) $request->input("company");

        $consecutive = DB::table('obr_anillos')
            ->where('obr_anillos_oti', '=', $oti)
            ->where('obr_anillos_company', '=', $company)
            ->first();
        return response()->json(['status' => 'ok', 'consecutive' => $consecutive], 200);
    }

    public function search_municipaly(Request $request)
    {
        $municipio = (int) $request->input("municipio");
        $company   = (int) $request->input("company");

        $consecutive = DB::table('obr_externa')
            ->join('type_obraext', 'obr_externa.obr_tipo', '=', 'type_obraext.idtype_obraext')
            ->join('contract', 'obr_externa.obr_contrato', '=', 'contract.idcontract')
            ->join('municipality', 'obr_externa.obr_municipio', '=', 'municipality.idmunicipality')
            ->where('obr_municipio', '=', $municipio)
            ->where('contract.id_empresa', '=', $company)
            ->get();
        return response()->json(['status' => 'ok', 'consecutive' => $consecutive], 200);
    }

    public function search_oti(Request $request)
    {
        $idobr_anillos = (int) $request->input("idobr_anillos");

        $oti = DB::table('obr_anillos')
            ->where('idobr_anillos', '=', $idobr_anillos)
            ->first();

        return response()->json(['status' => 'ok', 'oti' => $oti], 200);
    }

    public function update_anillo(Request $request)
    {
        $idobr_externa           = (int) $request->input("data.idobr_externa");
        $idobr_anillos           = (int) $request->input("data.idobr_anillos");
        $obr_anillos_consecutivo = (int) $request->input("obr_consecutivo");
        $obr_anillos_contract    = (int) $request->input("contract");
        $obr_anillos_company     = (int) $request->input("company");

        $obr_anillos_anillo       = (int) $request->input("data.obr_anillos_anillo");
        $obr_anillos_instalacion  = (int) $request->input("data.obr_anillos_instalacion");
        $obr_anillos_pedido       = (String) $request->input("data.obr_anillos_pedido");
        $obr_anillos_type_obr     = (int) $request->input("data.obr_anillos_type_obr");
        $obr_anillos_ot           = (String) $request->input("data.obr_anillos_ot");
        $obr_anillos_state        = (int) $request->input("data.obr_anillos_state");
        $obr_anillos_obser        = (String) $request->input("data.obr_anillos_obser");
        $obr_anillos_cierreanillo = (int) $request->input("data.obr_anillos_cierreanillo");
        $obr_anillos_gasificacion = (int) $request->input("data.obr_anillos_gasificacion");
        $obr_anillos_herme        = (int) $request->input("data.obr_anillos_herme");
        $obr_anillos_refer        = (int) $request->input("data.obr_anillos_refer");
        $obr_anillos_concre       = (int) $request->input("data.obr_anillos_concre");
        $obr_anillos_pav          = (int) $request->input("data.obr_anillos_pav");
        $obr_anillos_gpz          = (String) $request->input("data.obr_anillos_gpz");
        $obr_anillos_oti          = (String) $request->input("data.obr_anillos_oti");
        $obr_anillos_solicitud    = (int) $request->input("data.obr_anillos_solicitud");
        $otiStartDate             = $request->input("data.otiStartDate");
        $otiLastDate              = $request->input("data.otiLastDate");

        try {

            $update = DB::table('obr_anillos')
                ->where('idobr_anillos', '=', $idobr_anillos)
                ->Update([
                    'obr_anillos_instalacion'  => $obr_anillos_instalacion,
                    'obr_anillos_pedido'       => $obr_anillos_pedido,
                    'obr_anillos_ot'           => $obr_anillos_ot,
                    'obr_anillos_state'        => $obr_anillos_state,
                    'obr_anillos_obser'        => $obr_anillos_obser,
                    'obr_anillos_cierreanillo' => $obr_anillos_cierreanillo,
                    'obr_anillos_gasificacion' => $obr_anillos_gasificacion,
                    'obr_anillos_herme'        => $obr_anillos_herme,
                    'obr_anillos_refer'        => $obr_anillos_refer,
                    'obr_anillos_concre'       => $obr_anillos_concre,
                    'obr_anillos_pav'          => $obr_anillos_pav,
                    'obr_anillos_solicitud'    => $obr_anillos_solicitud,
                    'otiStartDate'             => $otiStartDate,
                    'otiLastDate'              => $otiLastDate,

                ]);
            $result = true;
        } catch (\Exception $e) {

        }

        return response()->json(['status' => 'ok', 'update' => $result], 200);
    }

    public function save_presupuesto_item(Request $request)
    {
        $data        = $request->input("data");
        $company     = (int) $request->input("company");
        $consecutive = (int) $request->input("consecutive");
        $contract    = (int) $request->input("contract");
        $oti         = (int) $request->input("oti");
        $idexterna   = (int) $request->input("idexterna");

        for ($i = 0; $i < count($data); $i++) {

            $item_presupuesto_codeid = isset($data[$i]["item_presupuesto_codeid"]) ? $data[$i]["item_presupuesto_codeid"] : null;
            $item_presupuesto_class  = isset($data[$i]["item_presupuesto_class"]) ? $data[$i]["item_presupuesto_class"] : null;

            $iditem_cobro               = isset($data[$i]["iditem_cobro"]) ? $data[$i]["iditem_cobro"] : null;
            $iditem_presupuesto         = isset($data[$i]["iditem_presupuesto"]) ? $data[$i]["iditem_presupuesto"] : 0;
            $item_presupuesto_cantidad  = isset($data[$i]["item_presupuesto_cantidad"]) ? $data[$i]["item_presupuesto_cantidad"] : 0;
            $item_presupuesto_acomulado = isset($data[$i]["item_presupuesto_acomulado"]) ? $data[$i]["item_presupuesto_acomulado"] : 0;
            $id_ipid                    = isset($data[$i]["id_ipid"]) ? $data[$i]["id_ipid"] : null;

            if ($item_presupuesto_codeid != null) {
                if ($iditem_presupuesto == 0) {

                    $insert = DB::table('item_presupuesto')
                        ->insert([
                            'item_presupuesto_class'     => $item_presupuesto_class,
                            'item_presupuesto_codeid'    => $item_presupuesto_codeid,
                            'item_presupuesto_cantidad'  => $item_presupuesto_cantidad,
                            'item_presupuesto_acomulado' => $item_presupuesto_acomulado,
                            'item_presupuest_idoti'      => $oti,
                            'id_ipid'                    => $id_ipid,
                        ]);

                } else {

                    $updateitem = DB::table('item_presupuesto')
                        ->where('iditem_presupuesto', '=', $iditem_presupuesto)
                        ->update([
                            'item_presupuesto_class'     => $item_presupuesto_class,
                            'item_presupuesto_codeid'    => $item_presupuesto_codeid,
                            'item_presupuesto_cantidad'  => $item_presupuesto_cantidad,
                            'item_presupuesto_acomulado' => $item_presupuesto_acomulado,
                            'id_ipid'                    => $id_ipid,
                        ]);

                }
            }
        }

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function search_presupuesto_item(Request $request)
    {

        $oti = $request->input("oti");

        $search = DB::table('item_presupuesto')
            ->where('item_presupuest_idoti', '=', $oti)
            ->leftjoin('item_cobro', 'item_cobro.iditem_cobro', '=', 'item_presupuesto.item_presupuesto_codeid')
            ->leftjoin('clasificacion_item', 'item_cobro.item_cobro_clasificacion', '=', 'clasificacion_item.idclasificacion_item')
            ->select('item_presupuesto.*', 'item_cobro.*', 'item_cobro.item_cobro_code as item', 'clasificacion_item.clasificacion_name as clasificacion_name')
            ->get();

        $number = count($search);

        if ($number != 0) {

            foreach ($search as $search) {

                $search_dobra = DB::table('items_cobro_externas')
                    ->where('items_externas_idoti', '=', $oti)
                    ->where('items_externas_code', '=', $search->iditem_cobro)
                    ->select(DB::raw('sum(items_externas_cantidad) AS d_cantidad'))
                    ->first();

                $acomulado = number_format($search_dobra->d_cantidad, 2, '.', '');

                $avance = 0;

                if ($acomulado != 0) {

                    $avance = $acomulado / $search->item_presupuesto_cantidad * 100;

                }
                $faltante = 100 - $avance;

                $results[] = [
                    'acomulado'                 => number_format($search_dobra->d_cantidad, 2, '.', ''),
                    'iditem_presupuesto'        => $search->iditem_presupuesto,
                    'avance'                    => number_format($avance, 2, '.', ''),
                    'faltante'                  => number_format($faltante, 2, '.', ''),
                    'item_presupuesto_class'    => $search->clasificacion_name,
                    'iditem_cobro'              => $search->iditem_cobro,
                    'item_presupuesto_cantidad' => $search->item_presupuesto_cantidad,
                    'item_cobro_name'           => $search->item_cobro_name,
                    'item'                      => $search->item,
                    'item_cobro_code'           => $search->item_cobro_code,
                    'iditem_presupuesto'        => $search->iditem_presupuesto,
                    'item_presupuesto_codeid'   => $search->item_presupuesto_codeid,
                    'id_ipid'                   => $search->id_ipid,

                ];

            }

        } else {
            $results = [];
        }
        return response()->json(['status' => 'ok', 'search' => $results], 200);
    }

    public function delete_items(Request $request)
    {
        try {

            $iditemp = $request->input("iditemp");

            $delete = DB::table('item_presupuesto')
                ->where('iditem_presupuesto', '=', $iditemp)
                ->delete();

            $result = true;

        } catch (\Exception $e) {

            $result = false;
        }
        return response()->json(['status' => 'ok', 'result' => $result], 200);
    }

//insertar detalle de la obra externa
    public function insert_dobra(Request $request)
    {

        $company                 = (int) $request->input("company");
        $contract                = (int) $request->input("contract");
        $idobr                   = (int) $request->input("idobr");
        $idoti                   = (int) $request->input("idoti");
        $detalles_obra_encargado = $request->input("data.detalles_obra_encargado");
        $detalles_obra_pegador   = $request->input("data.detalles_obra_pegador");
        $detalles_obraobser      = mb_strtoupper($request->input("data.detalles_obraobser"));
        $detalles_obra_date      = $request->input("data.detalles_obra_date");

        $problemss = $request->input("data.problemss");
        $quejas    = $request->input("data.quejas");
        $type_gans = $request->input("data.type_gans");

        $insert_d = DB::table('detalles_obra')
            ->insert([
                'detalles_obra_idoti'     => $idoti,
                'detalles_obra_date'      => $detalles_obra_date,
                'detalles_obra_encargado' => $detalles_obra_encargado,
                'detalles_obra_pegador'   => $detalles_obra_pegador,
                'detalles_obraobser'      => $detalles_obraobser,
                'quejas'                  => $quejas,
                'problemss'               => $problemss,
                'type_gans'               => $type_gans,
            ]);
        $result = true;

        return response()->json(['status' => 'ok', 'result' => $result], 200);
    }

    public function update_dobra(Request $request)
    {

        $idobr                   = (int) $request->input("idobr");
        $idoti                   = (int) $request->input("idoti");
        $detalles_obra_encargado = $request->input("data.detalles_obra_encargado");
        $detalles_obra_pegador   = $request->input("data.detalles_obra_pegador");
        $detalles_obraobser      = (String) mb_strtoupper($request->input("data.detalles_obraobser"));
        $iddetalles_obra         = (int) $request->input("data.iddetalles_obra");
        $detalles_obra_date      = $request->input("data.detalles_obra_date");
        $problemss               = $request->input("data.problemss");
        $quejas                  = $request->input("data.quejas");
        $type_gans               = $request->input("data.type_gans");

        try {

            $update = DB::table('detalles_obra')
                ->where('iddetalles_obra', '=', $iddetalles_obra)
                ->update([
                    'detalles_obra_date'      => $detalles_obra_date,
                    'detalles_obra_encargado' => $detalles_obra_encargado,
                    'detalles_obra_pegador'   => $detalles_obra_pegador,
                    'detalles_obraobser'      => $detalles_obraobser,
                    'quejas'                  => $quejas,
                    'problemss'               => $problemss,
                    'type_gans'               => $type_gans,
                ]);
            $result = true;
        } catch (\Exception $e) {
            $result = false;
        }

        return response()->json(['status' => 'ok', 'result' => $result], 200);
    }

    public function searc_detalle_obra(Request $request)
    {
        $idobr = (int) $request->input("idobr");
        $idoti = (int) $request->input("idoti");

        $search = DB::table('detalles_obra')
            ->where('detalles_obra_idoti', '=', $idoti)
        //->leftjoin('employees','employees.idemployees','=','detalles_obra.detalles_obra_encargado')
            ->select('detalles_obra.*',
                DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.Users_id_identification=detalles_obra.detalles_obra_encargado) AS detalles_obra_encargado")
                , DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.Users_id_identification=detalles_obra.detalles_obra_pegador) AS detalles_obra_pegador"))
            ->get();

        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function searc_detalle_obra_edit(Request $request)
    {
        $iddetalles_obra = (int) $request->input("iddetalles_obra");

        $search = DB::table('detalles_obra')
            ->where('iddetalles_obra', '=', $iddetalles_obra)
        //->leftjoin('employees','employees.idemployees','=','detalles_obra.detalles_obra_encargado')
            ->select('detalles_obra.*', DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.Users_id_identification=detalles_obra.detalles_obra_encargado) AS detalles_obra_encargado1")
                , DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.Users_id_identification=detalles_obra.detalles_obra_pegador) AS detalles_obra_pegador1"))
            ->first();

        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function save_dobra(Request $request)
    {
        $id_detalle = $request->input("id_detalle");
        $data       = $request->input("data");

        for ($i = 0; $i < count($data); $i++) {

            $item_presupuesto_cantidad = (float) isset($data[$i]["item_presupuesto_cantidad"]) ? $data[$i]["item_presupuesto_cantidad"] : 0;
            $item_presupuesto_class    = (int) isset($data[$i]["item_presupuesto_class"]) ? $data[$i]["item_presupuesto_class"] : 0;
            $item                      = (int) isset($data[$i]["item"]) ? $data[$i]["item"] : null;
            $iditem_cobro              = (int) isset($data[$i]["iditem_cobro"]) ? $data[$i]["iditem_cobro"] : 0;
            $idd_obra                  = (int) isset($data[$i]["idd_obra"]) ? $data[$i]["idd_obra"] : 0;
            $ipid                      = (int) isset($data[$i]["ipid"]) ? $data[$i]["ipid"] : null;
            $l1                        = (int) isset($data[$i]["l1"]) ? $data[$i]["l1"] : null;
            $l2                        = (int) isset($data[$i]["l2"]) ? $data[$i]["l2"] : null;
            $e                         = (int) isset($data[$i]["e"]) ? $data[$i]["e"] : null;

            if ($item != null && $idd_obra == 0) {

                $save = DB::table('d_obra')
                    ->insert([
                        'd_clasificacion' => $item_presupuesto_class,
                        'id_item'         => $iditem_cobro,
                        'd_cantidad'      => $item_presupuesto_cantidad,
                        'd_detalle'       => $id_detalle,
                        'l1'              => $l1,
                        'l2'              => $l2,
                        'e'               => $e,
                        'id_ipid'         => $ipid,

                    ]);

            } else {
                $update = DB::table('d_obra')
                    ->where('idd_obra', '=', $idd_obra)
                    ->update([
                        'd_clasificacion' => $item_presupuesto_class,
                        'id_item'         => $iditem_cobro,
                        'd_cantidad'      => $item_presupuesto_cantidad,
                        'l1'              => $l1,
                        'l2'              => $l2,
                        'e'               => $e,
                        'id_ipid'         => $ipid,
                    ]);

            }
        }
        return response()->json(['status' => 'ok', 'search' => true], 200);
    }

    public function search_dobra(Request $request)
    {
        $id_detalle = (int) $request->input("id_detalle");

        $search = DB::table('d_obra')
            ->join('item_cobro', 'd_obra.id_item', '=', 'item_cobro.iditem_cobro')
            ->where('d_detalle', '=', $id_detalle)
            ->select('d_obra.idd_obra', 'd_obra.d_clasificacion as item_presupuesto_class', 'd_obra.d_cantidad as item_presupuesto_cantidad', 'item_cobro.item_cobro_name', 'item_cobro.item_cobro_code as item_cobro_code', 'item_cobro.item_cobro_code as item', 'item_cobro.iditem_cobro as iditem_cobro', 'd_obra.id_ipid as ipid', 'd_obra.l1', 'd_obra.l2', 'd_obra.e', 'item_cobro.item_cobro_unidad')
            ->get();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }
    public function delete_dobra(Request $request)
    {
        $idd_obra = (int) $request->input("idd_obra");

        try {

            $update = DB::table('d_obra')
                ->where('idd_obra', '=', $idd_obra)
                ->delete();
            $response = true;
        } catch (\Exception $e) {
            $response = false;
        }

        return response()->json(['status' => 'ok', 'response' => $response], 200);
    }

    public function save_item_cbr(Request $request)
    {
        $id_oti = (int) $request->input("id_oti");
        $data   = $request->input("data");

        for ($i = 0; $i < count($data); $i++) {
            $iditems_externas = isset($data[$i]["iditems_externas"]) ? $data[$i]["iditems_externas"] : 0;

            $item                        = isset($data[$i]["item"]) ? $data[$i]["item"] : 0;
            $iditem_cobro                = isset($data[$i]["iditem_cobro"]) ? $data[$i]["iditem_cobro"] : 0;
            $items_externas_date         = isset($data[$i]["items_externas_date"]) ? $data[$i]["items_externas_date"] : '';
            $items_externas_cantidad     = (float) isset($data[$i]["items_externas_cantidad"]) ? $data[$i]["items_externas_cantidad"] : '';
            $items_externas_state        = isset($data[$i]["items_externas_state"]) ? $data[$i]["items_externas_state"] : '';
            $items_externas_acta         = isset($data[$i]["items_externas_acta"]) ? $data[$i]["items_externas_acta"] : '';
            $items_externas_date_acta    = isset($data[$i]["items_externas_date_acta"]) ? $data[$i]["items_externas_date_acta"] : '';
            $items_externas_idoti        = isset($data[$i]["items_externas_idoti"]) ? $data[$i]["items_externas_idoti"] : '';
            $items_externas_papeleta     = isset($data[$i]["items_externas_papeleta"]) ? $data[$i]["items_externas_papeleta"] : '';
            $items_externas_solicitud    = isset($data[$i]["items_externas_solicitud"]) ? $data[$i]["items_externas_solicitud"] : '';
            $items_externas_cantidadacta = (float) isset($data[$i]["items_externas_cantidadacta"]) ? $data[$i]["items_externas_cantidadacta"] : '';
            $items_externas_ipi          = isset($data[$i]["items_externas_ipi"]) ? $data[$i]["items_externas_ipi"] : '';

            if ($iditems_externas == 0 && $item != 0) {

                $insert = DB::table('items_cobro_externas')
                    ->insert([
                        'items_externas_code'         => $iditem_cobro,
                        'items_externas_date'         => $items_externas_date,
                        'items_externas_cantidad'     => $items_externas_cantidad,
                        'items_externas_state'        => $items_externas_state,
                        'items_externas_acta'         => $items_externas_acta,
                        'items_externas_date_acta'    => $items_externas_date_acta,
                        'items_externas_idoti'        => $id_oti,
                        'items_externas_papeleta'     => $items_externas_papeleta,
                        'items_externas_solicitud'    => $items_externas_solicitud,
                        'items_externas_cantidadacta' => $items_externas_cantidadacta,
                        'items_externas_ipi'          => $items_externas_ipi,
                    ]);
            } else {
                $update = DB::table('items_cobro_externas')
                    ->where('iditems_externas', '=', $iditems_externas)
                    ->update([

                        'items_externas_date'         => $items_externas_date,
                        'items_externas_cantidad'     => $items_externas_cantidad,
                        'items_externas_state'        => $items_externas_state,
                        'items_externas_acta'         => $items_externas_acta,
                        'items_externas_date_acta'    => $items_externas_date_acta,

                        'items_externas_papeleta'     => $items_externas_papeleta,
                        'items_externas_solicitud'    => $items_externas_solicitud,
                        'items_externas_cantidadacta' => $items_externas_cantidadacta,
                        'items_externas_ipi'          => $items_externas_ipi,
                    ]);
            }

        }

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function search_item_cbr(Request $request)
    {
        $id_oti = (int) $request->input("id_oti");

        $serach = DB::table('items_cobro_externas')
            ->join('item_cobro', 'items_cobro_externas.items_externas_code', '=', 'item_cobro.iditem_cobro')
            ->where('items_externas_idoti', '=', $id_oti)
            ->select('items_cobro_externas.*', 'item_cobro.item_cobro_name', 'item_cobro.item_cobro_code as item_cobro_code', 'item_cobro.item_cobro_code as item', 'item_cobro.iditem_cobro as iditem_cobro')
            ->get();

        return response()->json(['status' => 'ok', 'serach' => $serach], 200);
    }

    public function delete_item_cbr(Request $request)
    {
        $id_item_cbr = (int) $request->input("id_item_cbr");

        try {
            $delete = DB::table('items_cobro_externas')
                ->where('iditems_externas', '=', $id_item_cbr)
                ->delete();
            $response = true;
        } catch (\Exception $e) {
            $response = false;
        }

        return response()->json(['status' => 'ok', 'response' => $response], 200);
    }

    public function save_mate(Request $request)
    {
        $idoti = (int) $request->input("idoti");
        $data  = $request->input("data");

        for ($i = 0; $i < count($data); $i++) {

            $idmateriales_obr_ext = isset($data[$i]["idmateriales_obr_ext"]) ? $data[$i]["idmateriales_obr_ext"] : 0;
            $material             = isset($data[$i]["material"]) ? $data[$i]["material"] : '';

            $materiales_obr_idcode   = isset($data[$i]["idmateriales"]) ? $data[$i]["idmateriales"] : 0;
            $date                    = isset($data[$i]["date"]) ? $data[$i]["date"] : 0;
            $ipid                    = isset($data[$i]["ipid"]) ? $data[$i]["ipid"] : null;
            $materiales_obr_cantidad = (float) isset($data[$i]["materiales_obr_cantidad"]) ? $data[$i]["materiales_obr_cantidad"] : 0;

            if ($idmateriales_obr_ext == 0 && $material != '') {

                $insert = DB::table('materiales_obr_ext')
                    ->insert([
                        'materiales_obr_idcode'   => $materiales_obr_idcode,
                        'date'                    => $date,
                        'id_oti'                  => $idoti,
                        'materiales_obr_cantidad' => $materiales_obr_cantidad,
                        'ipid'                    => $ipid,
                    ]);
            } else {

                $update = DB::table('materiales_obr_ext')
                    ->where('idmateriales_obr_ext', '=', $idmateriales_obr_ext)
                    ->update([
                        'materiales_obr_idcode'   => $materiales_obr_idcode,
                        'date'                    => $date,
                        'materiales_obr_cantidad' => $materiales_obr_cantidad,
                        'ipid'                    => $ipid,
                    ]);
            }
        }

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function search_mate(Request $request)
    {
        $id_oti = (int) $request->input("id_oti");

        try {
            $search = DB::table('materiales_obr_ext')
                ->join('materiales', 'materiales_obr_ext.materiales_obr_idcode', '=', 'materiales.idmateriales')
                ->where('id_oti', '=', $id_oti)
                ->select('materiales_obr_ext.*', 'materiales.*', 'materiales.code as material')
                ->get();
            $response = true;
        } catch (\Exception $e) {
            $response = false;
        }

        return response()->json(['status' => 'ok', 'search' => $search, 'response' => $response], 200);
    }

    public function delete_mate(Request $request)
    {
        $id_mate_cbr = (int) $request->input("id_mate_cbr");

        try {
            $delete = DB::table('materiales_obr_ext')
                ->where('idmateriales_obr_ext', '=', $id_mate_cbr)
                ->delete();

            $response = true;
        } catch (Exception $e) {
            $response = false;
        }

        return response()->json(['status' => 'ok', 'response' => $response], 200);
    }

    public function save_activity(Request $request)
    {
        $idoti = (int) $request->input("idoti");
        $data  = $request->input("data");

        for ($i = 0; $i < count($data); $i++) {

            $idactivity_externas         = isset($data[$i]["idactivity_externas"]) ? $data[$i]["idactivity_externas"] : 0;
            $activity_externas_date      = isset($data[$i]["activity_externas_date"]) ? $data[$i]["activity_externas_date"] : '';
            $activity_externas_queantity = (float) isset($data[$i]["activity_externas_queantity"]) ? $data[$i]["activity_externas_queantity"] : 0;
            $activity_externas_state     = isset($data[$i]["activity_externas_state"]) ? $data[$i]["activity_externas_state"] : 0;
            $idactivity                  = isset($data[$i]["idactivity"]) ? $data[$i]["idactivity"] : 0;
            $idemployee                  = isset($data[$i]["idemployee"]) ? $data[$i]["idemployee"] : '';
            $value                       = isset($data[$i]["value"]) ? $data[$i]["value"] : 0;
            $activity_externas_datepago  = isset($data[$i]["activity_externas_datepago"]) ? $data[$i]["activity_externas_datepago"] : '';

            if ($idactivity_externas == 0 && $idactivity != 0) {

                $insert = DB::table('activity_externas')
                    ->insert([
                        'activity_externas_oti'       => $idoti,
                        'activity_externas_employe'   => $idemployee,
                        'activity_externas_date'      => $activity_externas_date,
                        'activity_externas_queantity' => $activity_externas_queantity,
                        'activity_externas_value'     => $value,
                        'activity_externas_state'     => $activity_externas_state,
                        'activity_externas_datepago'  => $activity_externas_datepago,
                        'activity_externas_activity'  => $idactivity,
                    ]);

            } else {

                $update = DB::table('activity_externas')
                    ->where('idactivity_externas', '=', $idactivity_externas)

                    ->update([
                        'activity_externas_employe'   => $idemployee,
                        'activity_externas_date'      => $activity_externas_date,
                        'activity_externas_queantity' => $activity_externas_queantity,
                        'activity_externas_value'     => $value,
                        'activity_externas_state'     => $activity_externas_state,
                        'activity_externas_datepago'  => $activity_externas_datepago,
                        'activity_externas_activity'  => $idactivity,
                    ]);
            }
        }

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function search_activity(Request $request)
    {
        $idoti = $request->input("idoti");

        $search = DB::table('activity_externas')
            ->leftjoin('activities', 'activity_externas.activity_externas_activity', '=', 'activities.idactivities')
            ->leftjoin('employees', 'activity_externas.activity_externas_employe', '=', 'employees.Users_id_identification')
            ->where('activity_externas_oti', '=', $idoti)
            ->orderBy('idactivity_externas', 'ASC')
            ->select('activity_externas.*', DB::raw("CONCAT(name,' ',last_name)as employee"), 'activity_externas.activity_externas_employe as idemployee'

                , 'activity_externas.activity_externas_value as value', 'activities.activities_name as activity', 'activity_externas.activity_externas_activity as idactivity')
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function search_params_oti(Request $request)
    {
        $idoti = (int) $request->input("idoti");

        $response = DB::table('obr_externa')
            ->join('type_obraext', 'obr_externa.obr_tipo', '=', 'type_obraext.idtype_obraext')
            ->join('municipality', 'obr_externa.obr_municipio', '=', 'municipality.idmunicipality')
            ->join('obr_anillos', 'obr_externa.idobr_externa', '=', 'obr_anillos.idobr_externa')
            ->where('idobr_anillos', '=', $idoti)
            ->get();
        return response()->json(['status' => 'ok', 'response' => $response], 200);

    }

    public function autocomplete_addrees(Request $request)
    {
        $term = $request->input("term");

        $response = DB::table('obr_externa')
            ->where('obr_direccion', 'like', '%' . $term . '%')
            ->orderBy('obr_direccion', 'ASC')
            ->take(10)
            ->get();
        return response()->json(['status' => 'ok', 'response' => $response], 200);
    }

    public function search_params_addrees(Request $request)
    {
        $adrees   = $request->input("addrees");
        $contract = (int) $request->input("contract");

        $response = DB::table('obr_externa')
            ->join('type_obraext', 'obr_externa.obr_tipo', '=', 'type_obraext.idtype_obraext')
            ->join('municipality', 'obr_externa.obr_municipio', '=', 'municipality.idmunicipality')
            ->where('obr_direccion', '=', $adrees)
            ->where('obr_externa.obr_contrato', '=', $contract)
            ->get();
        return response()->json(['status' => 'ok', 'response' => $response], 200);
    }

    public function search_params_consec(Request $request)
    {
        $consec   = (int) $request->input("consec");
        $contract = (int) $request->input("contract");

        $response = DB::table('obr_externa')
            ->join('type_obraext', 'obr_externa.obr_tipo', '=', 'type_obraext.idtype_obraext')
            ->join('contract', 'obr_externa.obr_contrato', '=', 'contract.idcontract')
            ->join('municipality', 'obr_externa.obr_municipio', '=', 'municipality.idmunicipality')
            ->where('idobr_externa', '=', $consec)
            ->where('obr_externa.obr_contrato', '=', $contract)
            ->get();
        return response()->json(['status' => 'ok', 'response' => $response], 200);
    }

    public function delete_detalle_obra(Request $request)
    {
        $iddobr = (int) $request->input("iddobr");
        try {
            $delete = DB::table('detalles_obra')
                ->where('iddetalles_obra', '=', $iddobr)
                ->delete();

            $response = true;
        } catch (\Exception $e) {
            $response = false;
        }

        return response()->json(['status' => 'ok', 'response' => $response], 200);
    }

    public function send_image(Request $request)
    {
        $idoti         = $_POST['idoti'];
        $oti           = $_POST['oti'];
        $company_name  = $_POST['company_name'];
        $contract_name = $_POST['contract_name'];
        $company_name  = str_replace(' ', '', $company_name);
        $image         = $_FILES;

        foreach ($image as &$image) {

            $name    = $image['name'];
            $file    = $image['tmp_name'];
            $type    = $image['type'];
            $hoy     = date("Y_m_d_H_i_s");
            $Typedoc = explode("/", $type);

            if ($Typedoc[1] == 'jpeg' or $Typedoc[1] == 'png') {

                $namefile = $name . '-' . $oti . '-' . $hoy . '.' . $Typedoc[1];
                $carpeta  = public_path('/public/externas/images/' . $company_name . '/' . $contract_name . '/' . $oti . '/');

                if (!File::exists($carpeta)) {
                    $path = public_path('/public/externas/images/' . $company_name . '/' . $contract_name . '/' . $oti . '/');
                    File::makeDirectory($path, 0777, true);
                }

                move_uploaded_file($file, $carpeta . $namefile);
            }

            if ($Typedoc[1] == 'pdf') {

                $namefile = $name . '-' . $oti . '-' . $hoy . '.' . $Typedoc[1];
                $carpeta  = public_path('/public/externas/pdf/' . $company_name . '/' . $contract_name . '/' . $oti . '/');
            }
        }

    }

    public function import_presu(Request $request)
    {
        $data  = $request->input("data");
        $idoti = $request->input("idoti");

        for ($i = 0; $i < count($data); $i++) {

            $A = isset($data[$i]["A"]) ? $data[$i]["A"] : ''; // pedido
            $B = isset($data[$i]["B"]) ? $data[$i]["B"] : ''; // pedido
            $C = isset($data[$i]["C"]) ? $data[$i]["C"] : ''; // pedido
            $D = isset($data[$i]["D"]) ? $data[$i]["D"] : ''; // pedido

            $inser_presu = DB::table('item_presupuesto')
                ->insert([
                    'item_presupuesto_codeid'   => $B,
                    'item_presupuesto_cantidad' => $C,
                    'item_presupuest_idoti'     => $A,
                ]);
        }

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function search_obr_dispachet(Request $request)
    {
        $id_obr = $request->input("id_obr");

        $search = DB::table('obr_externa')
            ->where('idobr_externa', '=', $id_obr)
            ->first();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function delete_activity(Request $request)
    {
        $id_activity = $request->input("id_activity");

        $delete = DB::table('activity_externas')
            ->where('idactivity_externas', '=', $id_activity)
            ->delete();

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function update_activity(Request $request)
    {
        $id_activity = $request->input("id_activity");

        $search = DB::table('activity_externas')
            ->leftjoin('activities', 'activity_externas.activity_externas_activity', '=', 'activities.idactivities')
            ->leftjoin('employees', 'activity_externas.activity_externas_employe', '=', 'employees.Users_id_identification')
            ->where('idactivity_externas', '=', $id_activity)
            ->orderBy('idactivity_externas', 'ASC')
            ->select('activity_externas.*', DB::raw("CONCAT(name,' ',last_name)as employee"), 'activity_externas.activity_externas_employe as idemployee'

                , 'activity_externas.activity_externas_value as value', 'activities.activities_name as activity', 'activity_externas.activity_externas_activity as idactivity')
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function saveacta(Request $request)
    {
        $idoti   = $request->input("idoti");
        $addresm = $request->input("addresm");

        $id_actav       = $request->input("data.id_actav");
        $consecutive    = $request->input("data.consecutive");
        $acta           = $request->input("data.acta");
        $ficha          = $request->input("data.ficha");
        $user           = $request->input("data.user");
        $identification = $request->input("data.identification");
        $address        = $request->input("data.address");
        $phone          = $request->input("data.phone");
        $cel            = $request->input("data.cel");
        $email          = $request->input("data.email");
        $obs            = $request->input("data.obs");
        $id_oti         = $request->input("data.id_oti");
        $date           = $request->input("data.date");
        $type           = $request->input("data.type");
        $multiple       = $request->input("data.multiple");

        $idemployee     = $request->input("data.idemployee");
        $ipid           = $request->input("data.id_ipid");
        $Acueducto      = $request->input("data.acueducto");
        $Alcantarillado = $request->input("data.alcantarillado");
        $Gas            = $request->input("data.gas");
        $Energia        = $request->input("data.energia");
        $obsC           = $request->input("data.obsC");
        $company        = $request->input("company");
        $contract       = $request->input("contract");

        $search1 = 0;

        if ($type <= 3) {

            if (isset($acta)) {
                $search1 = DB::table('actasvecinda')
                    ->where('idoti', '=', $idoti)
                    ->where('acta', '=', $acta)
                    ->first();

                //   echo $search = 1;
                $search = count($search1);
            } else {

                $search = count($search1);

            }

        } else {

            $search = $search1;

        }

        if (isset($multiple)) {

        } else {

            $multiple = 'no';
        }

        if ($search > 0 && $multiple == "si") {

            $insert = DB::table('actasvecinda')
                ->insertGetId([
                    'consecutive'    => $consecutive,
                    'acta'           => $acta,
                    'ficha'          => $ficha,
                    'user'           => $user,
                    'identification' => $identification,
                    'address'        => $addresm,
                    'phone'          => $phone,
                    'cel'            => $cel,
                    'email'          => $email,
                    'obs'            => $obs,
                    'idoti'          => $idoti,
                    'date'           => $date,
                    'type'           => $type,
                    'multiple'       => $multiple,
                    'idemployee'     => $idemployee,
                    'id_ipid'        => $ipid,
                    'acueducto'      => $Acueducto,
                    'alcantarillado' => $Alcantarillado,
                    'gas'            => $Gas,
                    'energia'        => $Energia,
                    'obsC'           => $obsC,
                    'idcompany'      => $company,
                    'idcontract'     => $contract,

                ]);
            $respose = false;

        }

        if ($search == 0) {

            $insert = DB::table('actasvecinda')
                ->insertGetId([
                    'consecutive'    => $consecutive,
                    'acta'           => $acta,
                    'ficha'          => $ficha,
                    'user'           => $user,
                    'identification' => $identification,
                    'address'        => $address,
                    'phone'          => $phone,
                    'cel'            => $cel,
                    'email'          => $email,
                    'obs'            => $obs,
                    'idoti'          => $idoti,
                    'date'           => $date,
                    'type'           => $type,
                    'idemployee'     => $idemployee,
                    'id_ipid'        => $ipid,
                    'acueducto'      => $Acueducto,
                    'alcantarillado' => $Alcantarillado,
                    'gas'            => $Gas,
                    'energia'        => $Energia,
                    'obsC'           => $obsC,
                    'idcompany'      => $company,
                    'idcontract'     => $contract,

                ]);
            $respose = false;

        }

        if ($search > 0 && $multiple == "no") {
            $insert = 0;

            $respose = true;
        }

        return response()->json(['status' => 'ok', 'response' => $respose, 'id_acta' => $insert], 200);
    }

    public function search_actas(Request $request)
    {
        $idacta = $request->input("idacta");

        $search = DB::table('actasvecinda')
            ->leftjoin('employees', 'actasvecinda.idemployee', '=', 'employees.idemployees')
            ->where('id_actav', '=', $idacta)
            ->select('actasvecinda.*', DB::raw("CONCAT(name,' ',last_name)as employee"))
            ->first();

        $address              = $search->id_address;
        $search_adreess       = null;
        $search_adreess_actas = DB::table('address_actas')
            ->where('id_acta', $idacta)
            ->first();
        // var_dump($search_adreess_actas);

        if ($search_adreess_actas == null) {

        } else {
            $search_adreess = DB::table('actasvecinda')
                ->where('id_actav', $search_adreess_actas->id_address)
                ->first();
        }

        return response()->json(['status' => 'ok', 'response' => $search, 'address' => $search_adreess], 200);
    }

    public function search_act(Request $request)
    {
        $idoti = $request->input("idoti");

        $search = DB::table('actasvecinda')
            ->where('idoti', '=', $idoti)
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function update_acta(Request $request)
    {
        $idoti          = $request->input("idoti");
        $id_actav       = $request->input("data.id_actav");
        $consecutive    = $request->input("data.consecutive");
        $acta           = $request->input("data.acta");
        $ficha          = $request->input("data.ficha");
        $user           = $request->input("data.user");
        $identification = $request->input("data.identification");
        $address        = $request->input("data.address");
        $phone          = $request->input("data.phone");
        $cel            = $request->input("data.cel");
        $email          = $request->input("data.email");
        $obs            = $request->input("data.obs");
        $id_oti         = $request->input("data.id_oti");
        $date_send      = $request->input("data.date_send");
        $date           = $request->input("data.date");
        $type           = $request->input("data.type");

        $idemployee     = $request->input("data.idemployee");
        $ipid           = $request->input("data.id_ipid");
        $Acueducto      = $request->input("data.acueducto");
        $Alcantarillado = $request->input("data.alcantarillado");
        $Gas            = $request->input("data.gas");
        $Energia        = $request->input("data.energia");
        $obsC           = $request->input("data.obsC");
        $cierre         = $request->input("data.cierre");
        $acta_ini       = $request->input("data.acta_ini");

        $insert = DB::table('actasvecinda')
            ->where('id_actav', '=', $id_actav)
            ->update([
                'idoti'          => $idoti,
                'acta'           => $acta,
                'ficha'          => $ficha,
                'user'           => $user,
                'identification' => $identification,
                'address'        => $address,
                'phone'          => $phone,
                'cel'            => $cel,
                'email'          => $email,
                'obs'            => $obs,
                'date_send'      => $date_send,
                'date'           => $date,
                'type'           => $type,
                'idemployee'     => $idemployee,
                'id_ipid'        => $ipid,
                'acueducto'      => $Acueducto,
                'alcantarillado' => $Alcantarillado,
                'gas'            => $Gas,
                'energia'        => $Energia,
                'obsC'           => $obsC,
                'cierre'         => $cierre,
                'acta_ini'       => $acta_ini,

            ]);

        if ($cierre == 1) {

            $search = DB::table('pibote_actas')
                ->where('id_cierre', $id_actav)
                ->first();

            if (!$search) {

                $insert = DB::table('pibote_actas')
                    ->insert([
                        'id_actainicio' => $acta_ini,
                        'id_cierre'     => $id_actav,
                    ]);
            } else {
                $insert = DB::table('pibote_actas')
                    ->where('id_pibote', $search->id_pibote)
                    ->update([
                        'id_actainicio' => $acta_ini,
                    ]);
            }
        }

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function oti_movil(Request $request)
    {

        $id_oti    = $request->input("id_oti");
        $idcontrac = $request->input("idcontrac");

        $search = DB::table('obr_anillos')
            ->where('obr_anillos_oti', '=', $id_oti)
            ->leftjoin('obr_externa', 'obr_anillos.idobr_externa', '=', 'obr_externa.idobr_externa')
            ->leftjoin('contract', 'contract.idcontract', '=', 'obr_externa.obr_contrato')
            ->leftjoin('business', 'contract.id_empresa', '=', 'business.idbusiness')
            ->where('obr_contrato', '=', $idcontrac)
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function create_acta(Request $request)
    {

        $dir = $request->input("dir");

        $searcta = DB::table('actasvecinda')
            ->where('acta', '=', $dir)
            ->where('idcompany', '=', 1)
            ->where('cierre', '=', 1)
            ->first();

        if (!$searcta) {

            $insertacta = DB::table('actasvecinda')
                ->insertGetId([
                    'acta'      => $dir,
                    'idcompany' => 1,
                    'cierre'    => 1,

                ]);
            $response = true;
        } else {
            $response   = false;
            $insertacta = $searcta->id_actav;

        }

        return response()->json(['status' => 'ok', 'response' => $response, 'id_acta' => $insertacta], 200);
    }

    public function create_acta1(Request $request)
    {

        $dir = $request->input("dir");

        $searcta = DB::table('actasvecinda')
            ->where('acta', '=', $dir)
            ->where('idcompany', '=', 4)
            ->first();

        if (!$searcta) {

            $insertacta = DB::table('actasvecinda')
                ->insertGetId([
                    'acta'      => $dir,
                    'idcompany' => 4,

                ]);
            $response = true;
        } else {
            $response   = false;
            $insertacta = $searcta->id_actav;

        }

        return response()->json(['status' => 'ok', 'response' => $response, 'id_acta' => $insertacta], 200);
    }

    public function acta_image(Request $request)
    {

        //$param = $_POST['params'];

        //$obj = json_decode($param, true);

        $idacta = $_POST['idacta'];

        // $contract      = $param['contract'];
        $company_name  = 'CONSORCIOCYC';
        $contract_name = 'CW23202';
        //$obr_anillos_oti = $obj['obr_anillos_oti'];
        //$obr_consecutivo = $obj['obr_consecutivo'];
        //$idobr_anillos   = $obj['idobr_anillos'];

        $image = $_FILES;

        $name = $image['file']['name'];
        $file = $image['file']['tmp_name'];

        $type    = $image['file']['type'];
        $hoy     = date("Y-m-d H:i");
        $Typedoc = explode(".", $name);

        $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        $strlength = strlen($characters);

        $random       = '';
        $company_name = str_replace(' ', '', $company_name);

        for ($i = 0; $i < 15; $i++) {
            $random .= $characters[rand(0, $strlength - 1)];
        }

        if ($Typedoc[1] == 'jpeg' or $Typedoc[1] == 'jpg') {

            $namefile = $random . '.' . $Typedoc[1];
            $carpeta  = public_path('/public/externas/actas/images/' . $company_name . '/' . $contract_name . '/' . '/' . $idacta . '/');

            if (!File::exists($carpeta)) {
                $path = public_path('/public/externas/actas/images/' . $company_name . '/' . $contract_name . '/' . '/' . $idacta . '/');
                File::makeDirectory($path, 0777, true);
            }
            $url = '/externas/actas/images/' . $company_name . '/' . $contract_name . '/' . $idacta . '/';
            move_uploaded_file($file, $carpeta . $namefile);

            ExternalController::insert_image($idacta, $namefile, $url, $idacta, $hoy);
        }

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function acta_image1(Request $request)
    {

        //$param = $_POST['params'];

        //$obj = json_decode($param, true);

        $idacta = $_POST['idacta'];

        // $contract      = $param['contract'];
        $company_name  = 'CON-GAS';
        $contract_name = 'CW-14787';
        //$obr_anillos_oti = $obj['obr_anillos_oti'];
        //$obr_consecutivo = $obj['obr_consecutivo'];
        //$idobr_anillos   = $obj['idobr_anillos'];

        $image = $_FILES;

        $name = $image['file']['name'];
        $file = $image['file']['tmp_name'];

        $type    = $image['file']['type'];
        $hoy     = date("Y-m-d H:i");
        $Typedoc = explode(".", $name);

        $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        $strlength = strlen($characters);

        $random       = '';
        $company_name = str_replace(' ', '', $company_name);

        for ($i = 0; $i < 15; $i++) {
            $random .= $characters[rand(0, $strlength - 1)];
        }

        if ($Typedoc[1] == 'jpeg' or $Typedoc[1] == 'jpg') {

            $namefile = $random . '.' . $Typedoc[1];
            $carpeta  = public_path('/public/externas/actas/images/' . $company_name . '/' . $contract_name . '/' . '/' . $idacta . '/');

            if (!File::exists($carpeta)) {
                $path = public_path('/public/externas/actas/images/' . $company_name . '/' . $contract_name . '/' . '/' . $idacta . '/');
                File::makeDirectory($path, 0777, true);
            }
            $url = '/externas/actas/images/' . $company_name . '/' . $contract_name . '/' . $idacta . '/';
            move_uploaded_file($file, $carpeta . $namefile);

            ExternalController::insert_image($idacta, $namefile, $url, $idacta, $hoy);
        }

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function insert_image($idacta1, $namefile, $url, $idacta, $hoy)
    {

        $insert = DB::table('image_actas')
            ->insert([
                'name_image' => $namefile,
                'url'        => $url,
                'id_acta'    => $idacta,
                'date'       => $hoy,
            ]);
    }

    public function send_mail(Request $request)
    {

        $this->acta    = $request->input("data.acta");
        $this->user    = $request->input("data.user");
        $this->address = $request->input("data.address");
        $this->mail    = $request->input("data.email");

        $this->idacta = $request->input("data.id_actav");

        $this->mensage = 'esto es una prueba del mensaje';
        $hoy           = date("Y:m:d H:i");
        $data          = array("address" => $this->address, "user" => $this->user, "acta" => $this->acta, "hoy" => $hoy);

        $update_acta = DB::table('actasvecinda')
            ->where('id_actav', $this->idacta)
            ->update([

                'date_send' => $hoy,
            ]);

        Mail::send('emails.welcome', $data, function ($message) {

            $search = DB::table('actasvecinda')
                ->leftjoin('image_actas', 'image_actas.id_acta', '=', 'actasvecinda.id_actav')
                ->where('id_actav', '=', $this->idacta)
                ->get();

            $message->from('actasdevecindad@grupoempresarialcyc.com', 'Actad de Vecindad #' . $this->acta);
            $message->to($this->mail, $this->user);
            $message->cc('actasdevecindad@grupoempresarialcyc.com', $this->user);
            $message->subject('Actas de Vecindad');

            $carpeta = public_path('/public');

            foreach ($search as $search) {

                $message->attach($carpeta . $search->url . $search->name_image);
            }
        });

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function imagesend_acta(Request $request)
    {

        // $contract      = $param['contract'];

        $company_name    = $_POST["company_name"];
        $contract_name   = $_POST['contract_name'];
        $obr_anillos_oti = $_POST['idoti'];
        $oti             = $_POST['oti'];
        //$obr_consecutivo = $_POST['consecutive'];

        $idacta = $_POST['acta'];

        $company_name = str_replace(' ', '', $company_name);
        $image        = $_FILES;
        $hoy          = date("Y-m-d H:i");

        foreach ($image as &$image) {

            $name = $image['name'];
            $file = $image['tmp_name'];
            $type = $image['type'];

            $Typedoc = explode("/", $type);

            $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

            $strlength = strlen($characters);

            $random       = '';
            $company_name = str_replace(' ', '', $company_name);

            for ($i = 0; $i < 15; $i++) {
                $random .= $characters[rand(0, $strlength - 1)];
            }

            if ($Typedoc[1] == 'jpeg' or $Typedoc[1] == 'jpg') {

                $namefile = $random . '.' . $Typedoc[1];
                $carpeta  = public_path('/public/externas/actas/images/' . $company_name . '/' . $contract_name . '/' . $idacta . '/');

                if (!File::exists($carpeta)) {

                    $path = public_path('/public/externas/actas/images/' . $company_name . '/' . $contract_name . '/' . $idacta . '/');
                    File::makeDirectory($path, 0777, true);

                }
                //$img = Image::make($file)->resize(1920, 1080);

                $url = '/externas/actas/images/' . $company_name . '/' . $contract_name . '/' . $idacta . '/';
                move_uploaded_file($file, $carpeta . $namefile);

                //$img->save($carpeta . $namefile, 50);

                ExternalController::insert_image($idacta, $namefile, $url, $idacta, $hoy);
                // public function insert_image($idacta1, $namefile, $url, $idacta, $hoy)
            }

            if ($Typedoc[1] == 'pdf') {

                $namefile = $random . '.' . $Typedoc[1];
                $carpeta  = public_path('/public/externas/actas/pdf/' . $company_name . '/' . $contract_name . '/' . $obr_consecutivo . '/' . $obr_anillos_oti . '/' . $idacta . '/');
                if (!File::exists($carpeta)) {
                    $path = public_path('/public/externas/actas/pdf/' . $company_name . '/' . $contract_name . '/' . $obr_consecutivo . '/' . $obr_anillos_oti . '/' . $idacta . '/');
                    File::makeDirectory($path, 0777, true);
                }
                $url = '/externas/actas/pdf/' . $company_name . '/' . $contract_name . '/' . $obr_consecutivo . '/' . $oti . '/' . $idacta . '/';
                move_uploaded_file($file, $carpeta . $namefile);

                ExternalController::insert_image($idacta, $obr_anillos_oti, $namefile, $url, $idacta, $hoy);
            }

        }
        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function search_imageactas(Request $request)
    {
        $idacta = $request->input("idacta");

        $search = DB::table('image_actas')
            ->where('id_acta', $idacta)
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function lis_type_acta()
    {
        $sarch = DB::table('list_actas')
            ->get();
        return response()->json(['status' => 'ok', 'sarch' => $sarch], 200);
    }

    public function searchidobr(Request $request)
    {

        $idoti = $request->input("idoti");

        $search = DB::table('obr_anillos')
            ->join('obr_externa', 'obr_anillos.idobr_externa', '=', 'obr_externa.idobr_externa')
            ->where('obr_anillos.idobr_anillos', $idoti)
            ->first();

        return response()->json(['status' => 'ok', 'sarch' => $search], 200);
    }

    public function save_items_actas(Request $request)
    {
        $idacta     = (int) $request->input("id_acta");
        $idoti      = (int) $request->input("idoti");
        $data       = $request->input("data");
        $id_address = $request->input("id_address");

        $search = DB::table('address_actas')
            ->where('id_acta', $idacta)
            ->first();

        if ($search == null) {

            $insert = DB::table('address_actas')
                ->insert([
                    'id_address' => $id_address,
                    'id_acta'    => $idacta,
                ]);

        } else {

            $update = DB::table('address_actas')
                ->where('id_acta', $idacta)
                ->update([
                    'id_address' => $id_address,

                ]);

        }

        for ($i = 0; $i < count($data); $i++) {
            $id_items_actas = isset($data[$i]["id_items_actas"]) ? $data[$i]["id_items_actas"] : null;

            $item         = isset($data[$i]["item"]) ? $data[$i]["item"] : null;
            $iditem_cobro = isset($data[$i]["iditem_cobro"]) ? $data[$i]["iditem_cobro"] : null;
            $date         = isset($data[$i]["date"]) ? $data[$i]["date"] : null;
            $quantity     = (float) isset($data[$i]["quantity"]) ? $data[$i]["quantity"] : null;
            $id_state     = isset($data[$i]["id_state"]) ? $data[$i]["id_state"] : null;
            $acta         = isset($data[$i]["acta"]) ? $data[$i]["acta"] : null;
            $date_acta    = isset($data[$i]["date_acta"]) ? $data[$i]["date_acta"] : null;
            $quanity_acta = (float) isset($data[$i]["quanity_acta"]) ? $data[$i]["quanity_acta"] : null;

            $l1 = (float) isset($data[$i]["l1"]) ? $data[$i]["l1"] : null;
            $l2 = (float) isset($data[$i]["l2"]) ? $data[$i]["l2"] : null;
            $e  = (float) isset($data[$i]["e"]) ? $data[$i]["e"] : null;

            if ($id_items_actas == null && $item != null) {

                $insert = DB::table('items_actas')
                    ->insert([
                        'id_items'     => $iditem_cobro,
                        'date'         => $date,
                        'quantity'     => $quantity,
                        'id_state'     => $id_state,
                        'quanity_acta' => $quanity_acta,
                        'date_acta'    => $date_acta,
                        'acta'         => $acta,
                        'id_oti'       => $idoti,
                        'id_acta'      => $idacta,
                        'l1'           => $l1,
                        'l2'           => $l2,
                        'e'            => $e,

                    ]);
            } else {
                $update = DB::table('items_actas')
                    ->where('id_items_actas', '=', $id_items_actas)
                    ->update([

                        'date'      => $date,
                        'quantity'  => $quantity,
                        'id_state'  => $id_state,
                        'acta'      => $acta,
                        'date_acta' => $date_acta,
                        'l1'        => $l1,
                        'l2'        => $l2,
                        'e'         => $e,

                    ]);
            }

        }

        $items_response = ExternalController::search_item_actas($idacta);

        return response()->json(['status' => 'ok', 'response' => true, 'result' => $items_response], 200);
    }

    public function search_item_actas($id_acta)
    {

        $serach = DB::table('items_actas')
            ->join('item_cobro', 'items_actas.id_items', '=', 'item_cobro.iditem_cobro')
            ->where('id_acta', '=', $id_acta)
            ->select('items_actas.*', 'item_cobro.item_cobro_name', 'item_cobro.item_cobro_code as item_cobro_code', 'item_cobro.item_cobro_code as item', 'item_cobro.iditem_cobro as iditem_cobro', 'item_cobro.item_cobro_unidad')
            ->get();

        return $serach;
    }

    public function search_items_actas(Request $request)
    {
        $idacta         = (int) $request->input("id_acta");
        $items_response = ExternalController::search_item_actas($idacta);

        return response()->json(['status' => 'ok', 'sarch' => $items_response], 200);
    }

    public function delet_items_actas(Request $request)
    {
        $id_item = (int) $request->input("id_item");
        $id_acta = (int) $request->input("id_acta");

        try {

            $delete = DB::table('items_actas')
                ->where('id_items_actas', '=', $id_item)
                ->delete();
            $response = true;

        } catch (\Exception $e) {

            $response = false;
        }
        $items_response = ExternalController::search_item_actas($id_acta);
        return response()->json(['status' => 'ok', 'response' => $response, 'result' => $items_response], 200);
    }

    public function auto_acta(Request $request)
    {

        $term     = $request->input("term");
        $company  = $request->input("company");
        $contract = $request->input("contract");

        $acta = DB::table('actasvecinda')

            ->where('idcompany', $company)
            ->where('acta', 'like', '%' . $term)

            ->take(10)
            ->get();
        return response()->json(['status' => 'ok', 'acta' => $acta], 200);
    }

    public function auto_user(Request $request)
    {

        $term     = $request->input("term");
        $company  = $request->input("company");
        $contract = $request->input("contract");

        $user = DB::table('actasvecinda')
            ->where('user', 'like', $term . '%')
            ->where('idcompany', $company)
            ->take(10)
            ->get();
        return response()->json(['status' => 'ok', 'user' => $user], 200);
    }

    public function auto_address(Request $request)
    {

        $term     = $request->input("term");
        $company  = $request->input("company");
        $contract = $request->input("contract");

        $address = DB::table('actasvecinda')

            ->where('address', 'like', '%' . $term . '%')
            ->where('idcompany', $company)
            ->leftjoin('list_actas', 'list_actas.id_type_acta', '=', 'actasvecinda.type')
            ->take(15)
            ->get();
        return response()->json(['status' => 'ok', 'address' => $address], 200);
    }

    public function search_acta(Request $request)
    {

        $acta   = $request->input("acta");
        $search = DB::table('actasvecinda')
            ->where('id_actav', '=', $acta)
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function search_user(Request $request)
    {

        $user    = $request->input("user");
        $company = $request->input("company");
        $search  = DB::table('actasvecinda')
            ->where('user', '=', $user)
            ->where('idcompany', $company)
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function search_address(Request $request)
    {
        $company = $request->input("company");
        $address = $request->input("address");

        $search = DB::table('actasvecinda')
            ->where('address', '=', $address)
            ->where('idcompany', $company)
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function search_idoti(Request $request)
    {

        $address = $request->input("address");
        $search  = DB::table('actasvecinda')
            ->where('address', '=', $address)

            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function type_gans()
    {

        $search = DB::table('type_gans')
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);

    }

    public function save_dobra_gerencial(Request $request)
    {
        $id_detalle = (int) $request->input("id_detalle");
        $data       = $request->input("data");

        for ($i = 0; $i < count($data); $i++) {

            $item_presupuesto_cantidad = (float) isset($data[$i]["d_cantidad"]) ? $data[$i]["d_cantidad"] : null;
            $item_presupuesto_class    = (int) isset($data[$i]["item_presupuesto_class"]) ? $data[$i]["item_presupuesto_class"] : null;
            $item                      = (int) isset($data[$i]["item"]) ? $data[$i]["item"] : null;
            $iditem_cobro              = (int) isset($data[$i]["iditem_cobro"]) ? $data[$i]["iditem_cobro"] : null;
            $idd_obra                  = isset($data[$i]["idd_obra_ge"]) ? $data[$i]["idd_obra_ge"] : null;

            if ($item != null && $idd_obra == null) {

                $save = DB::table('items_gerenciales')
                    ->insert([
                        'd_clasificacion' => $item_presupuesto_class,
                        'id_item'         => $iditem_cobro,
                        'd_cantidad'      => $item_presupuesto_cantidad,
                        'd_detalle'       => $id_detalle,

                    ]);

            } else {

                $update = DB::table('items_gerenciales')
                    ->where('idd_obra_ge', '=', $idd_obra)
                    ->update([
                        'd_clasificacion' => $item_presupuesto_class,
                        'id_item'         => $iditem_cobro,
                        'd_cantidad'      => $item_presupuesto_cantidad,
                    ]);

            }
        }
        return response()->json(['status' => 'ok', 'search' => true], 200);
    }

    public function search_dobra_gerencial(Request $request)
    {
        $id_detalle = (int) $request->input("id_detalle");

        $search = DB::table('items_gerenciales')
            ->join('item_cobro', 'items_gerenciales.id_item', '=', 'item_cobro.iditem_cobro')
            ->where('d_detalle', '=', $id_detalle)
            ->select('items_gerenciales.idd_obra_ge', 'items_gerenciales.d_clasificacion as item_presupuesto_class', 'items_gerenciales.d_cantidad as d_cantidad', 'item_cobro.item_cobro_name', 'item_cobro.item_cobro_code as item_cobro', 'item_cobro.item_cobro_code as item', 'item_cobro.iditem_cobro as iditem_cobro', 'item_cobro.item_cobro_valor as valor')
            ->get();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function delete_det_itemp_gen(Request $request)
    {
        $idd_obra_ge = (int) $request->input("idd_obra_ge");

        try {

            $delete = DB::table('items_gerenciales')
                ->where('idd_obra_ge', '=', $idd_obra_ge)
                ->delete();
            $response = true;
        } catch (\Exception $e) {
            $response = false;
        }

        return response()->json(['status' => 'ok', 'response' => $response], 200);
    }

    public function saveow(Request $request)
    {
        $idoti = $request->input("idoti");
        $ow    = $request->input("ow");

        for ($i = 0; $i < count($ow); $i++) {

            $iditem_ow    = isset($ow[$i]["iditem_ow"]) ? $ow[$i]["iditem_ow"] : null;
            $iditem_cobro = isset($ow[$i]["iditem_cobro"]) ? $ow[$i]["iditem_cobro"] : null;
            $cantidad     = (float) isset($ow[$i]["cantidad"]) ? $ow[$i]["cantidad"] : null;
            $ipid         = isset($ow[$i]["ipid"]) ? $ow[$i]["ipid"] : null;
            $idmaterial   = isset($ow[$i]["idmaterial"]) ? $ow[$i]["idmaterial"] : null;
            $statusdes    = isset($ow[$i]["statusdes"]) ? $ow[$i]["statusdes"] : null;

            if ($iditem_ow == null) {

                $insert = DB::table('items_ow')
                    ->insert([
                        'iditem_cobro' => $iditem_cobro,
                        'cantidad'     => $cantidad,
                        'idoti'        => $idoti,
                        'id_ipd'       => $ipid,
                        'idmaterial'   => $idmaterial,
                        'statusdes'    => $statusdes,

                    ]);
            } else {

                $update = DB::table('items_ow')
                    ->where('iditem_ow', $iditem_ow)
                    ->update([
                        'iditem_cobro' => $iditem_cobro,
                        'cantidad'     => $cantidad,
                        'idoti'        => $idoti,
                        'id_ipd'       => $ipid,
                        'idmaterial'   => $idmaterial,
                        'statusdes'    => $statusdes,

                    ]);
            }

        }

        return response()->json(['status' => 'ok', 'response' => true], 200);

    }

    public function list_ipid(Request $request)
    {

        $idoti  = $request->input("idoti");
        $search = DB::table('ipid')
            ->where('id_oti', $idoti)
            ->select('ipid.*')
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);

    }

    public function search_ow(Request $request)
    {

        $idoti = $request->input("idoti");

        $search = DB::table('items_ow')
            ->leftjoin('item_cobro', 'items_ow.iditem_cobro', '=', 'item_cobro.iditem_cobro')
            ->leftjoin('materiales', 'items_ow.idmaterial', '=', 'materiales.idmateriales')
            ->leftjoin('ipid', 'items_ow.id_ipd', '=', 'ipid.idipd')
            ->where('idoti', '=', $idoti)
            ->select('items_ow.iditem_ow', 'items_ow.cantidad as cantidad', 'item_cobro.item_cobro_name', 'item_cobro.item_cobro_code as item_cobro_code', 'item_cobro.item_cobro_code as item', 'item_cobro.iditem_cobro as iditem_cobro', 'item_cobro.item_cobro_valor as valor', 'items_ow.id_ipd as ipid', 'item_cobro.item_cobro_unidad', 'materiales.description as desmate', 'materiales.code as codemate', 'items_ow.idmaterial'
                , 'materiales.code as codemate', 'items_ow.statusdes as statusdes')
            ->get();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function delete_itemsow(Request $request)
    {
        $iditem_ow = $request->input("iditem_ow");

        $delete = DB::table('items_ow')
            ->where('iditem_ow', $iditem_ow)
            ->delete();

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function idoti(Request $request)
    {
        $idoti = $request->input("idoti");

        $search = DB::table('obr_anillos')
            ->where('idobr_anillos', $idoti)
            ->select('obr_anillos.idobr_anillos', 'obr_anillos.obr_anillos_oti', 'obr_anillos.idobr_externa')
            ->first();

        $search_conse = DB::table('obr_externa')
            ->where('idobr_externa', $search->idobr_externa)
            ->select('obr_externa.obr_consecutivo')
            ->first();

        return response()->json(['status' => 'ok', 'search' => $search, 'search_conse' => $search_conse->obr_consecutivo], 200);
    }

    public function savetopo(Request $request)
    {

        $id_oti              = $request->input("id_oti");
        $topoproyect         = $request->input("data.topoproyect");
        $topotramo           = $request->input("data.topotramo");
        $topoipid            = $request->input("data.topoipid");
        $toponodeslength     = $request->input("data.toponodeslength");
        $topoDiameter        = $request->input("data.topoDiameter");
        $topoMaterial        = $request->input("data.topoMaterial");
        $ininode             = $request->input("data.ininode");
        $iniaddress          = $request->input("data.iniaddress");
        $iniTypeMaterial     = $request->input("data.iniTypeMaterial");
        $inidiameterMaterial = $request->input("data.inidiameterMaterial");
        $inicoorEste         = (float) $request->input("data.inicoorEste");
        $inicoorNorte        = $request->input("data.inicoorNorte");
        $inicotaKey          = $request->input("data.inicotaKey");
        $inicotaTerreno      = $request->input("data.inicotaTerreno");
        $iniObservation      = $request->input("data.iniObservation");
        $finnode             = $request->input("data.finnode");
        $finaddress          = $request->input("data.finaddress");
        $finTypeMaterial     = $request->input("data.finTypeMaterial");
        $findiameterMaterial = $request->input("data.findiameterMaterial");
        $fincoorEste         = $request->input("data.fincoorEste");
        $fincoorNorte        = $request->input("data.fincoorNorte");
        $fincotaKey          = $request->input("data.fincotaKey");
        $fincotaTerreno      = $request->input("data.fincotaTerreno");
        $finObservation      = $request->input("data.finObservation");
        $idipd               = $request->input("data.topoidipid");
        $topoDate            = $request->input("data.topoDate");
        $inimateid           = $request->input("data.inimateid");
        $finmateid           = $request->input("data.finmateid");

        $toponodoinicial = $request->input("data.toponodoinicial");
        $toponodofinal   = $request->input("data.toponodofinal");
        $insert          = DB::table('topografia')

            ->insertGetId([
                'idipd'               => $idipd,
                'toponodeslength'     => $toponodeslength,
                'topoDiameter'        => $topoDiameter,
                'id_oti'              => $id_oti,
                'id_material'         => $topoMaterial,
                'ininode'             => $ininode,
                'iniaddress'          => $iniaddress,
                'iniTypeMaterial'     => $iniTypeMaterial,
                'inidiameterMaterial' => $inidiameterMaterial,
                'inicoorEste'         => $inicoorEste,
                'inicoorNorte'        => $inicoorNorte,
                'inicotaKey'          => $inicotaKey,
                'inicotaTerreno'      => $inicotaTerreno,
                'iniObservation'      => $iniObservation,
                'finnode'             => $finnode,
                'finaddress'          => $finaddress,
                'finTypeMaterial'     => $finTypeMaterial,
                'findiameterMaterial' => $findiameterMaterial,
                'fincoorEste'         => $fincoorEste,
                'fincoorNorte'        => $fincoorNorte,
                'fincotaKey'          => $fincotaKey,
                'fincotaTerreno'      => $fincotaTerreno,
                'finObservation'      => $finObservation,
                'toponodoinicial'     => $toponodoinicial,
                'toponodofinal'       => $toponodofinal,
                'topoDate'            => $topoDate,
                'inimateid'           => $inimateid,
                'finmateid'           => $finmateid,

            ]);
        return response()->json(['status' => 'ok', 'response' => true, 'id' => $insert], 200);
    }

    public function searchtopo(Request $request)
    {
        $id_oti = $request->input("id_oti");

        $search = DB::table('topografia')
            ->where('id_oti', $id_oti)
            ->get();

        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function searchOne(Request $request)
    {
        $id_topo = $request->input("id_topo");

        $search = DB::table('topografia')
            ->leftjoin('ipid', 'ipid.idipd', '=', 'topografia.idipd')
            ->leftjoin('item_cobro', 'item_cobro.iditem_cobro', '=', 'ipid.items')
            ->where('id_topografia', $id_topo)
            ->select('topografia.*', 'item_cobro.item_cobro_name as topoMaterial', 'ipid.ipid as topoipid', 'ipid.idipd as topoidipid', 'ipid.items as id_material', DB::raw("(SELECT  item_cobro_name FROM item_cobro where iditem_cobro=topografia.inimateid)as inimate"), DB::raw("(SELECT item_cobro_name  FROM item_cobro where iditem_cobro=topografia.finmateid)as finmate"))
            ->first();

        return response()->json(['status' => 'ok', 'search' => $search], 200);

    }

    public function updatetopo(Request $request)
    {

        $id_oti              = $request->input("id_oti");
        $topoproyect         = $request->input("data.topoproyect");
        $topotramo           = $request->input("data.topotramo");
        $idipd               = $request->input("data.idipd");
        $toponodeslength     = $request->input("data.toponodeslength");
        $topoDiameter        = $request->input("data.topoDiameter");
        $topoMaterial        = $request->input("data.topoMaterial");
        $ininode             = $request->input("data.ininode");
        $iniaddress          = $request->input("data.iniaddress");
        $iniTypeMaterial     = $request->input("data.iniTypeMaterial");
        $inidiameterMaterial = $request->input("data.inidiameterMaterial");
        $inicoorEste         = (FLOAT) $request->input("data.inicoorEste");
        $inicoorNorte        = $request->input("data.inicoorNorte");
        $inicotaKey          = $request->input("data.inicotaKey");
        $inicotaTerreno      = $request->input("data.inicotaTerreno");
        $iniObservation      = $request->input("data.iniObservation");
        $finnode             = $request->input("data.finnode");
        $finaddress          = $request->input("data.finaddress");
        $finTypeMaterial     = $request->input("data.finTypeMaterial");
        $findiameterMaterial = $request->input("data.findiameterMaterial");
        $fincoorEste         = $request->input("data.fincoorEste");
        $fincoorNorte        = $request->input("data.fincoorNorte");
        $fincotaKey          = $request->input("data.fincotaKey");
        $fincotaTerreno      = $request->input("data.fincotaTerreno");
        $finObservation      = $request->input("data.finObservation");
        $id_topografia       = $request->input("data.id_topografia");
        $toponodoinicial     = $request->input("data.toponodoinicial");
        $toponodofinal       = $request->input("data.toponodofinal");
        $idipd               = $request->input("data.topoidipid");
        $topoDate            = $request->input("data.topoDate");
        $inimateid           = $request->input("data.inimateid");
        $finmateid           = $request->input("data.finmateid");

        $insert = DB::table('topografia')
            ->where('id_topografia', $id_topografia)
            ->update([
                'idipd'               => $idipd,
                'toponodeslength'     => $toponodeslength,
                'topoDiameter'        => $topoDiameter,
                'id_material'         => $topoMaterial,
                'ininode'             => $ininode,
                'iniaddress'          => $iniaddress,
                'iniTypeMaterial'     => $iniTypeMaterial,
                'inidiameterMaterial' => $inidiameterMaterial,
                'inicoorEste'         => $inicoorEste,
                'inicoorNorte'        => $inicoorNorte,
                'inicotaKey'          => $inicotaKey,
                'inicotaTerreno'      => $inicotaTerreno,
                'iniObservation'      => $iniObservation,
                'finnode'             => $finnode,
                'finaddress'          => $finaddress,
                'finTypeMaterial'     => $finTypeMaterial,
                'findiameterMaterial' => $findiameterMaterial,
                'fincoorEste'         => $fincoorEste,
                'fincoorNorte'        => $fincoorNorte,
                'fincotaKey'          => $fincotaKey,
                'fincotaTerreno'      => $fincotaTerreno,
                'finObservation'      => $finObservation,
                'toponodoinicial'     => $toponodoinicial,
                'toponodofinal'       => $toponodofinal,
                'topoDate'            => $topoDate,
                'inimateid'           => $inimateid,
                'finmateid'           => $finmateid,
            ]);

        return response()->json(['status' => 'ok', 'response' => true], 200);

    }

    public function search_ipid(Request $request)
    {
        $term     = $request->input("term");
        $contract = $request->input("contract");

        $response = DB::table('ipid')

            ->where('ipid', 'like', '%' . $term . '%')
            ->leftjoin('item_cobro', 'item_cobro.iditem_cobro', '=', 'ipid.items')
        // ->where('item_cobro.item_cobro_contract', $contract)
            ->orderBy('ipid', 'ASC')
            ->take(10)
            ->select('ipid.*', 'item_cobro.item_cobro_name')
            ->get();
        return response()->json(['status' => 'ok', 'response' => $response], 200);
    }

    public function search_dataipid(Request $request)
    {
        $id_ipid = $request->input("id_ipid");

        //$search = DB::table('')
    }

    public function imagesend_ext()
    {

        // $contract      = $param['contract'];

        $company_name    = $_POST["company_name"];
        $contract_name   = $_POST['contract_name'];
        $idoti           = $_POST['idoti'];
        $oti             = $_POST['oti'];
        $idobr_externa   = $_POST['idobr_externa'];
        $obr_consecutivo = $_POST['consecutive'];

        $company_name = str_replace(' ', '', $company_name);
        $image        = $_FILES;
        $hoy          = date("Y-m-d H:i");

        foreach ($image as &$image) {

            $name = $image['name'];
            $file = $image['tmp_name'];
            $type = $image['type'];

            $Typedoc = explode("/", $type);

            $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

            $strlength = strlen($characters);

            $random       = '';
            $company_name = str_replace(' ', '', $company_name);

            for ($i = 0; $i < 15; $i++) {
                $random .= $characters[rand(0, $strlength - 1)];
            }

            if ($Typedoc[1] == 'jpeg' or $Typedoc[1] == 'jpg') {

                $namefile = $random . '.' . $Typedoc[1];
                $carpeta  = public_path('/public/externas/images/' . $company_name . '/' . $contract_name . '/' . $obr_consecutivo . '/' . $oti . '/');

                if (!File::exists($carpeta)) {

                    $path = public_path('/public/externas/images/' . $company_name . '/' . $contract_name . '/' . $obr_consecutivo . '/' . $oti . '/');
                    File::makeDirectory($path, 0777, true);

                }
                //$img = Image::make($file)->resize(1920, 1080);

                $url = '/externas/images/' . $company_name . '/' . $contract_name . '/' . $obr_consecutivo . '/' . $oti . '/';
                move_uploaded_file($file, $carpeta . $namefile);

                //$img->save($carpeta . $namefile, 50);

                ExternalController::insert_image_etx($idoti, $namefile, $url, $oti, $hoy, $idobr_externa);
                // public function insert_image($idacta1, $namefile, $url, $idacta, $hoy)
            }

            if ($Typedoc[1] == 'pdf') {

                $namefile = $random . '.' . $Typedoc[1];
                $carpeta  = public_path('/public/externas/pdf/' . $company_name . '/' . $contract_name . '/' . $obr_consecutivo . '/' . $oti . '/');
                if (!File::exists($carpeta)) {
                    $path = public_path('/public/externas/pdf/' . $company_name . '/' . $contract_name . '/' . $obr_consecutivo . '/' . $oti . '/');
                    File::makeDirectory($path, 0777, true);
                }
                $url = '/externas/pdf/' . $company_name . '/' . $contract_name . '/' . $obr_consecutivo . '/' . $oti . '/';
                move_uploaded_file($file, $carpeta . $namefile);

                ExternalController::insert_image_etx($idoti, $namefile, $url, $oti, $hoy, $idobr_externa);
            }

        }
        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function insert_image_etx($idoti, $namefile, $url, $oti, $hoy, $idobr_externa)
    {

        $insert = DB::table('image_ext')
            ->insert([
                'name'   => $namefile,
                'url'    => $url,
                'id_oti' => $idoti,
                'id_obr' => $idobr_externa,
                'date'   => $hoy,
            ]);
    }

    public function view_image(Request $request)
    {
        $id_obr = $request->input("id_obr");
        $idoti  = $request->input("idoti");
        $url1   = $request->input("url");
        $search = DB::table('image_ext')
            ->where('id_oti', '=', $idoti)
            ->select('name', DB::raw("CONCAT('$url1', url,name) AS small"), DB::raw("CONCAT('$url1', url,name) AS medium"), DB::raw("CONCAT('$url1', url,name) AS big"))
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

}
