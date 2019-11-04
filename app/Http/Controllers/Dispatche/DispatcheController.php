<?php

namespace App\Http\Controllers\Dispatche;

use App\Http\Controllers\Controller;
use Config;
use Facades\App\ClassPhp\Consecutive;
use Facades\App\ClassPhp\log;
use Facades\App\ClassPhp\UpdateInventary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DispatcheController extends Controller
{
    //

    public function insert(Request $request)
    {

        $cellar          = $request->input("head.dispatches_cellar");
        $consec_obra     = $request->input("head.consec_obra");
        $date            = $request->input("head.dispatches_date");
        $destino         = $request->input("head.dispatches_destino");
        $dispatches_move = $request->input("head.dispatches_move");
        $employeeid      = $request->input("head.dispatches_incharge");
        $idincharge      = $request->input("head.idincharge");

        $idworkI        = $request->input("head.idworkI");
        $N_move         = $request->input("head.N_move");
        $id_oti         = $request->input("head.id_oti");
        $id_oym         = $request->input("head.id_oym");
        $obs_dispatches = $request->input("head.obs_dispatches");

        $idcompany   = $request->input("company");
        $contract    = $request->input("contract");
        $series      = $request->input("series");
        $documento   = Config::get('Config.despacho.documento');
        $Consecutive = Consecutive::query_consecutive($idcompany, $documento);

        $Consec = $Consecutive->consecutive;

        $body           = $request->input("body");
        $bodydata       = $body;
        $identification = $request->input("user");
        $ConseAtual     = (Int) $Consec + 1;

        $update = Consecutive::Updateconsecutive($idcompany, $documento, $ConseAtual);
        try {

            $insert_dispatche = DB::table('dispatches')

                ->insertGetId([
                    'dispatches_move'     => $dispatches_move,
                    'dispatches_cellar'   => $cellar,
                    'dispatches_date'     => $date,
                    'dispatches_destino'  => $destino,
                    'dispatches_conse'    => $Consec,
                    'dispatches_incharge' => $idincharge,
                    'consec_workI'        => $idworkI,
                    'dispatches_contract' => $contract,
                    'oti'                 => $id_oti,
                    'N_move'              => $N_move,
                    'id_oym'              => $id_oym,
                    'obs_dispatches'      => $obs_dispatches,

                ]);

            $response     = true;
            $decicion     = 2;
            $recorrerbody = DispatcheController::recorrer($bodydata, $idcompany, $insert_dispatche, $cellar, $Consec, $decicion);

            $text_insert = Config::get('Config.despacho.text_insert');

            log::insert_log($identification, $text_insert, $Consec, $idcompany);
            DispatcheController::series($series, $Consec, $insert_dispatche);
        } catch (Exception $e) {

            $response = false;

        }

        return response()->json(['status' => 'ok', 'data' => $response, 'consecutive' => $Consec], 200);

    }

    public function series($series, $Consec, $insert_dispatche)
    {

        for ($i = 0; $i < count($series); $i++) {

            $serie_nro_serie = isset($series[$i]['serie_nro_serie']) ? $series[$i]['serie_nro_serie'] : 0;
            $idseries        = isset($series[$i]['idseries']) ? $series[$i]['idseries'] : 0;

            if ($serie_nro_serie != 0) {

                $updateserie = DB::table('series')
                    ->where('idseries', '=', $idseries)
                    ->update([
                        'serie_despacho'  => $Consec,
                        'serie_iddepacho' => $insert_dispatche,
                        'serie_estado'    => 2,
                    ]);
            }
        }
    }
    public function recorrer($bodydata, $idcompany, $insert_dispatche, $cellar, $Consec, $decicion)
    {

        for ($i = 0; $i < count($bodydata); $i++) {

            $cod_mater = isset($bodydata[$i]['idmateriales']) ? $bodydata[$i]['idmateriales'] : null;
            $code      = isset($bodydata[$i]['code']) ? $bodydata[$i]['code'] : null;

            $dispatches = $insert_dispatche;

            $quantity = isset($bodydata[$i]["quantity"]) ? $bodydata[$i]["quantity"] : 0;
            $idcode   = isset($bodydata[$i]["iddetail_dispatches"]) ? $bodydata[$i]["iddetail_dispatches"] : null;
            $serie    = isset($bodydata[$i]["serie"]) ? $bodydata[$i]["serie"] : 0;

            if ($code != null) {
                if ($idcode != null) {

                    $insertbody = DispatcheController::update_body($dispatches, $cod_mater, $quantity, $idcompany, $cellar, $Consec, $idcode, $serie);

                } else {

                    $insertbody = DispatcheController::insertbody($dispatches, $cod_mater, $quantity, $idcompany, $cellar, $Consec, $serie);

                }
            }

        }

    }

    public function insertbody($dispatches, $cod_mater, $quantity, $idcompany, $cellar, $Consec, $serie)
    {

        try {

            $inser = DB::table('detail_dispatches')
                ->insertGetId([
                    'dispatches' => $dispatches,
                    'cod_mater'  => $cod_mater,
                    'quantity'   => $quantity,

                ]);

            $tipo = Config::get('Config.despacho.text_insert');

            $UpdateInventary = UpdateInventary::subtract($idcompany, $cellar, $cod_mater, $quantity, $Consec, $dispatches, $tipo);

        } catch (Exception $e) {

        }

    }

    public function update(Request $request)
    {
        $cellar          = (Int) $request->input("head.dispatches_cellar");
        $consec_obra     = (Int) $request->input("head.consec_obra");
        $date            = $request->input("head.dispatches_date");
        $destino         = (Int) $request->input("head.dispatches_destino");
        $dispatches_move = (Int) $request->input("head.dispatches_move");
        $series          = $request->input("series");
        $idworkI         = (Int) $request->input("head.idworkI");
        $N_move          = $request->input("head.N_move");
        $idcompany       = (Int) $request->input("company");
        $contract        = (Int) $request->input("contract");
        $id_oti          = $request->input("head.id_oti");
        $id_oym          = $request->input("head.id_oym");
        $a               = $request->input("head.dispatches_incharge");
        $obs_dispatches  = $request->input("head.obs_dispatches");
        //  $employeeid = isset($a) ? $a : $b;
        $employeeid = is_array($a) ? $a['idemployees'] : $request->input("head.idemployees");

        $iddispatches = (Int) $request->input("head.iddispatches");
        $consecutive  = (Int) $request->input("head.dispatches_conse");

        $body           = $request->input("body");
        $bodydata       = $body;
        $identification = $request->input("user");
        $revi           = $request->input("head.revi");
        DispatcheController::series($series, $consecutive, $iddispatches);

        try {

            $update_head = DB::table('dispatches')

                ->where('iddispatches', $iddispatches)
                ->update([

                    'dispatches_date'     => $date,
                    'dispatches_incharge' => $employeeid,
                    'dispatches_move'     => $dispatches_move,
                    'dispatches_destino'  => $destino,
                    'id_oym'              => $id_oym,
                    'consec_workI'        => $idworkI,
                    'oti'                 => $id_oti,
                    'N_move'              => $N_move,
                    'obs_dispatches'      => $obs_dispatches,
                    'revi'                => $revi,

                ]);

            $dispatches = true;
            $tipo       = Config::get('Config.despacho.text_update');

            log::insert_log($identification, $tipo, $consecutive, $idcompany);
            $decicion     = 1;
            $recorrerbody = DispatcheController::recorrer($bodydata, $idcompany, $iddispatches, $cellar, $consecutive, $decicion);

        } catch (Exception $e) {
            $dispatches = false;
        }

        return response()->json(['status' => 'ok', 'dispatches' => $dispatches], 200);
    }

    public function update_body($dispatches, $cod_mater, $quantity, $idcompany, $cellar, $Consec, $idcode, $serie)
    {

        try {

            $cantidadAnterior = DispatcheController::DetailBody($idcode, $idcompany, $cod_mater);
            $ValidarC         = DispatcheController::ValidarCatidades($dispatches, $cantidadAnterior, $quantity, $cod_mater, $idcompany, $Consec, $idcode, $cellar);

            $idcode;
            $update_body = DB::table('detail_dispatches')
                ->where('iddetail_dispatches', $idcode)
                ->update(['quantity' => $quantity]);

            //$series = series::update_series($idcompany, $serie, $dispatches, $idcode);

        } catch (Exception $e) {

        }

    }

    public function DetailBody($idcode, $idcompany, $cod_mater)
    {
        $material = DB::table('detail_dispatches')
            ->where('iddetail_dispatches', $idcode)
            ->select('detail_dispatches.*')
            ->first();

        return $cantidad_atual = $material->quantity;

    }
// funcion para valoidar la cantidad anterior con la cantidad actual
    public function ValidarCatidades($dispatches, $cantidadAnterior, $quantity, $cod_mater, $idcompany, $Consec, $idcode, $cellar)
    {
        $tipo = Config::get('Config.despacho.text_update');

        // 7               8
        if ($cantidadAnterior < $quantity) {

            $cantidad = (FLOAT) $quantity - $cantidadAnterior;

            $UpdateInventary = UpdateInventary::subtract($idcompany, $cellar, $cod_mater, $cantidad, $Consec, $dispatches, $tipo);

        }
        //10             6
        if ($cantidadAnterior > $quantity) {

            $cantidad = (FLOAT) $cantidadAnterior - $quantity;

            $UpdateInventary = UpdateInventary::AddInventary($idcompany, $cellar, $cod_mater, $cantidad, $Consec, $dispatches, $tipo);

        }

    }

    public function search_dispatches(Request $request)
    {

        $start_date = $request->input("start_date");
        $end_date   = $request->input("end_date");
        $company    = $request->input("company");

        $dispatches = DB::table('dispatches')
            ->join('employees', 'dispatches.dispatches_incharge', '=', 'employees.idemployees')
            ->join('contract', 'dispatches.dispatches_contract', '=', 'contract.idcontract')
            ->select('dispatches.*', 'employees.name', 'employees.last_name')
            ->where('contract.id_empresa', '=', $company)
            ->whereBetween('dispatches_date', [$start_date, $end_date])
            ->get();

        return response()->json(['status' => 'ok', 'dispatches' => $dispatches], 200);
    }

    public function search_dispatches_refunt(Request $request)
    {

        $start_date = $request->input("start_date");
        $end_date   = $request->input("end_date");
        $company    = $request->input("company");

        $dispatches = DB::table('dispatches')
            ->leftjoin('employees', 'dispatches.dispatches_incharge', '=', 'employees.idemployees')
            ->leftjoin('cellar', 'dispatches.dispatches_cellar', '=', 'cellar.idcellar')

            ->select('dispatches.*', 'employees.name', 'employees.last_name')
            ->where('cellar.id_empresa', '=', $company)
            ->where('dispatches.refund', '=', null)
            ->whereBetween('dispatches_date', [$start_date, $end_date])
            ->get();

        return response()->json(['status' => 'ok', 'dispatches' => $dispatches], 200);
    }
    public function search_head(Request $request)
    {

        $company      = $request->input("company");
        $iddispatches = $request->input("dispatche");

        try {

            $dispatche = DB::table('dispatches')
                ->leftjoin('employees', 'dispatches.dispatches_incharge', '=', 'employees.idemployees')
                ->leftjoin('worki', 'dispatches.consec_workI', '=', 'worki.idworkI')
                ->leftjoin('state_obr', 'state_obr.idstate_obr', '=', 'worki.worki_state')
                ->leftjoin('obr_anillos', 'obr_anillos.idobr_anillos', '=', 'dispatches.oti')
                ->leftjoin('oym', 'oym.id_oym', '=', 'dispatches.id_oym')
                ->where('iddispatches', $iddispatches)
                ->select('dispatches.*', 'employees.name', 'employees.last_name', 'employees.idemployees', 'worki.consecutive as consec_obr', 'worki.Direccion as address', 'worki.Estrato as estrato', 'worki.idworkI', 'state_obr.state_obr_name as T_obt', 'obr_anillos.idobr_anillos as id_oti', 'obr_anillos.obr_anillos_oti as oti', 'oym.cod_instalacion as address', 'oym.consecutive as oym')
                ->first();

            $dispatches_body = DispatcheController::search_body($iddispatches);
            $series          = DispatcheController::search_series($iddispatches);

        } catch (Exception $e) {

        }
        return response()->json(['status' => 'ok', 'dispatches' => $dispatche, 'dispatches_body' => $dispatches_body, 'series' => $series], 200);

    }

    public function search_series($iddispatches)
    {

        $search = DB::table('series')
            ->where('serie_iddepacho', '=', $iddispatches)
            ->select('series.*', 'series.serie_nro_serie as serie')
            ->get();

        return $search;
    }

    public function series_delet(Request $request)
    {

        $idseries = $request->input("idseries");

        try {

            $update = DB::table('series')
                ->where('idseries', '=', $idseries)
                ->where('serie_estado', '=', 2)
                ->update([
                    'serie_iddepacho' => '',
                    'serie_despacho'  => '',
                    'serie_estado'    => 1,
                ]);

            $result = true;
        } catch (\Exception $e) {
            $result = false;
        }

        return response()->json(['status' => 'ok', 'result' => $result], 200);
    }

    public function search_body($iddispatches)
    {
        //echo 'hola';
        $search_body = DB::table('dispatches')
            ->leftjoin('detail_dispatches', 'detail_dispatches.dispatches', '=', 'dispatches.iddispatches')
            ->leftjoin('materiales', 'detail_dispatches.cod_mater', '=', 'materiales.idmateriales')
            ->leftjoin('unity', 'materiales.unity', '=', 'unity.idUnity')

            ->where('detail_dispatches.dispatches', $iddispatches)

            ->select('detail_dispatches.*', 'materiales.description', 'materiales.serie', 'unity.name_Unity', 'materiales.code as cod_mater', 'materiales.idmateriales as idmateriales', 'materiales.code as code',
                DB::raw("(SELECT inventary_quantity FROM inventario_cellar where inventario_cellar.id_cellar=dispatches.dispatches_cellar and inventario_cellar.id_material=materiales.idmateriales) AS missing"))
            ->get();

        return $search_body;
    }

}
