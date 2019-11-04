<?php

namespace App\Http\Controllers\Series;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeriesController extends Controller
{

    public function save(Request $request)
    {
        $data     = $request->input("data");
        $contract = $request->input("contract");
        $cellar   = $request->input("cellar");

        for ($i = 0; $i < count($data); $i++) {

            $checkbox      = isset($data[$i]["checkbox"]) ? $data[$i]["checkbox"] : false;
            $nserie        = isset($data[$i]["A"]) ? $data[$i]["A"] : '';
            $observaciones = isset($data[$i]["B"]) ? $data[$i]["B"] : '';

            try {
                $inserseries = DB::table('series')
                    ->insert([
                        'serie_nro_serie' => $nserie,
                        'serie_obs'       => $observaciones,
                        'serie_almacen'   => $cellar,
                        'contrac'         => $contract,
                        'serie_estado'    => 1,
                    ]);
                $result = true;
            } catch (\Exception $e) {
                $result = false;
            }

        }

        return response()->json(['status' => 'ok', 'data' => $result], 200);
    }

    public function search(Request $request)
    {
        $date_ini = $request->input("date_ini");
        $date_end = $request->input("date_end");
        $cellar   = $request->input("cellar");
        $contract = $request->input("contract");

        $search = DB::table('series')
        //->where('serie_almacen', $cellar)
            ->where('contrac', $contract)
            ->whereBetween('serie_fecha', [$date_ini, $date_end])
            ->select('series.serie_nro_serie as A', 'series.*')
            ->get();

        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function update(Request $request)
    {

        $idseries           = $request->input("data.idseries");
        $serie_almacen      = $request->input("data.serie_almacen");
        $serie_caja         = $request->input("data.serie_caja");
        $serie_codigo       = $request->input("data.serie_codigo");
        $serie_despacho     = $request->input("data.serie_despacho");
        $serie_entrega      = $request->input("data.serie_entrega");
        $serie_fecha        = $request->input("data.serie_fecha");
        $serie_flujo        = $request->input("data.serie_flujo");
        $serie_linea_codigo = $request->input("data.serie_linea_codigo");
        $serie_lote         = $request->input("data.serie_lote");
        $serie_marca        = $request->input("data.serie_marca");
        $serie_nro_serie    = $request->input("data.serie_nro_serie");
        $serie_obs          = $request->input("data.serie_obs");
        try {

            $update = DB::table('series')
                ->where('idseries', '=', $idseries)
                ->update(['serie_nro_serie' => $serie_nro_serie,
                    'serie_flujo'               => $serie_flujo,
                    'serie_codigo'              => $serie_codigo,
                    'serie_marca'               => $serie_marca,
                    'serie_lote'                => $serie_lote,
                    'serie_entrega'             => $serie_entrega,
                    'serie_caja'                => $serie_caja,
                    'serie_fecha'               => $serie_fecha,
                    'serie_obs'                 => $serie_obs,
                    'serie_despacho'            => $serie_despacho,
                    'serie_linea_codigo'        => $serie_linea_codigo,

                ]);

            $result = true;

        } catch (\Exception $e) {

            $result = false;
        }

        return response()->json(['status' => 'ok', 'result' => $result], 200);
    }

    public function delete(Request $request)
    {

        $idseries = $request->input("idseries");

        try {

            $delete = DB::table('series')
                ->where('idseries', '=', $idseries)
                ->delete();

            $result = true;
        } catch (\Exception $e) {
            $result = false;
        }
        return response()->json(['status' => 'ok', 'result' => $result], 200);

    }

    public function search_series(Request $request)
    {
        $serie_nro_serie = $request->input("term");
        $contrac         = $request->input("contrac");

        $series = DB::table('series')
            ->where('serie_nro_serie', 'like', '%' . $serie_nro_serie . '%')
            ->select('series.*')
            ->take(10)
            ->get();
        return response()->json(['status' => 'ok', 'results' => $series], 200);
    }

    // funcion para buscar por numero de serie
    public function searchs(Request $request)
    {
        $series = $request->input("series");

        $search = DB::table('series')
            ->where('serie_nro_serie', '=', $series)
            ->leftjoin('odi', 'odi.idsepo', '=', 'series.idobr')
            ->select('series.*', 'odi.consecutive')
            ->get();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

}
