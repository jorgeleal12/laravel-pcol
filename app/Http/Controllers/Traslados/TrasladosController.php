<?php

namespace App\Http\Controllers\Traslados;

use App\Http\Controllers\Controller;
use Config;
use Facades\App\ClassPhp\Consecutive;
use Facades\App\ClassPhp\UpdateInventary;
use Illuminate\Http\Request;
//use File;
//use Facades\App\ClassPhp\log;
use Illuminate\Support\Facades\DB;

class TrasladosController extends Controller
{
    //

    public function save(Request $request)
    {

        $idtransfer        = $request->input("head.idtransfer");
        $transfer_date     = $request->input("head.transfer_date");
        $transfer_cellar_o = $request->input("head.transfer_cellar_o");
        $transfer_cellar_d = $request->input("head.transfer_cellar_d");
        $transfer_observa  = $request->input("head.transfer_observa");
        $transfer_consec   = $request->input("head.transfer_consec");
        $trasfer_contract  = $request->input("head.trasfer_contract");

        $company = $request->input("company");

        $contract = $request->input("contract");
        $user     = $request->input("user");
        $data     = $request->input("data");

        if (!isset($idtransfer)) {

            $documento   = Config::get('Config.traslado.documento');
            $Consecutive = Consecutive::query_consecutive($company, $documento);
            $consecutivo = $Consecutive->consecutive;

            $save = DB::table('transfer')
                ->insertGetId([
                    'transfer_date'     => $transfer_date,
                    'transfer_cellar_o' => $transfer_cellar_o,
                    'transfer_cellar_d' => $transfer_cellar_d,
                    'transfer_observa'  => $transfer_observa,
                    'transfer_consec'   => $consecutivo,
                    'trasfer_contract'  => $contract,

                ]);

            Consecutive::Updateconsecutive_inventario($company, $documento, $consecutivo);

            $this->recorrer($contract, $save, $data, $transfer_cellar_o, $transfer_cellar_d, $consecutivo, $company, $user);

            return response()->json(['status' => 'ok', 'consecutivo' => $consecutivo], 200);

        } else {

            $update = DB::table('transfer')
                ->where('idtransfer', $idtransfer)
                ->update([

                    'transfer_date' => $transfer_date,
                ]);
            return response()->json(['status' => 'ok', 'update' => true], 200);
        }
    }
    public function recorrer($contract, $save, $data, $transfer_cellar_o, $transfer_cellar_d, $consecutivo, $company, $user)
    {
        for ($i = 0; $i < count($data); $i++) {

            $iddetail_transfer        = isset($data[$i]['iddetail_transfer']) ? $data[$i]['iddetail_transfer'] : null;
            $idmateriales             = isset($data[$i]['idmateriales']) ? $data[$i]['idmateriales'] : null;
            $detail_transfer_quantity = isset($data[$i]['detail_transfer_quantity']) ? $data[$i]['detail_transfer_quantity'] : 0;

            $insert = DB::table('detail_transfer')
                ->insert([
                    'idtransfer'               => $save,
                    'detail_transfer_cod'      => $idmateriales,
                    'detail_transfer_quantity' => $detail_transfer_quantity,
                ]);

            UpdateInventary::sumarinventario($save, $transfer_cellar_d, $consecutivo, $idmateriales, $detail_transfer_quantity, 'CREO TRASLADO', $company, $user);

            UpdateInventary::restarinventario($save, $transfer_cellar_o, $consecutivo, $idmateriales, $detail_transfer_quantity, 'CREO TRASLADO', $company, $user);

        }
    }

