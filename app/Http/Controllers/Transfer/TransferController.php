<?php

namespace App\Http\Controllers\Transfer;

use App\Http\Controllers\Controller;
use Config;
use Facades\App\ClassPhp\Consecutive;
use Facades\App\ClassPhp\log;
use Facades\App\ClassPhp\UpdateInventary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{

    public function InsertHead(Request $request)
    {

        $cellar_destino = $request->input("head.transfer_cellar_d");
        $cellar_origen  = $request->input("head.transfer_cellar_o");
        $date           = $request->input("head.transfer_date");
        $observaciones  = $request->input("head.transfer_observa");
        $contract       = $request->input("contract");
        $company        = $request->input("company");

        $body = $request->input("body");
        $user = $request->input("user");

        $documento = Config::get('Config.traslado.documento');

        $Consecutive = Consecutive::query_consecutive($company, $documento);
        $Consec      = $Consecutive->consecutive;

        try {

            $InsertHead = DB::table('transfer')->insertGetId([
                'transfer_date'     => $date,
                'transfer_cellar_o' => $cellar_origen,
                'transfer_cellar_d' => $cellar_destino,
                'transfer_observa'  => $observaciones,
                'trasfer_contract'  => $contract,
                'transfer_consec'   => $Consec,

            ]);

            $recorrer = TransferController::recorrer($body, $InsertHead, $company, $Consec, $cellar_destino, $cellar_origen, $user);

            $ConseAtual = (Int) $Consec + 1;
            $update     = Consecutive::Updateconsecutive($company, $documento, $ConseAtual);

            $text_insert = Config::get('Config.traslado.text_insert');

            log::insert_log($user, $text_insert, $Consec, $company);

            return response()->json(['status' => 'ok', 'consecutive' => $Consec], 200);

        } catch (\Exception $e) {

        }

        //return response()->json(['status' => 'ok', 'data' => $response, 'consecutive' => $Consec, 'idrefund_masive' => $refundm_head], 200);
    }

// funcion para rrecorrer el cuerpo de cada movimiento
    public function recorrer($body, $InsertHead, $company, $Consec, $cellar_destino, $cellar_origen, $user)
    {

        for ($i = 0; $i < count($body); $i++) {

            $code_mater        = isset($body[$i]["idmateriales"]) ? $body[$i]["idmateriales"] : 0;
            $quantity          = isset($body[$i]["quantity"]) ? $body[$i]["quantity"] : 0;
            $iddetail_transfer = isset($body[$i]["iddetail_transfer"]) ? $body[$i]["iddetail_transfer"] : 0;

            if ($iddetail_transfer != 0) {

                $updatebody = TransferController::UpdateBody($code_mater, $quantity, $InsertHead, $company, $Consec, $iddetail_transfer, $cellar_destino, $cellar_origen, $user);

            } else {

                $insertbody = TransferController::insertbody($code_mater, $quantity, $InsertHead, $company, $Consec, $cellar_destino, $cellar_origen, $user);

            }

        }
    }

    public function insertbody($code_mater, $quantity, $InsertHead, $company, $Consec, $cellar_destino, $cellar_origen, $user)
    {

        $insertBody = DB::table("detail_transfer")
            ->insert([
                'idtransfer'               => $InsertHead,
                'detail_transfer_cod'      => $code_mater,
                'detail_transfer_quantity' => $quantity,

            ]);

        $tipo = Config::get('Config.traslado.text_insert');

        $UpdateInventary = UpdateInventary::AddInventary($company, $cellar_destino, $code_mater, $quantity, $insertBody, $Consec, $tipo);

        $UpdateInventary = UpdateInventary::subtract($company, $cellar_origen, $code_mater, $quantity, $insertBody, $Consec, $tipo);

    }

    public function search(Request $request)
    {
        $date_end = $request->input("date_end");
        $date_ini = $request->input("date_ini");
        $company  = $request->input("company");

        $search = DB::table('transfer')
            ->join('cellar', 'transfer.transfer_cellar_o', '=', 'cellar.idcellar')
            ->whereBetween('transfer_date', [$date_ini, $date_end])
            ->where('cellar.id_empresa', $company)
            ->select('transfer.*',
                DB::raw("(select  name from cellar where idcellar=transfer.transfer_cellar_o) as cellaro"),
                DB::raw("(select  name from cellar where idcellar=transfer.transfer_cellar_d) as cellard"))
            ->get();

        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function searchhead(Request $request)
    {
        $idtransfer = $request->input("data");
        $company    = $request->input("company");

        $search = DB::table('transfer')
            ->where('idtransfer', '=', $idtransfer)
            ->select('transfer.*')
            ->first();

        $body = TransferController::searchbody($idtransfer, $company, $search->transfer_cellar_o);

        return response()->json(['search' => $search, 'body' => $body], 200);
    }

    public function searchbody($idtransfer, $company, $transfer_cellar_o)
    {

        $searchb = DB::table('detail_transfer')
            ->join('materiales', 'materiales.idmateriales', '=', 'detail_transfer.detail_transfer_cod')
            ->join('unity', 'unity.idUnity', '=', 'materiales.unity')
            ->where('idtransfer', '=', $idtransfer)
            ->select('detail_transfer.*', 'materiales.description', 'materiales.idmateriales', 'unity.name_Unity', 'detail_transfer.detail_transfer_quantity as quantity', 'materiales.code', 'materiales.code as cod_mater',
                DB::raw("(select  inventary_quantity from inventario_cellar where id_cellar='$transfer_cellar_o' AND id_material=detail_transfer.detail_transfer_cod ) as inventary_quantity"))
            ->get();

        return $searchb;
    }

    public function updatehead(Request $request)
    {
        $cellar_destino = (INT) $request->input("head.transfer_cellar_d");
        $cellar_origen  = (INT) $request->input("head.transfer_cellar_o");
        $date           = $request->input("head.transfer_date");
        $observaciones  = $request->input("head.transfer_observa");
        $Consec         = $request->input("head.transfer_consec");
        $idtransfer     = $request->input("head.idtransfer");
        $contract       = $request->input("contract");
        $company        = $request->input("company");

        $body = $request->input("body");
        $user = $request->input("user");

        try {

            $UpdateHead = DB::table('transfer')
                ->where('idtransfer', '=', $idtransfer)

                ->update(['transfer_date' => $date, 'transfer_observa' => $observaciones,
                ]);

            $recorrer = TransferController::recorrer($body, $idtransfer, $company, $Consec, $cellar_destino, $cellar_origen);

            $text_update = Config::get('Config.traslado.text_update');

            log::insert_log($user, $text_update, $Consec, $company);

            $body = TransferController::searchbody($idtransfer, $company, $cellar_origen);

            return response()->json(['status' => 'ok', 'data' => $body], 200);

        } catch (Exception $e) {

        }
    }

    public function UpdateBody($code_mater, $quantity, $InsertHead, $company, $Consec, $iddetail_transfer, $cellar_destino, $cellar_origen, $user)
    {

        $DetailBody = TransferController::DetailBody($iddetail_transfer, $company);

        $cantidadAnterior = $DetailBody->detail_transfer_quantity;

        $ValidarC = TransferController::ValidarCatidades($cantidadAnterior, $quantity, $code_mater, $company, $Consec, $iddetail_transfer, $cellar_destino, $cellar_origen);

        $update = DB::table('detail_transfer')
            ->where('iddetail_transfer', '=', $iddetail_transfer)

            ->update([

                'detail_transfer_quantity' => $quantity,
            ]);
    }

    public function DetailBody($iddetail_transfer, $company)
    {
        $DetailBody = DB::table('detail_transfer')
            ->where('iddetail_transfer', '=', $iddetail_transfer)
            ->select('detail_transfer.detail_transfer_quantity')
            ->first();

        return $DetailBody;
    }
// funcion para valoidar la cantidad anterior con la cantidad actual
    public function ValidarCatidades($cantidadAnterior, $quantity, $code_mater, $company, $Consec, $iddetail_transfer, $cellar_destino, $cellar_origen)
    {
        $tipo = Config::get('Config.traslado.text_update');

        // 6               10
        if ($cantidadAnterior < $quantity) {

            $cantidad = (FLOAT) $quantity - $cantidadAnterior;

            $UpdateInventary = UpdateInventary::AddInventary($company, $cellar_destino, $code_mater, $cantidad, $Consec, $iddetail_transfer, $tipo);
            $UpdateInventary = UpdateInventary::subtract($company, $cellar_origen, $code_mater, $cantidad, $Consec, $iddetail_transfer, $tipo);

        }
        //10             6
        if ($cantidadAnterior > $quantity) {

            $cantidad = (FLOAT) $cantidadAnterior - $quantity;

            $UpdateInventary = UpdateInventary::AddInventary($company, $cellar_origen, $code_mater, $cantidad, $Consec, $iddetail_transfer, $tipo);
            $UpdateInventary = UpdateInventary::subtract($company, $cellar_destino, $code_mater, $cantidad, $Consec, $iddetail_transfer, $tipo);

        }

    }

}
