<?php

namespace App\Http\Controllers\Lists;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListsController extends Controller
{

    private $company;

    // funcion para los estados de compras y ingresos
    public function state_moves()
    {

        $state_moves = DB::table('state_moves')
            ->select('state_moves.*')
            ->get();

        return response()->json(['status' => 'ok', 'state_moves' => $state_moves], 200);
    }

    // funcion para consultar los almacenes
    public function cellar(Request $request)
    {

        $company = (Int) $request->input("idcompany");

        $cellar = DB::table('cellar')
            ->where('id_empresa', $company)
            ->select('cellar.*')
            ->get();

        return response()->json(['status' => 'ok', 'cellar' => $cellar], 200);
    }

    public function ArrayCellar(Request $request)
    {

        $company = $request->input("company");
        $cellar  = $request->input("data");

        $cellar = DB::table('cellar')

            ->whereIn('idcellar', $cellar)
            ->where('id_empresa', $company)
            ->select('cellar.*')
            ->get();

        return response()->json(['status' => 'ok', 'cellar' => $cellar], 200);
    }

    public function income_move()
    {
        $moves_income = DB::table('income_move')
            ->select('income_move.*')
            ->get();

        return response()->json(['status' => 'ok', 'moves_income' => $moves_income], 200);
    }

    public function dispatches_move()
    {

        $dispatches_move = DB::table('dispatches_move')
            ->select('dispatches_move.*')
            ->get();

        return response()->json(['status' => 'ok', 'dispatches_move' => $dispatches_move], 200);
    }

    public function destination_dispatches()
    {
        $destination_dispatches = DB::table('destination_dispatches')
            ->select('destination_dispatches.*')
            ->get();

        return response()->json(['status' => 'ok', 'destination_dispatches' => $destination_dispatches], 200);}

    public function departamentos()
    {

        $departments = DB::table('departments')
            ->select('departments.*')
            ->get();

        return response()->json(['status' => 'ok', 'departments' => $departments], 200);
    }

    public function municipios(Request $request)
    {
        $id_departamento = (Int) $request->input("id_departamento");

        $municipality = DB::table('municipality')
            ->where('id_departament', $id_departamento)
            ->select('municipality.*')
            ->get();

        return response()->json(['status' => 'ok', 'municipality' => $municipality], 200);
    }

    public function sexo()
    {

        $sexo = DB::table('sexo')
            ->select('sexo.*')
            ->get();

        return response()->json(['status' => 'ok', 'sexo' => $sexo], 200);
    }

    public function account_type()
    {

        $account_type = DB::table('account_type')
            ->select('account_type.*')
            ->get();

        return response()->json(['status' => 'ok', 'account_type' => $account_type], 200);
    }

    public function bank()
    {

        $bank = DB::table('bank')
            ->select('bank.*')
            ->get();

        return response()->json(['status' => 'ok', 'bank' => $bank], 200);
    }

    public function eps()
    {

        $eps = DB::table('eps')
            ->select('eps.*')
            ->get();

        return response()->json(['status' => 'ok', 'eps' => $eps], 200);
    }

    public function pensions()
    {

        $pensions = DB::table('pensions')
            ->select('pensions.*')
            ->get();

        return response()->json(['status' => 'ok', 'pensions' => $pensions], 200);
    }

    public function states()
    {

        $states = DB::table('states')
            ->select('states.*')
            ->get();

        return response()->json(['status' => 'ok', 'states' => $states], 200);
    }

    public function arl()
    {

        $arl = DB::table('arl')
            ->select('arl.*')
            ->get();

        return response()->json(['status' => 'ok', 'arl' => $arl], 200);
    }

    public function charges(Request $request)
    {

        $company = (Int) $request->input("company");

        $charges = DB::table('sub_charge')
            ->select('sub_charge.*')
            ->get();

        return response()->json(['status' => 'ok', 'charges' => $charges], 200);
    }

    public function type_charges()
    {

        $type_charges = DB::table('charges')
            ->select('charges.*')
            ->get();

        return response()->json(['status' => 'ok', 'type_charges' => $type_charges], 200);
    }

    public function clasificaciones()
    {

        $clasificaciones = DB::table('clasificaciones')
            ->select('clasificaciones.*')
            ->get();

        return response()->json(['status' => 'ok', 'clasificaciones' => $clasificaciones], 200);
    }

    public function education_level()
    {

        $education_level = DB::table('education_level')
            ->select('education_level.*')
            ->get();

        return response()->json(['status' => 'ok', 'education_level' => $education_level], 200);
    }

    public function profiles(Request $request)
    {

        $company = (Int) $request->input("company");

        $p_profiles = DB::table('p_profiles')
            ->where('id_company', '=', $company)
            ->select('p_profiles.*')
            ->get();

        return response()->json(['status' => 'ok', 'p_profiles' => $p_profiles], 200);
    }

