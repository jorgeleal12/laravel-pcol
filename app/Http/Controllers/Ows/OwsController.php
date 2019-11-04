<?php

namespace App\Http\Controllers\Ows;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Config;
use Facades\App\ClassPhp\Consecutive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwsController extends Controller
{
    //
    public function create(Request $request)
    {

        $body                   = $request->input("body");
        $id_company             = $request->input("company");
        $idow                   = $request->input("data.idows");
        $consecutive_ow         = $request->input("data.consecutive_ow");
        $idproviders            = $request->input("data.idproviders");
        $ow_date                = $request->input("data.ow_date");
        $ow_deliver_date        = $request->input("data.ow_deliver_date");
        $idow_cellar            = $request->input("data.ow_cellar");
        $ordername              = $request->input("data.ordername");
        $audit_order_date       = $request->input("data.audit_order_date");
        $ow_processing_date     = $request->input("data.ow_processing_date");
        $ow_response_time       = $request->input("data.ow_response_time");
        $provider_response      = $request->input("data.provider_response");
        $time_provider_response = $request->input("data.time_provider_response");
        $authorization_date     = $request->input("data.authorization_date");
        $batch_number           = $request->input("data.batch_number");
        $response_time_epm      = $request->input("data.response_time_epm");
        $ow_observations        = $request->input("data.ow_observations");

        $audit_order_date   = Carbon::parse($audit_order_date);
        $ow_processing_date = Carbon::parse($ow_processing_date);
        $provider_response  = Carbon::parse($provider_response);

        $ow_response_time       = $ow_processing_date->diffInDays($audit_order_date);
        $time_provider_response = $provider_response->diffInDays($ow_processing_date);
        $response_time_epm      = $provider_response->diffInDays($audit_order_date);

        if (isset($idow)) {

            $update = DB::table('ows')
                ->where('idows', $idow)
                ->update([
                    'ow_date'                => $ow_date,
                    'ow_deliver_date'        => $ow_deliver_date,
                    'ordername'              => $ordername,
                    'audit_order_date'       => $audit_order_date,
                    'ow_processing_date'     => $ow_processing_date,
                    'ow_response_time'       => $ow_response_time,
                    'provider_response'      => $provider_response,
                    'time_provider_response' => $time_provider_response,
                    'authorization_date'     => $authorization_date,
                    'batch_number'           => $batch_number,
                    'response_time_epm'      => $response_time_epm,
                    'ow_observations'        => $ow_observations,
                    'id_company'             => $id_company,
                    'id_company'             => $id_company,

                ]);
            $create = $idow;

            $consecutive = $consecutive_ow;

        } else {

            $documento   = Config::get('Config.ow.documento');
            $Consecutive = Consecutive::query_consecutive($id_company, 'OW');
            $consecutive = $Consecutive->consecutive;

            $create = DB::table('ows')
                ->insertGetId([

                    'consecutive_ow'         => $consecutive,
                    'id_providers'           => $idproviders,
                    'ow_date'                => $ow_date,
                    'ow_deliver_date'        => $ow_deliver_date,
                    'ow_cellar'              => $idow_cellar,
                    'ordername'              => $ordername,
                    'audit_order_date'       => $audit_order_date,
                    'ow_processing_date'     => $ow_processing_date,
                    'ow_response_time'       => $ow_response_time,
                    'provider_response'      => $provider_response,
                    'time_provider_response' => $time_provider_response,
                    'authorization_date'     => $authorization_date,
                    'batch_number'           => $batch_number,
                    'response_time_epm'      => $response_time_epm,
                    'ow_observations'        => $ow_observations,
                    'id_company'             => $id_company,
                    'ow_state'               => 1,

                ]);
            $ConseAtual = $Consecutive->consecutive + 1;

            $update = Consecutive::Updateconsecutive($id_company, $documento, $ConseAtual);
        }

        $this->recorrerbody($body, $create);

        return response()->json(['status' => 'Ok', 'Consecutive' => $consecutive, 'ow_response_time' => $ow_response_time, 'time_provider_response' => $time_provider_response, 'response_time_epm' => $response_time_epm], 200);
    }

    public function update()
    {

    }

    public function recorrerbody($body, $create)
    {

        for ($i = 0; $i < count($body); $i++) {

            $id_detail = isset($body[$i]['id_detail']) ? $body[$i]['id_detail'] : null;

            $id_material     = isset($body[$i]['id_material']) ? $body[$i]['id_material'] : null;
            $can_solicitadad = isset($body[$i]['can_solicitadad']) ? $body[$i]['can_solicitadad'] : null;
            $Vuni            = isset($body[$i]['Vuni']) ? $body[$i]['Vuni'] : null;
            $descu           = isset($body[$i]['descu']) ? $body[$i]['descu'] : null;
            $iva             = isset($body[$i]['iva']) ? $body[$i]['iva'] : null;
            $Viva            = isset($body[$i]['Viva']) ? $body[$i]['Viva'] : null;
            $Stotal          = isset($body[$i]['Stotal']) ? $body[$i]['Stotal'] : null;
            $total           = isset($body[$i]['total']) ? $body[$i]['total'] : null;

            if ($id_detail != null) {

                $this->update_body($create, $id_material, $can_solicitadad, $Vuni, $descu, $iva, $Viva, $Stotal, $total, $id_detail);

            } else {
                $this->Create_body($create, $id_material, $can_solicitadad, $Vuni, $descu, $iva, $Viva, $Stotal, $total);
            }

        }
    }

    public function Create_body($create, $id_material, $can_solicitadad, $Vuni, $descu, $iva, $Viva, $Stotal, $total)
    {

        $insert = DB::table('detail_ow')
            ->insert([
                'idow'            => $create,
                'id_material'     => $id_material,
                'can_solicitadad' => $can_solicitadad,
                'Vuni'            => $Vuni,
                'descu'           => $descu,
                'iva'             => $iva,
                'Viva'            => $Viva,
                'Stotal'          => $Stotal,
                'total'           => $total,

            ]);

    }

    public function update_body($create, $id_material, $can_solicitadad, $Vuni, $descu, $iva, $Viva, $Stotal, $total, $id_detail)
    {
        $insert = DB::table('detail_ow')
            ->where('id_detail', $id_detail)
            ->update([
                'idow'            => $create,
                'id_material'     => $id_material,
                'can_solicitadad' => $can_solicitadad,
                'Vuni'            => $Vuni,
                'descu'           => $descu,
                'iva'             => $iva,
                'Viva'            => $Viva,
                'Stotal'          => $Stotal,
                'total'           => $total,

            ]);
    }

    public function searc_one(Request $request)
    {

        $date_in  = $request->input("date_in");
        $date_end = $request->input("date_end");
        $company  = $request->input("company");

        $search = DB::table('ows')
            ->leftjoin('providers', 'ows.id_providers', '=', 'providers.idproviders')
            ->whereBetween('ow_date', [$date_in, $date_end])
            ->where('id_company', '=', $company)
            ->select('ows.*', 'providers.providers_name')
            ->get();

        return response()->json(['status' => 'Ok', 'result' => $search], 200);
    }

    public function searc(Request $request)
    {

        $idows = $request->input("idows");

        $Search_head = DB::table('ows')
            ->leftjoin('providers', 'ows.id_providers', '=', 'providers.idproviders')
            ->where('idows', $idows)
            ->first();

        $search_body = $this->search_body($idows);
        return response()->json(['status' => 'Ok', 'head' => $Search_head, 'body' => $search_body], 200);
    }

    public function search_body($idows)
    {

        $detail_purchases = DB::table('detail_ow')
            ->leftjoin('materiales', 'detail_ow.id_material', '=', 'materiales.idmateriales')
            ->leftjoin('unity', 'materiales.unity', '=', 'unity.idUnity')

            ->where('idow', $idows)
            ->select('detail_ow.*', 'materiales.description', 'materiales.code as cod_mater', 'materiales.code', 'unity.name_Unity')
            ->get();

        return $detail_purchases;
    }

}
