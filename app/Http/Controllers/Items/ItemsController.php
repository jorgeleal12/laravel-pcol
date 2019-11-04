<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemsController extends Controller
{
    //

    public function insert(Request $request)
    {

        $company  = (int) $request->input("company");
        $contract = (int) $request->input("contract");

        $item_cobro_code   = $request->input("data.item_cobro_code");
        $item_cobro_name   = (String) $request->input("data.item_cobro_name");
        $item_cobro_unidad = (String) $request->input("data.item_cobro_unidad");

        $item_cobro_type          = (int) $request->input("data.item_cobro_type");
        $item_cobro_valor         = (float) $request->input("data.item_cobro_valor");
        $item_cobro_state         = (int) $request->input("data.item_cobro_state");
        $item_cobro_tipo          = (int) $request->input("data.item_cobro_tipo");
        $item_cobro_clasificacion = (int) $request->input("data.item_cobro_clasificacion");

        try {
            $insrt_item = DB::table('item_cobro')
                ->insert(['item_cobro_code' => $item_cobro_code,
                    'item_cobro_name'           => $item_cobro_name,
                    'item_cobro_type'           => $item_cobro_type,
                    'item_cobro_valor'          => $item_cobro_valor,
                    'item_cobro_state'          => $item_cobro_state,
                    'item_cobro_tipo'           => $item_cobro_tipo,
                    'item_cobro_clasificacion'  => $item_cobro_clasificacion,
                    'item_cobro_contract'       => $contract,
                    'item_cobro_unidad'         => $item_cobro_unidad,

                ]);

            $response = true;
        } catch (\Exception $e) {
            $response = false;
        }
        return response()->json(['status' => 'ok', 'data' => $response], 200);

    }

    public function update(Request $request)
    {

        $id = (int) $request->input("id");

        $item_cobro_code   = $request->input("code");
        $item_cobro_name   = (String) $request->input("descrip");
        $item_cobro_unidad = (String) $request->input("unidad");

        $item_cobro_type          = (int) $request->input("tipo_obra"); //tipo de insumo
        $item_cobro_valor         = (float) $request->input("valor");
        $item_cobro_state         = (int) $request->input("state");
        $item_cobro_tipo          = (int) $request->input("tipoins");
        $item_cobro_clasificacion = (int) $request->input("classf");

        try {
            $update = DB::table('item_cobro')
                ->where('iditem_cobro', '=', $id)
                ->update(['item_cobro_code' => $item_cobro_code,
                    'item_cobro_name'           => $item_cobro_name,
                    'item_cobro_type'           => $item_cobro_type,
                    'item_cobro_valor'          => $item_cobro_valor,
                    'item_cobro_state'          => $item_cobro_state,
                    'item_cobro_tipo'           => $item_cobro_tipo,
                    'item_cobro_clasificacion'  => $item_cobro_clasificacion,
                    'item_cobro_unidad'         => $item_cobro_unidad,

                ]);

            $response = true;
        } catch (\Exception $e) {
            $response = false;
        }
        return response()->json(['status' => 'ok', 'data' => $response], 200);

    }

    public function search(Request $request)
    {

        $company  = (int) $request->input("company");
        $contract = (int) $request->input("contract");
        $type_obr = (int) $request->input("type_obr");

        try {
            $search = DB::table('item_cobro')
                ->where('item_cobro_type', '=', $type_obr)
                ->where('item_cobro_contract', '=', $contract)
                ->get();

            $response = true;

        } catch (\Exception $e) {

            $response = false;
        }

        return response()->json(['status' => 'ok', 'data' => $response, 'search' => $search], 200);
    }

    public function autocomplecodeexternal(Request $request)
    {

        $company  = (int) $request->input("company");
        $contract = (int) $request->input("contract");
        $type_obr = (int) $request->input("type_obr");
        $code     = (String) $request->input("term");

        $search = DB::table('item_cobro')
            ->leftjoin('clasificacion_item', 'item_cobro.item_cobro_clasificacion', '=', 'clasificacion_item.idclasificacion_item')
            ->where('item_cobro_contract', '=', $contract)
            ->where('item_cobro_type', '=', 2)
            ->where('item_cobro_code', 'like', $code . '%')
        //->orderBy('item_cobro.item_cobro_code', 'ACS')
            ->select('item_cobro.*', 'item_cobro.item_cobro_code as item', 'clasificacion_item.clasificacion_name as item_presupuesto_class')
            ->take(20)
            ->get();

        return response()->json(['status' => 'ok', 'search' => $search], 200);

    }

    public function autocomplecodetopo(Request $request)
    {

        $company  = (int) $request->input("company");
        $contract = (int) $request->input("contract");
        $type_obr = (int) $request->input("type_obr");
        $code     = (String) $request->input("term");

        $search = DB::table('item_cobro')
            ->leftjoin('clasificacion_item', 'item_cobro.item_cobro_clasificacion', '=', 'clasificacion_item.idclasificacion_item')
            ->where('item_cobro_contract', '=', $contract)
            ->where('item_cobro_type', '=', 2)

        //->orWhere('item_cobro_clasificacion', '=', 19)
            ->where('item_cobro_code', 'like', $code . '%')
            ->whereIn('item_cobro.item_cobro_clasificacion', [46, 56])
            ->select('item_cobro.*', 'item_cobro.item_cobro_code as item', 'clasificacion_item.clasificacion_name as item_presupuesto_class')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'search' => $search], 200);

    }

    public function autocompleinternal(Request $request)
    {

        $company  = (int) $request->input("company");
        $contract = (int) $request->input("contract");
        $type_obr = (int) $request->input("type_obr");
        $code     = $request->input("term");

        $search = DB::table('item_cobro')
            ->where('item_cobro_contract', '=', $contract)
            ->where('item_cobro_type', '=', 1)
            ->where('item_cobro_code', 'like', $code . '%')
            ->select('item_cobro.*', 'item_cobro.item_cobro_code as item', 'item_cobro.item_cobro_code as item_cobro_code')
            ->orderBy('item_cobro_code', 'asc')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'search' => $search], 200);

    }

    public function autocomplenameinternas(Request $request)
    {

        $company  = (int) $request->input("company");
        $contract = (int) $request->input("contract");
        $type_obr = (int) $request->input("type_obr");
        $name     = (String) $request->input("term");

        $search = DB::table('item_cobro')
            ->where('item_cobro_contract', '=', $contract)
            ->where('item_cobro_type', '=', 1)
            ->where('item_cobro_name', 'like', $name . '%')
            ->select('item_cobro.*', 'item_cobro.item_cobro_code as item')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'search' => $search], 200);

    }

    public function autocompleoym(Request $request)
    {

        $company  = (int) $request->input("company");
        $contract = (int) $request->input("contract");
        $type_obr = (int) $request->input("type_obr");
        $code     = $request->input("term");

        $search = DB::table('item_cobro')
            ->where('item_cobro_contract', '=', $contract)
            ->where('item_cobro_type', '=', 3)
            ->where('item_cobro_code', 'like', $code . '%')
            ->select('item_cobro.*', 'item_cobro.item_cobro_code as item', 'item_cobro.item_cobro_code as item_cobro_code')
            ->orderBy('item_cobro_code', 'asc')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'search' => $search], 200);

    }

    public function autocomplenameoym(Request $request)
    {

        $company  = (int) $request->input("company");
        $contract = (int) $request->input("contract");
        $type_obr = (int) $request->input("type_obr");
        $name     = (String) $request->input("term");

        $search = DB::table('item_cobro')
            ->where('item_cobro_contract', '=', $contract)
            ->where('item_cobro_type', '=', 3)
            ->where('item_cobro_name', 'like', '%' . $name . '%')
            ->select('item_cobro.*', 'item_cobro.item_cobro_code as item')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'search' => $search], 200);

    }

    public function autocomplename(Request $request)
    {

        $company  = (int) $request->input("company");
        $contract = (int) $request->input("contract");
        $type_obr = (int) $request->input("type_obr");
        $name     = (String) $request->input("term");

        $search = DB::table('item_cobro')
            ->where('item_cobro_contract', '=', $contract)
            ->where('item_cobro_type', '=', 2)
            ->where('item_cobro_name', 'like', '%' . $name . '%')
            ->select('item_cobro.*', 'item_cobro.item_cobro_code as item')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'search' => $search], 200);

    }

    public function state_items()
    {
        $search = DB::table('estados_items')
            ->get();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function delete(Request $request)
    {
        $id = (int) $request->input("id");

        $delete = DB::table('item_cobro')
            ->where('iditem_cobro', '=', $id)
            ->delete();
        return response()->json(['status' => 'ok', 'search' => true], 200);
    }

    public function autocomplecodeexternal4(Request $request)
    {

        $company  = (int) $request->input("company");
        $contract = (int) $request->input("contract");
        $type_obr = (int) $request->input("type_obr");
        $code     = (String) $request->input("term");

        $search = DB::table('item_cobro')
            ->leftjoin('clasificacion_item', 'item_cobro.item_cobro_clasificacion', '=', 'clasificacion_item.idclasificacion_item')
            ->where('item_cobro_contract', '=', $contract)
            ->where('item_cobro_type', '=', 4)
            ->where('item_cobro_code', 'like', '%' . $code . '%')
            ->select('item_cobro.*', 'item_cobro.item_cobro_code as item', 'clasificacion_item.clasificacion_name as item_presupuesto_class')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'search' => $search], 200);

    }

    public function autocomplename4(Request $request)
    {

        $company  = (int) $request->input("company");
        $contract = (int) $request->input("contract");
        $type_obr = (int) $request->input("type_obr");
        $name     = (String) $request->input("term");

        $search = DB::table('item_cobro')
            ->where('item_cobro_contract', '=', $contract)
            ->where('item_cobro_type', '=', 4)
            ->where('item_cobro_name', 'like', '%' . $name . '%')
            ->select('item_cobro.*', 'item_cobro.item_cobro_code as item')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'search' => $search], 200);

    }
}
