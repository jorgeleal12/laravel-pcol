<?php

namespace App\Http\Controllers\Interna;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{

    public function insert(Request $request)
    {

        $id_obr = (int) $request->input("id_obr");
        $data   = $request->input("data");

        for ($i = 0; $i < count($data); $i++) {

            $idmaterial_internas = isset($data[$i]["idmaterial_internas"]) ? $data[$i]["idmaterial_internas"] : 0;
            $codigo              = isset($data[$i]["codigo"]) ? $data[$i]["codigo"] : '';
            $idmateriales        = isset($data[$i]["id_material"]) ? $data[$i]["id_material"] : 0;
            $date                = isset($data[$i]["date"]) ? $data[$i]["date"] : 0;
            $date_acta           = isset($data[$i]["date_acta"]) ? $data[$i]["date_acta"] : 0;
            $id_state            = isset($data[$i]["id_state"]) ? $data[$i]["id_state"] : 0;
            $quantity            = isset($data[$i]["quantity"]) ? $data[$i]["quantity"] : 0;
            $quantity_acta       = isset($data[$i]["quantity_acta"]) ? $data[$i]["quantity_acta"] : 0;
            $acta                = isset($data[$i]["acta"]) ? $data[$i]["acta"] : '';

            if ($idmaterial_internas == 0 and $codigo != '') {

                $insert = DB::table('material_internas')
                    ->insert([
                        'id_material'   => $idmateriales,
                        'date'          => $date,
                        'quantity'      => $quantity,
                        'id_state'      => $id_state,
                        'quantity_acta' => $quantity_acta,
                        'date_acta'     => $date_acta,
                        'acta'          => $acta,
                        'id_obr'        => $id_obr,
                    ]);

            } else {

                $update = DB::table('material_internas')
                    ->where('idmaterial_internas', '=', $idmaterial_internas)
                    ->update([
                        'id_material'   => $idmateriales,
                        'date'          => $date,
                        'quantity'      => $quantity,
                        'id_state'      => $id_state,
                        'quantity_acta' => $quantity_acta,
                        'date_acta'     => $date_acta,
                        'acta'          => $acta,
                    ]);

            }
        }

        $search = MaterialController::search($id_obr);

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function searchmaterial(Request $request)
    {

        $id_obr = (int) $request->input("id_obr");

        $search = MaterialController::search($id_obr);

        return response()->json(['status' => 'ok', 'response' => $search], 200);

    }

    public function search($id_obr)
    {

        $search = DB::table('material_internas')
            ->join('materiales', 'material_internas.id_material', '=', 'materiales.idmateriales')
            ->orderBy('idmaterial_internas', 'ASC')
            ->where('id_obr', '=', $id_obr)
            ->select('material_internas.*', 'materiales.code as codigo', 'materiales.code', 'materiales.description')
            ->get();

        return $search;
    }

    public function delete_material(Request $request)
    {
        $idmaterial_internas = $request->input("id");
        $id_obr              = $request->input("d_obr");

        $delete = DB::table('material_internas')
            ->where('idmaterial_internas', '=', $idmaterial_internas)
            ->delete();

        $search = MaterialController::search($id_obr);

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }
}
