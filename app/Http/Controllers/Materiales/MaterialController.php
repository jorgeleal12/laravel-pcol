<?php

namespace App\Http\Controllers\Materiales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{

    private $code;
    private $state;
    private $input_type;
    private $unity;
    private $serie;
    private $average_value;
    private $minimum_inventory;
    private $iva;
    private $description;

    //funcion que consulta el material
    public function query(Request $request)
    {

        $this->code = (String) $request->input("code");

        $materiales = DB::table('materiales')
            ->join('unity', 'materiales.unity', '=', 'unity.idUnity')
            ->join('type_input', 'materiales.input_type', '=', 'type_input.idType_input')
            ->where('materiales.code', $this->code)
            ->select('materiales.*', 'unity.name_unity', 'type_input.name_Type')
            ->first();

        if (!$materiales) {
            $response = false;

        } else {
            $response = true;

        }

        return response()->json(['status' => 'ok', 'data' => $response, 'material' => $materiales], 200);

    }

    // function para atualizar el material
    public function update(Request $request)
    {

        $this->idmateriales      = (String) $request->input("idmateriales");
        $this->code              = (String) mb_strtoupper($request->input("code"));
        $this->state             = (Int) $request->input("state");
        $this->input_type        = (Int) $request->input("input_type");
        $this->unity             = (Int) $request->input("unity");
        $this->serie             = (Int) $request->input("serie");
        $this->average_value     = (float) $request->input("average_value");
        $this->minimum_inventory = (float) $request->input("minimum_inventory");
        $this->iva               = (float) $request->input("iva");
        $this->description       = (String) mb_strtoupper($request->input("description"));

        try {

            $Update = DB::table('materiales')
                ->where('code', $this->code)
                ->where('idmateriales', $this->idmateriales)
                ->update(
                    ['state' => $this->state, 'input_type' => $this->input_type, 'unity' => $this->unity, 'serie' => $this->serie, 'average_value' => $this->average_value, 'minimum_inventory' => $this->minimum_inventory, 'iva' => $this->iva, 'description' => $this->description]
                );
            $response = true;
        } catch (\Exception $e) {
            $response = false;
        }

        return response()->json(['status' => 'ok', 'data' => $response], 200);
    }

    // funcion para crear material
    public function create(Request $request)
    {

        $this->code              = (String) mb_strtoupper($request->input("code"));
        $this->state             = (Int) $request->input("state");
        $this->input_type        = (Int) $request->input("input_type");
        $this->unity             = (Int) $request->input("unity");
        $this->serie             = (Int) $request->input("serie");
        $this->average_value     = (float) $request->input("average_value");
        $this->minimum_inventory = (float) $request->input("minimum_inventory");
        $this->iva               = (float) $request->input("iva");
        $this->description       = (String) mb_strtoupper($request->input("description"));

        $this->description = mb_strtoupper($this->description);
        $this->code        = mb_strtoupper($this->code);

        $Insert = DB::table('materiales')->insert(
            ['code' => $this->code, 'state' => $this->state, 'input_type' => $this->input_type, 'unity' => $this->unity, 'serie' => $this->serie, 'average_value' => $this->average_value, 'minimum_inventory' => $this->minimum_inventory, 'iva' => $this->iva, 'description' => $this->description]
        );

        if (!$Insert) {
            $response = false;

        } else {
            $response = true;

        }
        return response()->json(['status' => 'ok', 'data' => $response], 200);
    }

    // funcion para eliminar material
    public function delete(Request $request)
    {

        $this->code         = (String) $request->input("code");
        $this->idmateriales = (String) $request->input("id");

        $delete = DB::table('materiales')
            ->where('code', $this->code)
            ->where('idmateriales', $this->idmateriales)
            ->delete();

        if (!$delete) {
            $response = false;

        } else {
            $response = true;

        }
        return response()->json(['status' => 'ok', 'data' => $response], 200);

    }
    //funcion para autocomplete por codigo
    public function AutoQueryCode(Request $request)
    {

        $this->code = (String) $request->input("term");

        $materiales = DB::table('materiales')
            ->join('unity', 'materiales.unity', '=', 'unity.idUnity')
            ->where('code', 'like', '%' . $this->code . '%')
            ->select('materiales.*', 'unity.name_Unity')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'materiales' => $materiales], 200);
    }

    //funcion para autocomple por descripcion
    public function AutoQueryDescry(Request $request)
    {

        $this->description = (String) $request->input("term");

        $materiales = DB::table('materiales')
            ->where('description', 'like', '%' . $this->description . '%')
            ->select('materiales.*')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'results' => $materiales], 200);
    }

    public function query_inventmate_code(Request $request)
    {

        $code   = (String) $request->input("term");
        $cellar = (int) $request->input("cellar");

        $materiales = DB::table('materiales')
            ->join('unity', 'materiales.unity', '=', 'unity.idUnity')
            ->join('inventario_cellar', 'materiales.idmateriales', '=', 'inventario_cellar.id_material')

            ->where('materiales.code', 'like', $code . '%')
            ->where('inventario_cellar.id_cellar', $cellar)
            ->select('materiales.*', 'unity.name_Unity', 'inventario_cellar.inventary_quantity')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'results' => $materiales], 200);

    }

    public function query_inventmate_descrip(Request $request)
    {

        $description = (String) $request->input("term");
        $cellar      = (int) $request->input("cellar");

        $materiales = DB::table('materiales')
            ->join('unity', 'materiales.unity', '=', 'unity.idUnity')
            ->leftjoin('inventario_cellar', 'materiales.idmateriales', '=', 'inventario_cellar.id_material')

            ->where('materiales.description', 'like', $description . '%')
            ->where('inventario_cellar.id_cellar', $cellar)
            ->select('materiales.*', 'unity.name_Unity', 'inventario_cellar.inventary_quantity')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'results' => $materiales], 200);

    }

    public function inventary(Request $request)
    {

        $idmateriales = (String) $request->input("idmateriales");
        $company      = (int) $request->input("company");

        $serach = DB::table('inventario_cellar')
            ->join('cellar', 'inventario_cellar.id_cellar', '=', 'cellar.idcellar')
            ->join('materiales', 'inventario_cellar.id_material', '=', 'materiales.idmateriales')
            ->where('id_material', '=', $idmateriales)
            ->where('cellar.id_empresa', '=', $company)
            ->select('inventario_cellar.*', 'cellar.name', 'materiales.*')
            ->get();
        return response()->json(['status' => 'ok', 'results' => $serach], 200);
    }

    public function historico(Request $request)
    {

        $company      = $request->input("company");
        $idmateriales = $request->input("idmateriales");
        $cellar       = $request->input("cellar");

        $search = DB::table('historical_inventory')
            ->leftjoin('materiales', 'historical_inventory.id_code', '=', 'materiales.idmateriales')
            ->leftjoin('employees', 'historical_inventory.user', '=', 'employees.Users_id_identification')
            ->where('cellar', $cellar)
            ->where('idcompany', $company)
            ->where('id_code', $idmateriales)
            ->orderBy('idhistorical_inventory', 'asc')
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

}
