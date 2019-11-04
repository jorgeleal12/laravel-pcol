<?php

namespace App\Http\Controllers\Interna;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemsController extends Controller
{
    //

    public function insert(Request $request)
    {

        $id_obr = (int) $request->input("id_obr");

        $data = $request->input("data");

        for ($i = 0; $i < count($data); $i++) {

            $iditems_internas = isset($data[$i]["iditems_internas"]) ? $data[$i]["iditems_internas"] : 0;
            $codigo           = isset($data[$i]["codigo"]) ? $data[$i]["codigo"] : 0;
            $date             = isset($data[$i]["date"]) ? $data[$i]["date"] : 0;
            $date_acta        = isset($data[$i]["date_acta"]) ? $data[$i]["date_acta"] : 0;
            $iditem_cobro     = isset($data[$i]["iditem_cobro"]) ? $data[$i]["iditem_cobro"] : 0;
            $items_state      = isset($data[$i]["id_state"]) ? $data[$i]["id_state"] : 0;
            $quantity         = isset($data[$i]["quantity"]) ? $data[$i]["quantity"] : 0;
            $quantity_acta    = isset($data[$i]["quantity_acta"]) ? $data[$i]["quantity_acta"] : '';
            $acta             = isset($data[$i]["acta"]) ? $data[$i]["acta"] : 0;

            if ($iditems_internas == 0 and $codigo != 0) {

                $insert = DB::table('items_internas')
                    ->insert([
                        'id_items'     => $iditem_cobro,
                        'date'         => $date,
                        'quantity'     => $quantity,
                        'id_state'     => $items_state,
                        'quanity_acta' => $quantity_acta,
                        'date_acta'    => $date_acta,
                        'acta'         => $acta,
                        'id_obr'       => $id_obr,
                    ]);

            }

            if ($iditems_internas != 0 and $codigo != 0) {

                $Update = DB::table('items_internas')
                    ->where('iditems_internas', '=', $iditems_internas)
                    ->update([
                        'id_items'     => $iditem_cobro,
                        'date'         => $date,
                        'quantity'     => $quantity,
                        'id_state'     => $items_state,
                        'quanity_acta' => $quantity_acta,
                        'date_acta'    => $date_acta,
                        'acta'         => $acta,

                    ]);
            }

        }

        $search = ItemsController::search($id_obr);

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function search($id_obr)
    {
        $search = DB::table('items_internas')
            ->join('item_cobro', 'items_internas.id_items', '=', 'item_cobro.iditem_cobro')
            ->orderBy('iditems_internas', 'ASC')
            ->where('id_obr', '=', $id_obr)
            ->select('items_internas.*', 'item_cobro.item_cobro_code', 'item_cobro.item_cobro_code as codigo', 'item_cobro.item_cobro_name', 'items_internas.quanity_acta as quantity_acta'

                , 'items_internas.id_items as iditem_cobro')
            ->get();

        return $search;
    }

    public function search_items(Request $request)
    {
        $id_obr = (int) $request->input("id_obr");

        $search = ItemsController::search($id_obr);

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function delete_items(Request $request)
    {
        $id_obr       = (int) $request->input("id_obr");
        $iditem_cobro = (int) $request->input("iditem_cobro");

        $delete = DB::table('items_internas')
            ->where('iditems_internas', '=', $iditem_cobro)
            ->delete();

        $search = ItemsController::search($id_obr);

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }
}
