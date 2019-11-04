<?php

namespace App\Http\Controllers\interna;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TravelController extends Controller
{
    public function search_recorrodor(Request $request)
    {

        $company = $request->input("company");

        $search = DB::table('worki')
            ->leftjoin('ot', 'worki.idworkI', '=', 'ot.id_obr')
            ->leftjoin('employees', 'employees.idemployees', '=', 'worki.programado_A')

            ->leftjoin('tipos_obr_internas', 'tipos_obr_internas.idtipos_obr_internas', '=', 'worki.worki_type_obr')
            ->leftjoin('state_obr', 'state_obr.idstate_obr', '=', 'worki.worki_state')

            ->leftjoin('municipality', 'municipality.id_dane', '=', 'worki.Municipio')
            ->whereIn('ot.sub_estado', [12, 17])
        //  ->Where('worki.id_company', '=', $company)
            ->groupBy('ot.id_obr')
        // ->groupBy('worki.programado_A')
            ->select('worki.programado_A', 'worki.consecutive', 'worki.Atualizacion', 'worki.Pedido', 'worki.Instalacion', 'worki.Direccion', 'worki.Solicitante', 'worki.Cedula', 'tipos_obr_internas.tipos_obr_internas_name', 'state_obr.state_obr_name', 'municipality.name_municipality', 'worki.idworkI', 'worki.Telefono', 'worki.Tel_Contacto', 'worki.Zona', 'worki.x', 'worki.y', 'worki.lng', 'worki.lat',

                DB::raw('count(worki.programado_A) as total'), DB::raw("CONCAT(name,' ',last_name) AS nameprogramado"))
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function saverecorredor(Request $request)
    {
        $data       = $request->input("data");
        $idemployee = $request->input("idemployee");
        $date_re    = $request->input("date_re");
        $hoy        = $request->input("hoy");

        for ($i = 0; $i < count($data); $i++) {

            $checkbox = isset($data[$i]["checkbox"]) ? $data[$i]["checkbox"] : $checkbox = false;
            $idworkI  = $data[$i]["idworkI"];
            var_dump($checkbox);
            if ($checkbox == true) {

                $update = DB::table('ot')
                    ->Where('id_obr', '=', $idworkI)
                    ->whereIn('sub_tipo', [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 16, 17, 18, 24, 25])
                    ->update([
                        'fprogra'    => $date_re,
                        'fstate'     => $hoy,
                        'sub_estado' => 18,
                    ]);

                $updateobr = DB::table('worki')
                    ->Where('idworkI', '=', $idworkI)
                    ->update([
                        'Fecha_Prog'   => $date_re,
                        'programado_A' => $idemployee,
                        'worki_state'  => 7,
                        'Fecha_Estado' => $hoy,
                    ]);
            }
        }

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function search_porprogramar(Request $request)
    {

        $company = $request->input("company");
        $search  = DB::table('worki')
            ->leftjoin('ot', 'worki.idworkI', '=', 'ot.id_obr')
            ->leftjoin('employees', 'employees.idemployees', '=', 'worki.programado_A')

            ->leftjoin('tipos_obr_internas', 'tipos_obr_internas.idtipos_obr_internas', '=', 'worki.worki_type_obr')
            ->leftjoin('state_obr', 'state_obr.idstate_obr', '=', 'worki.worki_state')

            ->leftjoin('municipality', 'municipality.id_dane', '=', 'worki.Municipio')
            ->Where('ot.sub_estado', '=', '13')
            ->Where('worki.id_company', '=', $company)
            ->groupBy('ot.id_obr')
        // ->groupBy('worki.programado_A')
            ->select('worki.programado_A', 'worki.consecutive', 'worki.Atualizacion', 'worki.Pedido', 'worki.Instalacion', 'worki.Direccion', 'worki.Solicitante', 'worki.Cedula', 'tipos_obr_internas.tipos_obr_internas_name', 'state_obr.state_obr_name', 'municipality.name_municipality', 'worki.idworkI', 'worki.Telefono', 'worki.Tel_Contacto', 'worki.Zona', 'worki.x', 'worki.y',

                DB::raw('count(worki.programado_A) as total'), DB::raw("CONCAT(name,' ',last_name) AS nameprogramado"))
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);

    }

    public function saveprogramacion(Request $request)
    {
        $data       = $request->input("data");
        $idemployee = $request->input("idemployee");
        $date_porp  = $request->input("date_porp");
        $hoy        = $request->input("hoy");

        for ($i = 0; $i < count($data); $i++) {

            $checkbox = isset($data[$i]["checkbox"]) ? $data[$i]["checkbox"] : $checkbox = false;
            $idworkI  = $data[$i]["idworkI"];

            if ($checkbox == true) {

                $update = DB::table('ot')
                    ->Where('id_obr', '=', $idworkI)
                    ->whereIn('sub_tipo', [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 16, 17, 18, 24, 25])
                    ->update([
                        'fprogra'    => $date_porp,
                        'fstate'     => $hoy,
                        'sub_estado' => 16,
                    ]);

                $updateobr = DB::table('worki')
                    ->Where('idworkI', '=', $idworkI)
                    ->update([
                        'Fecha_Prog'   => $date_porp,
                        'programado_A' => $idemployee,
                        'worki_state'  => 4,
                        'Fecha_Estado' => $hoy,
                    ]);
            }
        }

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function programada(Request $request)
    {

        $company = $request->input("company");
        $date    = $request->input("date");

        $search = DB::table('worki')
            ->leftjoin('ot', 'worki.idworkI', '=', 'ot.id_obr')
            ->leftjoin('employees', 'employees.idemployees', '=', 'worki.programado_A')

            ->leftjoin('tipos_obr_internas', 'tipos_obr_internas.idtipos_obr_internas', '=', 'worki.worki_type_obr')
            ->leftjoin('state_obr', 'state_obr.idstate_obr', '=', 'worki.worki_state')

            ->leftjoin('municipality', 'municipality.id_dane', '=', 'worki.Municipio')
            ->Where('ot.sub_estado', '=', '16')
            ->Where('worki.id_company', '=', $company)
            ->Where('worki.Fecha_Prog', '=', $date)
            ->groupBy('ot.id_obr')
        // ->groupBy('worki.programado_A')
            ->select('worki.programado_A', 'worki.consecutive', 'worki.Atualizacion', 'worki.Pedido', 'worki.Instalacion', 'worki.Direccion', 'worki.Solicitante', 'worki.Cedula', 'tipos_obr_internas.tipos_obr_internas_name', 'state_obr.state_obr_name', 'municipality.name_municipality', 'worki.idworkI', 'worki.Telefono', 'worki.Tel_Contacto', 'worki.Zona', 'worki.x', 'worki.y',

                DB::raw('count(worki.programado_A) as total'), DB::raw("CONCAT(name,' ',last_name) AS nameprogramado"))
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);

    }
}