    public function search_date(Request $request)
    {

        $start_date = $request->input("start_date");
        $end_date   = $request->input("end_date");
        $company    = $request->input("company");

        $search = DB::table('transfer')
            ->leftjoin('contract', 'transfer.trasfer_contract', '=', 'contract.idcontract')
            ->where('contract.id_empresa', '=', $company)
            ->whereBetween('transfer_date', [$start_date, $end_date])
            ->select('transfer.*',
                DB::raw("(select  name from cellar where idcellar=transfer.transfer_cellar_o) as cellaro"),
                DB::raw("(select  name from cellar where idcellar=transfer.transfer_cellar_d) as cellard"))
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function search_one(Request $request)
    {
        $idtransfer = $request->input("idtransfer");
        $cellar_o   = $request->input("cellar_o");

        $head = $this->head($idtransfer);
        $body = $this->body($idtransfer, $cellar_o);

        return response()->json(['status' => 'ok', 'head' => $head, 'body' => $body], 200);
    }

    public function head($idtransfer)
    {

        $search = DB::table('transfer')
            ->where('idtransfer', '=', $idtransfer)
            ->select('transfer.*')
            ->first();

        return $search;
    }

    public function body($idtransfer, $cellar_o)
    {

        $search_body = DB::table('detail_transfer')
            ->leftjoin('materiales', 'materiales.idmateriales', '=', 'detail_transfer.detail_transfer_cod')
            ->leftjoin('unity', 'unity.idUnity', '=', 'materiales.unity')
            ->where('idtransfer', '=', $idtransfer)
            ->select('detail_transfer.*', 'materiales.description', 'materiales.idmateriales', 'unity.name_Unity', 'detail_transfer.detail_transfer_quantity', 'materiales.code', 'materiales.code as cod_mater',
                DB::raw("(select  inventary_quantity from inventario_cellar where id_cellar='$cellar_o' AND id_material=detail_transfer.detail_transfer_cod ) as inventary_quantity"))
            ->get();

        return $search_body;
    }

    public function edit(Request $request)
    {

        $company                    = $request->input("company");
        $iddetail_transfer          = $request->input("edit.iddetail_transfer");
        $detail_transfer_quantity   = $request->input("edit.detail_transfer_quantity");
        $detail_transfer_quantity_a = $request->input("edit.detail_transfer_quantity_a");
        $idmateriales               = $request->input("edit.idmateriales");
        $transfer_cellar_o          = $request->input("head.transfer_cellar_o");
        $transfer_cellar_d          = $request->input("head.transfer_cellar_d");
        $idtransfer                 = $request->input("head.idtransfer");
        $transfer_consec            = $request->input("head.transfer_consec");
        $user                       = $request->input("user");

        if ($detail_transfer_quantity > $detail_transfer_quantity_a) {

            $cantidad = $detail_transfer_quantity - $detail_transfer_quantity_a;

            $saldo_t = $cantidad + $detail_transfer_quantity_a;

            $saldo = UpdateInventary::inventario_atual_edit($transfer_cellar_o, $idmateriales);

            if ($saldo_t > $saldo) {

                return response()->json(['status' => 'ok', 'saldo' => true], 200);
            }

            UpdateInventary::sumarinventario($idtransfer, $transfer_cellar_d, $transfer_consec, $idmateriales, $cantidad, 'ATUALIZO TRASLADO', $company, $user);
            UpdateInventary::restarinventario($idtransfer, $transfer_cellar_o, $transfer_consec, $idmateriales, $cantidad, 'ATUALIZO TRASLADO', $company, $user);

            $update = DB::table('detail_transfer')
                ->where('iddetail_transfer', $iddetail_transfer)
                ->update([
                    'detail_transfer_quantity' => $detail_transfer_quantity,
                ]);

            $body = $this->body($idtransfer, $transfer_cellar_o);

            return response()->json(['status' => 'ok', 'saldo' => false, 'body' => $body], 200);
        }

        if ($detail_transfer_quantity_a > $detail_transfer_quantity) {

            $cantidad = $detail_transfer_quantity_a - $detail_transfer_quantity;

            $saldo_t = $detail_transfer_quantity_a - $cantidad;

            $saldo = UpdateInventary::inventario_atual_edit($transfer_cellar_d, $idmateriales);

            if ($saldo_t > $saldo) {

                return response()->json(['status' => 'ok', 'saldo' => true], 200);
            }

            UpdateInventary::sumarinventario($idtransfer, $transfer_cellar_o, $transfer_consec, $idmateriales, $cantidad, 'ATUALIZO TRASLADO', $company, $user);
            UpdateInventary::restarinventario($idtransfer, $transfer_cellar_d, $transfer_consec, $idmateriales, $cantidad, 'ATUALIZO TRASLADO', $company, $user);

            $update = DB::table('detail_transfer')
                ->where('iddetail_transfer', $iddetail_transfer)
                ->update([
                    'detail_transfer_quantity' => $detail_transfer_quantity,
                ]);

            $body = $this->body($idtransfer, $transfer_cellar_o);

            return response()->json(['status' => 'ok', 'saldo' => false, 'body' => $body], 200);

        }
        return response()->json(['status' => 'ok', 'saldo' => false], 200);
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

    public function search_conse(Request $request)
    {

        $Conse_  = $request->input("Conse_");
        $company = $request->input("company");

        $search = DB::table('transfer')
            ->leftjoin('contract', 'transfer.trasfer_contract', '=', 'contract.idcontract')
            ->where('contract.id_empresa', '=', $company)
            ->where('transfer_consec', $Conse_)
            ->select('transfer.*',
                DB::raw("(select  name from cellar where idcellar=transfer.transfer_cellar_o) as cellaro"),
                DB::raw("(select  name from cellar where idcellar=transfer.transfer_cellar_d) as cellard"))
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function ping()
    {

    }

}