    public function place_of_work(Request $request)
    {

        $company       = (Int) $request->input("company");
        $place_of_work = DB::table('place_of_work')
            ->where('company', '=', $company)
            ->select('place_of_work.*')
            ->get();

        return response()->json(['status' => 'ok', 'place_of_work' => $place_of_work], 200);
    }

    public function contract(Request $request)
    {

        $company  = (Int) $request->input("company");
        $contract = DB::table('contract')
            ->where('id_empresa', '=', $company)
            ->select('contract.*')
            ->get();

        return response()->json(['status' => 'ok', 'contract' => $contract], 200);
    }

    public function gangs(Request $request)
    {

        $company = (Int) $request->input("company");
        $gangs   = DB::table('gangs')
            ->where('company', '=', $company)
            ->select('gangs.*')
            ->get();

        return response()->json(['status' => 'ok', 'gangs' => $gangs], 200);
    }

    public function civil_status()
    {

        $civil_status = DB::table('civil_status')
            ->select('civil_status.*')
            ->get();

        return response()->json(['status' => 'ok', 'civil_status' => $civil_status], 200);
    }

    public function location(Request $request)
    {

        $company  = (Int) $request->input("company");
        $location = DB::table('location')
            ->where('company', '=', $company)
            ->select('location.*')
            ->get();

        return response()->json(['status' => 'ok', 'location' => $location], 200);
    }

    public function type_contract()
    {

        $type_contract = DB::table('type_contract')
            ->select('type_contract.*')
            ->get();

        return response()->json(['status' => 'ok', 'type_contract' => $type_contract], 200);
    }

    public function company(Request $request)
    {
        $company = (Int) $request->input("company");

        $company = DB::table('business')
            ->where('idbusiness', '=', $company)
            ->select('business.*')
            ->first();

        return response()->json(['status' => 'ok', 'business' => $company], 200);
    }

    public function local_contract(Request $request)
    {
        $company  = (Int) $request->input("company");
        $contract = (Int) $request->input("contract");

        $local_contract = DB::table('contract')
            ->where('id_empresa', '=', $company)
            ->where('idcontract', '=', $contract)
            ->select('contract.*')
            ->first();

        return response()->json(['status' => 'oak', 'contract' => $local_contract], 200);
    }

    public function list_profiles(Request $request)
    {
        $company = (Int) $request->input("company");

        $search = DB::table('p_profiles')
            ->where('id_company', '=', $company)
            ->select('p_profiles.*')
            ->get();

        return response()->json(['status' => 'ok', 'profiles' => $search], 200);
    }

    public function list_tipeext()
    {

        $type_obraext = DB::table('type_obraext')
            ->select('type_obraext.*')
            ->get();

        return response()->json(['status' => 'ok', 'type_obraext' => $type_obraext], 200);
    }

    public function state_ext()
    {

        $state_ext = DB::table('state_ext')
            ->select('state_ext.*')
            ->get();

        return response()->json(['status' => 'ok', 'state_ext' => $state_ext], 200);
    }

    public function state_anillo()
    {

        $state_anillo = DB::table('state_anillo')
            ->select('state_anillo.*')
            ->get();

        return response()->json(['status' => 'ok', 'state_anillo' => $state_anillo], 200);
    }

    public function type_obr_anillo()
    {

        $type_obr_anillo = DB::table('type_obr_anillo')
            ->select('type_obr_anillo.*')
            ->get();

        return response()->json(['status' => 'ok', 'type_obr_anillo' => $type_obr_anillo], 200);

    }
// funcion que consulta la lista de tipos de suministros para los items de cobra externas
    public function tipo_s_item()
    {

        $tipo_s_item = DB::table('tipo_s_item')
            ->select('tipo_s_item.*')
            ->get();

        return response()->json(['status' => 'ok', 'tipo_s_item' => $tipo_s_item], 200);
    }

    public function tipo_item(Request $request)
    {

        $company = (Int) $request->input("company");
//tipo de obra de los items de coibro
        $type_item = DB::table('type_item')
            ->select('type_item.*')
            ->get();

        return response()->json(['status' => 'ok', 'type_item' => $type_item], 200);
    }

    public function clasificacion_item(Request $request)
    {

        $company  = (Int) $request->input("company");
        $contract = (Int) $request->input("contract");
        //tipo de obra de los items de coibro
        $clasificacion_item = DB::table('clasificacion_item')
            ->where('clasificacion_company', '=', $company)
            ->where('id_contract', '=', $contract)
            ->select('clasificacion_item.*')
            ->get();

        return response()->json(['status' => 'ok', 'clasificacion_item' => $clasificacion_item], 200);
    }

    public function tipo_medidor()
    {

        //tipo de obra de los items de coibro
        $tipo_medidor = DB::table('tipo_medidor')
            ->select('tipo_medidor.*')
            ->get();

        return response()->json(['status' => 'ok', 'tipo_medidor' => $tipo_medidor], 200);
    }

    public function state_activity()
    {

        //tipo de obra de los items de coibro
        $search = DB::table('state_activity')
            ->select('state_activity.*')
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

}
