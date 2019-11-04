<?php

namespace App\Http\Controllers\Reintegro;

use App\Http\Controllers\Controller;
use Config;
use Facades\App\ClassPhp\Consecutive;
use Facades\App\ClassPhp\UpdateInventary;
//use File;
//use Facades\App\ClassPhp\log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReintegroController extends Controller
{
    //

    public function save(Request $request)
    {

        $iddispatches      = $request->input("head.iddispatches");
        $dispatches_cellar = $request->input("head.dispatches_cellar");
        $company           = $request->input("company");
        $contrat           = $request->input("contrat");

        $refund_date     = $request->input("head.refund_date");
        $idrefund        = $request->input("head.idrefund");
        $refund_conse    = $request->input("head.refund_conse");
        $refund_incharge = $request->input("head.dispatches_incharge");

        $refund_obs = $request->input("head.refund_obs");
        $body       = $request->input("body");
        $user       = $request->input("usuario");

        if (!isset($idrefund)) {

            $documento   = Config::get('Config.reintegro.documento');
            $Consecutive = Consecutive::query_consecutive($company, $documento);
            $consecutivo = $Consecutive->consecutive;

            $insert = DB::table('refund')
                ->insertGetId([

                    'refund_cellar'   => $dispatches_cellar,
                    'refund_date'     => $refund_date,
                    'refund_conse'    => $consecutivo,
                    'iddispatches'    => $iddispatches,
                    'refund_obs'      => $refund_obs,
                    'refund_contract' => $contrat,
                    'refund_incharge' => $refund_incharge,

                ]);

            $update = DB::table('dispatches')
                ->where('iddispatches', $iddispatches)
                ->update([
                    'refund' => 1,
                ]);

            $this->recorrer($body, $insert, $consecutivo, $user, $company, $contrat, $dispatches_cellar);
            $conse = $consecutivo + 1;
            Consecutive::Updateconsecutive($company, $documento, $conse);

            return response()->json(['status' => 'ok', 'save' => true, 'update' => false, 'consecutive' => $consecutivo], 200);
        } else {

            $update = DB::table('refund')
                ->where('idrefund', $idrefund)
                ->update([
                    'refund_date' => $refund_date,
                    'refund_obs'  => $refund_obs,
                ]);

            return response()->json(['status' => 'ok', 'save' => false, 'update' => true], 200);

        }

    }

    public function recorrer($data, $insert, $consecutivo, $user, $company, $contrat, $dispatches_cellar)
    {

        for ($i = 0; $i < count($data); $i++) {

            $iddetail_dispatches = isset($data[$i]['iddetail_dispatches']) ? $data[$i]['iddetail_dispatches'] : null;
            $idmateriales        = isset($data[$i]['idmateriales']) ? $data[$i]['idmateriales'] : null;
            $quantity            = isset($data[$i]['quantity']) ? $data[$i]['quantity'] : 0;
            $refund              = isset($data[$i]['refund']) ? $data[$i]['refund'] : 0;
            $iddetail_refund     = isset($data[$i]['iddetail_refund']) ? $data[$i]['iddetail_refund'] : null;

            if ($iddetail_refund == null) {

                if ($refund > 0) {
                    $inser_detail = DB::table('detail_refund')
                        ->insert([
                            'idrefund'           => $insert,
                            'cod_mater'          => $idmateriales,
                            'quantity'           => $quantity,
                            'refund'             => $refund,
                            'id_detail_dispatch' => $iddetail_dispatches,
                        ]);

                    UpdateInventary::sumarinventario($insert, $dispatches_cellar, $consecutivo, $idmateriales, $refund, 'CREO REINTEGRO', $company, $user);
                }

            }
        }
    }

    public function search_conse(Request $request)
    {

        $company = $request->input("company");
        $contrat = $request->input("contrat");
        $consec  = $request->input("consec");

        $search = DB::table('refund')
            ->leftjoin('employees', 'refund.refund_incharge', '=', 'employees.idemployees')
            ->leftjoin('contract', 'refund.refund_contract', '=', 'contract.idcontract')
            ->leftjoin('dispatches', 'refund.iddispatches', '=', 'dispatches.iddispatches')
            ->where('contract.id_empresa', '=', $company)
            ->where('refund_conse', $consec)
            ->select('refund.*', 'employees.name', 'employees.last_name', 'dispatches.dispatches_conse')
            ->get();

        return response()->json(['status' => 'ok', 'refund' => $search], 200);
    }

    public function search_reintegro(Request $request)
    {

        $idrefund = $request->input('idrefunt');

        $head = $this->head($idrefund);

        $head->refund_cellar;
        $body = $this->body($idrefund, $head->refund_cellar);

        return response()->json(['status' => 'ok', 'head' => $head, 'body' => $body], 200);
    }

    public function head($idrefund)
    {

        $search = DB::table('refund')
            ->leftjoin('dispatches', 'refund.iddispatches', '=', 'dispatches.iddispatches')
            ->leftjoin('employees', 'dispatches.dispatches_incharge', '=', 'employees.idemployees')
            ->leftjoin('worki', 'dispatches.consec_workI', '=', 'worki.idworkI')
            ->leftjoin('state_obr', 'state_obr.idstate_obr', '=', 'worki.worki_state')
            ->leftjoin('obr_anillos', 'obr_anillos.idobr_anillos', '=', 'dispatches.oti')
            ->leftjoin('oym', 'oym.id_oym', '=', 'dispatches.id_oym')
            ->where('idrefund', $idrefund)
            ->select('dispatches.*', 'refund.*', 'employees.name', 'employees.last_name', 'employees.idemployees', 'worki.consecutive as consec_obr', 'worki.Direccion as address', 'worki.Estrato as estrato', 'worki.idworkI', 'state_obr.state_obr_name as T_obt', 'obr_anillos.idobr_anillos as id_oti', 'obr_anillos.obr_anillos_oti as oti', 'oym.cod_instalacion as address1', 'oym.consecutive as consecutive_oym')
            ->first();
        return $search;
    }

    public function body($idrefund, $refund_cellar)
    {

        $body = DB::table('detail_refund')
            ->where('idrefund', $idrefund)
            ->leftjoin('materiales', 'detail_refund.cod_mater', '=', 'materiales.idmateriales')
            ->leftjoin('unity', 'materiales.unity', '=', 'unity.idUnity')
            ->select('detail_refund.*', DB::raw("(SELECT inventary_quantity FROM inventario_cellar where inventario_cellar.id_cellar=$refund_cellar and inventario_cellar.id_material=materiales.idmateriales) AS missing"), 'materiales.description', 'unity.name_Unity', 'materiales.code as cod_mater', 'materiales.idmateriales')
            ->get();

        $detail_refund = [];

        foreach ($body as $search) {

            $reintegros = DB::table('detail_refund')
                ->where('id_detail_dispatch', $search->id_detail_dispatch)
                ->where('cod_mater', $search->idmateriales)
                ->groupBy('detail_refund.id_detail_dispatch')
                ->select(DB::raw('sum(detail_refund.refund) AS refundp'))
                ->first();
            //var_dump($reintegros);
            $refundp = isset($reintegros->refundp) ? $reintegros->refundp - $search->refund : 0;

            $detail_refund[] = [
                'iddetail_refund'     => $search->iddetail_refund,
                'iddetail_dispatches' => $search->id_detail_dispatch,
                'code'                => $search->cod_mater,
                'cod_mater'           => $search->cod_mater,
                'quantity'            => $search->quantity,
                'refund'              => $search->refund,
                'refundp'             => $refundp,
                'inventary_quantity'  => $search->missing,
                'description'         => $search->description,
                'name_Unity'          => $search->name_Unity,
                'idmateriales'        => $search->idmateriales,

            ];

        }

        return $detail_refund;
    }

    public function search_reintegro_date(Request $request)
    {

        $ini_date = $request->input('ini_date1');
        $end_date = $request->input('end_date1');
        $company  = $request->input('company');

        $search = DB::table('refund')
            ->leftjoin('employees', 'refund.refund_incharge', '=', 'employees.idemployees')
            ->leftjoin('contract', 'refund.refund_contract', '=', 'contract.idcontract')
            ->leftjoin('dispatches', 'refund.iddispatches', '=', 'dispatches.iddispatches')
            ->where('contract.id_empresa', '=', $company)
            ->whereBetween('refund_date', [$ini_date, $end_date])
            ->select('refund.*', 'employees.name', 'employees.last_name', 'dispatches.dispatches_conse')
            ->get();

        return response()->json(['status' => 'ok', 'refund' => $search], 200);

    }

    public function search_one_diapches(Request $request)
    {

        $iddispache = $request->input("iddispache");
        $company    = $request->input("company");

        $head = $this->search_head($iddispache, $company);
        $body = $this->search_detail($iddispache, $company, $head->dispatches_cellar);

        return response()->json(['status' => 'ok', 'head' => $head, 'body' => $body], 200);

    }

    public function search_detail($iddispache, $company, $dispatches_cellar)
    {

        $body = DB::table('detail_dispatches')
            ->where('dispatches', $iddispache)
            ->leftjoin('materiales', 'detail_dispatches.cod_mater', '=', 'materiales.idmateriales')
            ->leftjoin('unity', 'materiales.unity', '=', 'unity.idUnity')
            ->select('detail_dispatches.*', 'materiales.description', 'unity.name_Unity', 'materiales.code as cod_mater', 'materiales.idmateriales', DB::raw("(SELECT inventary_quantity FROM inventario_cellar where inventario_cellar.id_cellar=$dispatches_cellar and inventario_cellar.id_material=materiales.idmateriales) AS missing2"))
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
                'dispatches'          => $search->dispatches,
                'code'                => $search->cod_mater,
                'cod_mater'           => $search->cod_mater,
                'quantity'            => $search->quantity,
                'refund'              => 0,
                'refundp'             => $refund,
                'inventary_quantity'  => $search->missing2,
                'description'         => $search->description,
                'name_Unity'          => $search->name_Unity,
                'idmateriales'        => $search->idmateriales,

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

    public function search_conse_despach(Request $request)
    {

        $company = $request->input("company");
        $contrat = $request->input("contrat");
        $consec  = $request->input("Conse_desp");

        $dispatches = DB::table('dispatches')
            ->leftjoin('employees', 'dispatches.dispatches_incharge', '=', 'employees.idemployees')
            ->leftjoin('contract', 'dispatches.dispatches_contract', '=', 'contract.idcontract')
            ->leftjoin('destination_dispatches', 'dispatches.dispatches_destino', '=', 'destination_dispatches.iddestination_dispatches')
            ->select('dispatches.*', 'employees.name', 'employees.last_name', 'destination_dispatches.destination_name')
            ->where('contract.id_empresa', '=', $company)
            ->where('dispatches_conse', $consec)
            ->get();

        return response()->json(['status' => 'ok', 'dispatches' => $dispatches], 200);
    }

    public function update_reintegro(Request $request)
    {

        $iddetail_refund = $request->input("iddetail_refund");
        $cod_mater       = $request->input("cod_mater");
        $idrefund        = $request->input("idrefund");
        $refund          = $request->input("refund");

        $refunda = $request->input("refunda");
        $conse   = $request->input("conse");
        $cellar  = $request->input("cellar");
        $usuario = $request->input("usuario");
        $contrat = $request->input("contrat");
        $company = $request->input("company");

        if ($refund > $refunda) {

            $cantidad = $refund - $refunda;

            UpdateInventary::sumarinventario($idrefund, $cellar, $conse, $cod_mater, $cantidad, 'ATUALIZO REINTEGRO', $company, $usuario);

            $update = DB::table('detail_refund')
                ->where('iddetail_refund', $iddetail_refund)
                ->update([
                    'refund' => $refund,
                ]);

            $body = $this->body($idrefund, $cellar);

            return response()->json(['status' => 'ok', 'update' => true, 'body' => $body, 'saldo' => false], 200);

        }

        if ($refunda > $refund) {

            $cantidad = $refunda - $refund;

            $saldo = UpdateInventary::inventario_atual($cellar, $cod_mater, $cantidad);

            if ($cantidad > $saldo) {

                return response()->json(['status' => 'ok', 'saldo' => true], 200);
            }

            UpdateInventary::restarinventario($idrefund, $cellar, $conse, $cod_mater, $cantidad, 'ATUALIZO REINTEGRO', $company, $usuario);

            $update = DB::table('detail_refund')
                ->where('iddetail_refund', $iddetail_refund)
                ->update([
                    'refund' => $refund,
                ]);

            $body = $this->body($idrefund, $cellar);

            return response()->json(['status' => 'ok', 'update' => true, 'body' => $body, 'saldo' => false], 200);
        }

        return response()->json(['status' => 'ok', 'update' => false, 'saldo' => false], 200);
    }

    public function historico(Request $request)
    {

        $company     = $request->input("company");
        $consecutive = $request->input("consecutive");
        $params1     = $request->input("params1");
        $params2     = $request->input("params2");

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
