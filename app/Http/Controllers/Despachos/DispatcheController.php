<?php

namespace App\Http\Controllers\Despachos;

use App\Http\Controllers\Controller;
use Config;
use Facades\App\ClassPhp\Consecutive;
use Facades\App\ClassPhp\UpdateInventary;
use File;
//use Facades\App\ClassPhp\log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DispatcheController extends Controller
{
    //

    public function save(Request $request)
    {
        $iddispatches = $request->input("cabezera.iddispatches");
        $company = $request->input("company");
        $contrat = $request->input("contrat");
        $dispatches_cellar = $request->input("cabezera.dispatches_cellar");
        $N_move = $request->input("cabezera.N_move");
        $dispatches_date = $request->input("cabezera.dispatches_date");
        $dispatches_destino = $request->input("cabezera.dispatches_destino");
        $dispatches_incharge = $request->input("cabezera.dispatches_incharge");
        $dispatches_move = $request->input("cabezera.dispatches_move");
        $id_oym = $request->input("cabezera.id_oym");
        $id_oti = $request->input("cabezera.id_oti");
        $id_work = $request->input("cabezera.id_work");
        $obs_dispatches = $request->input("cabezera.obs_dispatches");

        $data = $request->input("data");
        $user = $request->input("user");

        if (!isset($iddispatches)) {

            $documento = Config::get('Config.despacho.documento');
            $Consecutive = Consecutive::query_consecutive($company, $documento);
            $consecutivo = $Consecutive->consecutive;

            $insert = DB::table('dispatches')
                ->insertGetId([

                    'dispatches_move' => $dispatches_move,
                    'dispatches_contract' => $contrat,
                    'dispatches_cellar' => $dispatches_cellar,
                    'dispatches_incharge' => $dispatches_incharge,
                    'dispatches_date' => $dispatches_date,
                    'dispatches_conse' => $consecutivo,
                    'dispatches_destino' => $dispatches_destino,
                    'consec_workI' => $id_work,
                    'oti' => $id_oti,
                    'obs_dispatches' => $obs_dispatches,
                    'N_move' => $N_move,
                    'id_oym' => $id_oym,
                ]);

            $this->recorrer($data, $insert, $consecutivo, $user, $company, $contrat, $dispatches_cellar);

            $conse = $consecutivo + 1;
            Consecutive::Updateconsecutive($company, $documento, $conse);

            return response()->json(['status' => 'ok', 'dispatches' => true, 'consecutive' => $consecutivo], 200);

        } else {

            $update = DB::table('dispatches')
                ->where('iddispatches', $iddispatches)
                ->update([
                    'dispatches_move' => $dispatches_move,
                    'dispatches_incharge' => $dispatches_incharge,
                    'dispatches_date' => $dispatches_date,
                    'dispatches_destino' => $dispatches_destino,
                    'consec_workI' => $id_work,
                    'oti' => $id_oti,
                    'obs_dispatches' => $obs_dispatches,
                    'N_move' => $N_move,
                    'id_oym' => $id_oym,
                ]);

            return response()->json(['status' => 'ok', 'dispatches' => true], 200);
        }

    }

    public function recorrer($data, $insert, $consecutivo, $user, $company, $contrat, $dispatches_cellar)
    {

        for ($i = 0; $i < count($data); $i++) {

            $iddetail_dispatches = isset($data[$i]['iddetail_dispatches']) ? $data[$i]['iddetail_dispatches'] : null;
            $idmateriales = isset($data[$i]['idmateriales']) ? $data[$i]['idmateriales'] : null;
            $quantity = isset($data[$i]['quantity']) ? $data[$i]['quantity'] : 0;
            $idseries = isset($data[$i]['idseries']) ? $data[$i]['idseries'] : null;

            if ($iddetail_dispatches == null) {

                $inser_detail = DB::table('detail_dispatches')
                    ->insert([
                        'cod_mater' => $idmateriales,
                        'quantity' => $quantity,
                        'dispatches' => $insert,
                        'idseries' => $idseries,
                    ]);

                UpdateInventary::restarinventario($insert, $dispatches_cellar, $consecutivo, $idmateriales, $quantity, 'CREO DESPACHO', $company, $user);
                $this->update_serie($consecutivo, $insert, $idseries);

            }
        }
    }

    public function search(Request $request)
    {

        $start_date = $request->input("date_ini");
        $end_date = $request->input("date_end");
        $company = $request->input("company");

        $dispatches = DB::table('dispatches')
            ->leftjoin('employees', 'dispatches.dispatches_incharge', '=', 'employees.idemployees')
            ->leftjoin('contract', 'dispatches.dispatches_contract', '=', 'contract.idcontract')
            ->leftjoin('destination_dispatches', 'dispatches.dispatches_destino', '=', 'destination_dispatches.iddestination_dispatches')
            ->select('dispatches.*', 'employees.name', 'employees.last_name', 'destination_dispatches.destination_name')
            ->where('contract.id_empresa', '=', $company)
            ->whereBetween('dispatches_date', [$start_date, $end_date])
            ->get();

        return response()->json(['status' => 'ok', 'dispatches' => $dispatches], 200);
    }

    public function search_one(Request $request)
    {

        $consecutive = $request->input("consecutive");
        $company = $request->input("company");

        $dispatches = DB::table('dispatches')
            ->leftjoin('employees', 'dispatches.dispatches_incharge', '=', 'employees.idemployees')
            ->leftjoin('contract', 'dispatches.dispatches_contract', '=', 'contract.idcontract')
            ->leftjoin('destination_dispatches', 'dispatches.dispatches_destino', '=', 'destination_dispatches.iddestination_dispatches')
            ->select('dispatches.*', 'employees.name', 'employees.last_name', 'destination_dispatches.destination_name')
            ->where('contract.id_empresa', '=', $company)
            ->where('dispatches_conse', $consecutive)
            ->get();

        return response()->json(['status' => 'ok', 'dispatches' => $dispatches], 200);
    }

    public function search_one_diapches(Request $request)
    {

        $iddispache = $request->input("iddispache");
        $company = $request->input("company");

        $head = $this->search_head($iddispache, $company);
        $body = $this->search_detail($iddispache, $company, $head->dispatches_cellar);

        return response()->json(['status' => 'ok', 'head' => $head, 'body' => $body], 200);

    }

    public function search_detail($iddispache, $company, $dispatches_cellar)
    {

        $body = DB::table('detail_dispatches')
            ->where('dispatches', $iddispache)
            ->leftjoin('materiales', 'detail_dispatches.cod_mater', '=', 'materiales.idmateriales')
            ->leftjoin('series', 'detail_dispatches.idseries', '=', 'series.idseries')
            ->leftjoin('unity', 'materiales.unity', '=', 'unity.idUnity')
            ->select('detail_dispatches.*', 'materiales.description', 'unity.name_Unity', 'materiales.code as cod_mater', 'materiales.idmateriales', 'materiales.serie', DB::raw("(SELECT inventary_quantity FROM inventario_cellar where inventario_cellar.id_cellar=$dispatches_cellar and inventario_cellar.id_material=materiales.idmateriales) AS missing2"), 'series.idseries', 'series.serie_nro_serie')
            ->get();

        $detail_purchase = [];

        foreach ($body as $search) {

            $reintegros = DB::table('detail_refund')
                ->where('id_detail_dispatch', $search->iddetail_dispatches)
                ->where('cod_mater', $search->idmateriales)
                ->groupBy('detail_refund.id_detail_dispatch')
                ->select(DB::raw('sum(detail_refund.refund) AS refund'))
                ->first();
            //var_dump($reintegros);
            $refund = isset($reintegros->refund) ? $reintegros->refund : 0;

            $detail_purchase[] = [
                'iddetail_dispatches' => $search->iddetail_dispatches,
                'dispatches' => $search->dispatches,
                'code' => $search->cod_mater,
                'cod_mater' => $search->cod_mater,
                'quantity' => $search->quantity,
                'refund' => $refund,
                'inventary_quantity' => $search->missing2,
                'description' => $search->description,
                'name_Unity' => $search->name_Unity,
                'idmateriales' => $search->idmateriales,
                'nserie' => $search->serie_nro_serie,
                'idseries' => $search->idseries,
                'serie' => $search->serie,

            ];

        }

        return $detail_purchase;
    }

    public function search_head($iddispache, $company)
    {

        $dispatche = DB::table('dispatches')
            ->leftjoin('employees', 'dispatches.dispatches_incharge', '=', 'employees.idemployees')
            ->leftjoin('worki', 'dispatches.consec_workI', '=', 'worki.idworkI')
            ->leftjoin('state_obr', 'state_obr.idstate_obr', '=', 'worki.worki_state')
            ->leftjoin('obr_anillos', 'obr_anillos.idobr_anillos', '=', 'dispatches.oti')
            ->leftjoin('oym', 'oym.id_oym', '=', 'dispatches.id_oym')
            ->where('iddispatches', $iddispache)
            ->select('dispatches.*', 'employees.name', 'employees.last_name', 'employees.idemployees', 'worki.consecutive as consec_obr', 'worki.Direccion as address', 'worki.Estrato as estrato', 'worki.idworkI', 'state_obr.state_obr_name as T_obt', 'obr_anillos.idobr_anillos as id_oti', 'obr_anillos.obr_anillos_oti as oti', 'oym.cod_instalacion as address1', 'oym.consecutive as consecutive_oym')
            ->first();
        return $dispatche;
    }

    public function update_quantity(Request $request)
    {
        $cantidad = $request->input("Editmodel.cantidad");
        $iddetail_dispatches = $request->input("Editmodel.iddetail_dispatches");
        $idmateriales = $request->input("Editmodel.idmateriales");

        $idprofile = $request->input("idprofile");
        $cellar = $request->input("cellar");
        $conse = $request->input("conse");
        $company = $request->input("company");
        $contrat = $request->input("contrat");
        $user = $request->input("user");
        $pass = $request->input("pass");
        $dispache = $request->input("dispache");
        $r_saldo = false;
        $head = false;
        $body = false;

        $search = DB::table('detail_dispatches')
            ->where('iddetail_dispatches', $iddetail_dispatches)
            ->where('cod_mater', $idmateriales)
            ->first();

        $cant_anterior = $search->quantity;
        $tipo = Config::get('Config.despacho.text_update');

        if ($cant_anterior > $cantidad) {

            $cantidad_actual = $cant_anterior - $cantidad;

            UpdateInventary::sumarinventario($dispache, $cellar, $conse, $idmateriales, $cantidad_actual, $tipo, $company, $user);

        }

        if ($cantidad > $cant_anterior) {

            $cantidad_actual = $cantidad - $cant_anterior;

            $saldo = UpdateInventary::inventario_atual($cellar, $idmateriales, $cantidad_actual);

            if ($cantidad_actual > $saldo) {

                $r_saldo = true;
                return response()->json(['status' => 'ok', 'head' => $head, 'body' => $body, 'saldo' => $r_saldo], 200);
            } else {
                UpdateInventary::restarinventario($dispache, $cellar, $conse, $idmateriales, $cantidad_actual, $tipo, $company, $user);

            }

        }

        // UpdateInventary::cancular_cantidades($income, $income_cellar, $income_conse, $cod_mater, $cantidadan, $ceceived_amount, 'ATUALIZO INGRESO', $company);

        $update = DB::table('detail_dispatches')
            ->where('cod_mater', $idmateriales)
            ->where('iddetail_dispatches', $iddetail_dispatches)
            ->update([
                'quantity' => $cantidad,
            ]);

        $head = $this->search_head($dispache, $company);
        $body = $this->search_detail($dispache, $company, $cellar);

        return response()->json(['status' => 'ok', 'head' => $head, 'body' => $body, 'saldo' => $r_saldo], 200);

    }

    public function image_upload()
    {

        $dispache = $_POST['dispache'];
        $name_company = $_POST['name_company'];
        $image = $_FILES;
        $name = $image['file']['name'];
        $file = $image['file']['tmp_name'];
        $type = $image['file']['type'];
        $hoy = date("Y_m_d_H_i_s");
        $Typedoc = explode("/", $type);
        $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $strlength = strlen($characters);

        $random = '';
        $name_company = str_replace(' ', '', $name_company);

        for ($i = 0; $i < 15; $i++) {
            $random .= $characters[rand(0, $strlength - 1)];
        }

        $namefile = $random . '.' . $Typedoc[1];
        $carpeta = public_path('/public/despachos/' . $name_company . '/' . $dispache . '/');

        if (!File::exists($carpeta)) {

            $path = public_path('/public/despachos/' . $name_company . '/' . $dispache . '/');

            File::makeDirectory($path, 0777, true);
            move_uploaded_file($file, $carpeta . $namefile);
        } else {
            move_uploaded_file($file, $carpeta . $namefile);
        }

    }

    public function despacho_series(Request $request)
    {

        $cellar = $request->input("cellar");
        $contrat = $request->input("contrat");
        $serie = $request->input("serie");

        $serach = DB::table('series')
            ->where('serie_nro_serie', $serie)
        //        ->where('contrac', $contrat)
            ->first();

        return response()->json(['status' => 'ok', 'serie' => $serach], 200);
    }

    public function update_serie($consecutivo, $insert, $idseries)
    {
        $update = DB::table('series')
            ->where('idseries', $idseries)
            ->where('serie_estado', 1)
            ->update([
                'serie_estado' => 2,
                'serie_despacho' => $consecutivo,
                'serie_iddepacho' => $insert,

            ]);
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
