<?php

namespace App\Http\Controllers\NewControllers\odi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OdiController extends Controller
{
    //

    public function create(Request $request)
    {
        $data = $request->all();

        $idodi          = $request->input("idodi");
        $order          = $request->input("order");
        $ot             = $request->input("ot");
        $cod            = $request->input("cod");
        $client         = $request->input("client");
        $identifacation = $request->input("identifacation");
        $address        = $request->input("address");
        $phone          = $request->input("phone");
        $city           = $request->input("city");
        $barrio         = $request->input("barrio");
        $zona           = $request->input("zona");

        $date_assignment      = date('Y-m-d', strtotime($request->input("date_assignment"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_assignment")));
        $date_expiration      = date('Y-m-d', strtotime($request->input("date_expiration"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_expiration")));
        $date_instalation     = date('Y-m-d', strtotime($request->input("date_instalation"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_instalation")));
        $date_expiration_insp = date('Y-m-d', strtotime($request->input("date_expiration_insp"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_expiration_insp")));
        $date_legalization    = date('Y-m-d', strtotime($request->input("date_legalization"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_legalization")));
        $date_Inspection      = date('Y-m-d', strtotime($request->input("date_Inspection"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_Inspection")));
        $date_programming     = date('Y-m-d', strtotime($request->input("date_programming"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_programming")));
        $date_sicerco         = date('Y-m-d', strtotime($request->input("date_sicerco"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_sicerco")));
        $date_distri          = date('Y-m-d', strtotime($request->input("date_distri"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_distri")));
        $date_ps              = date('Y-m-d', strtotime($request->input("date_ps"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_ps")));
        $date_df              = date('Y-m-d', strtotime($request->input("date_df"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_df")));

        $days_ans                    = $request->input("days_ans");
        $expired_days                = $request->input("expired_days");
        $start_time                  = $request->input("start_time");
        $final_hour                  = $request->input("final_hour");
        $programming_code            = $request->input("programming_code");
        $number_visits               = $request->input("number_visits");
        $state                       = $request->input("state");
        $number_acta                 = $request->input("number_acta");
        $serie_cepo                  = $request->input("serie_cepo");
        $type_service_idtype_service = $request->input("type");
        $clasification               = $request->input("clasification");
        $ventilation_r               = $request->input("ventilation_r");
        $ventilation_d               = $request->input("ventilation_d");
        $obsr_close                  = $request->input("obsr_close");
        $obsr_order                  = $request->input("obsr_order");
        $contract_idcontract         = $request->input("contract_idcontract");
        $company_idcompany           = $request->input("company_idcompany");
        $department_iddepartment     = $request->input("department_iddepartment");
        $idsupervisor                = $request->input("idsupervisor");
        $idinspetor                  = $request->input("idinspetor");
        $type_network_idtype_network = $request->input("type_network");
        $Attention                   = $request->input("Attention");
        $priority                    = $request->input("priority");
        $construtor                  = $request->input("construtor");
        $material                    = $request->input("material");
        $type_obr                    = $request->input("type_obr");
        $measurer                    = $request->input("measurer");
        $idclient_account            = $request->input("idclient_account");
        $_user                       = $request->input("_user");

        $insert = DB::table('odi')
            ->insertGetid([
                'order'                       => $order,
                'ot'                          => $ot,
                'cod'                         => $cod,
                'client'                      => $client,
                'identifacation'              => $identifacation,
                'address'                     => $idclient_account,
                'phone'                       => $phone,
                'city'                        => $city,
                'barrio'                      => $barrio,
                'zona'                        => $zona,
                'date_assignment'             => $date_assignment,
                'date_expiration'             => $date_expiration,
                'date_instalation'            => $date_instalation,
                'date_expiration_insp'        => $date_expiration_insp,
                'date_legalization'           => $date_legalization,
                'date_Inspection'             => $date_Inspection,
                'days_ans'                    => $days_ans,
                'expired_days'                => $expired_days,
                'start_time'                  => $start_time,
                'final_hour'                  => $final_hour,
                'programming_code'            => $programming_code,
                'number_visits'               => $number_visits,
                'state'                       => $state,
                'number_acta'                 => $number_acta,
                'serie_cepo'                  => $serie_cepo,
                'type_service_idtype_service' => $type_service_idtype_service,
                'clasification'               => $clasification,
                'ventilation_r'               => $ventilation_r,
                'ventilation_d'               => $ventilation_d,
                'obsr_close'                  => $obsr_close,
                'contract_idcontract'         => 1,
                'company_idcompany'           => 7,
                'department_iddepartment'     => $department_iddepartment,
                'idsupervisor'                => $idsupervisor,
                'idinspetor'                  => $idinspetor,
                'obsr_order'                  => $obsr_order,
                'type_network_idtype_network' => $type_network_idtype_network,
                'Attention'                   => $Attention,
                'date_programming'            => $date_programming,
                'construtor'                  => $construtor,
                'material'                    => $material,
                'service_type_idservice_type' => $type_obr,
                'date_sicerco'                => $date_sicerco,
                'date_distri'                 => $date_distri,
                'measurer'                    => $measurer,
                'date_ps'                     => $date_ps,
                'date_df'                     => $date_df,
                'priority'                    => $priority,
            ]);
        $data = json_encode($data);
        $this->log($_user, $data, $insert, 'Creo Servicio');

        return response()->json(['status' => 'ok', 'response' => true, 'result' => $insert], 200);
    }

    public function log($_user, $data, $odi_idodi, $type)
    {
        $hoy = date("Y-m-d H:i");

        $insert_log = DB::table('log_odi')
            ->insert([
                'date_log'  => $hoy,
                'users'     => $_user,
                'type'      => $type,
                'log'       => $data,
                'odi_idodi' => $odi_idodi,
            ]);

    }

    public function update(Request $request)
    {

        $data           = $request->all();
        $idodi          = $request->input("idodi");
        $order          = $request->input("order");
        $ot             = $request->input("ot");
        $cod            = $request->input("cod");
        $client         = $request->input("client");
        $identifacation = $request->input("identifacation");
        $address        = $request->input("address");
        $phone          = $request->input("phone");
        $city           = $request->input("city");
        $barrio         = $request->input("barrio");
        $zona           = $request->input("zona");

        $date_assignment             = date('Y-m-d', strtotime($request->input("date_assignment"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_assignment")));
        $date_expiration             = date('Y-m-d', strtotime($request->input("date_expiration"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_expiration")));
        $date_instalation            = date('Y-m-d', strtotime($request->input("date_instalation"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_instalation")));
        $date_expiration_insp        = date('Y-m-d', strtotime($request->input("date_expiration_insp"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_expiration_insp")));
        $date_legalization           = date('Y-m-d', strtotime($request->input("date_legalization"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_legalization")));
        $date_Inspection             = date('Y-m-d', strtotime($request->input("date_Inspection"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_Inspection")));
        $date_programming            = date('Y-m-d', strtotime($request->input("date_programming"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_programming")));
        $date_sicerco                = date('Y-m-d', strtotime($request->input("date_sicerco"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_sicerco")));
        $date_distri                 = date('Y-m-d', strtotime($request->input("date_distri"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_distri")));
        $date_ps                     = date('Y-m-d', strtotime($request->input("date_ps"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_ps")));
        $date_df                     = date('Y-m-d', strtotime($request->input("date_df"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_df")));
        $days_ans                    = $request->input("days_ans");
        $expired_days                = $request->input("expired_days");
        $start_time                  = $request->input("start_time");
        $final_hour                  = $request->input("final_hour");
        $programming_code            = $request->input("programming_code");
        $number_visits               = $request->input("number_visits");
        $state                       = $request->input("state");
        $number_acta                 = $request->input("number_acta");
        $serie_cepo                  = $request->input("serie_cepo");
        $type_service_idtype_service = $request->input("type");
        $clasification               = $request->input("clasification");
        $ventilation_r               = $request->input("ventilation_r");
        $ventilation_d               = $request->input("ventilation_d");
        $obsr_close                  = $request->input("obsr_close");
        $obsr_order                  = $request->input("obsr_order");
        $contract_idcontract         = $request->input("contract_idcontract");
        $company_idcompany           = $request->input("company_idcompany");
        $department_iddepartment     = $request->input("department_iddepartment");
        $idsupervisor                = $request->input("idsupervisor");
        $idinspetor                  = $request->input("idinspetor");

        $type_network_idtype_network = $request->input("type_network");
        $Attention                   = $request->input("Attention");
        $priority                    = $request->input("priority");

        $construtor       = $request->input("idconstrutor");
        $material         = $request->input("idmaterial");
        $type_obr         = $request->input("type_obr");
        $measurer         = $request->input("measurer");
        $idclient_account = $request->input("idclient_account");
        $_user            = $request->input("_user");
        $update           = DB::table('odi')
            ->where('idodi', $idodi)
            ->update([
                'order'                       => $order,
                'ot'                          => $ot,
                'cod'                         => $cod,
                'client'                      => $client,
                'identifacation'              => $identifacation,
                'address'                     => $idclient_account,
                'phone'                       => $phone,
                'city'                        => $city,
                'barrio'                      => $barrio,
                'zona'                        => $zona,
                'date_assignment'             => $date_assignment,
                'date_expiration'             => $date_expiration,
                'date_instalation'            => $date_instalation,
                'date_expiration_insp'        => $date_expiration_insp,
                'date_legalization'           => $date_legalization,
                'date_Inspection'             => $date_Inspection,
                'days_ans'                    => $days_ans,
                'expired_days'                => $expired_days,
                'start_time'                  => $start_time,
                'final_hour'                  => $final_hour,
                'programming_code'            => $programming_code,
                'number_visits'               => $number_visits,
                'state'                       => $state,
                'number_acta'                 => $number_acta,
                'serie_cepo'                  => $serie_cepo,
                'type_service_idtype_service' => $type_service_idtype_service,
                'clasification'               => $clasification,
                'ventilation_r'               => $ventilation_r,
                'ventilation_d'               => $ventilation_d,
                'obsr_close'                  => $obsr_close,
                'obsr_order'                  => $obsr_order,
                'contract_idcontract'         => 1,
                'company_idcompany'           => 7,
                'department_iddepartment'     => $department_iddepartment,
                'idsupervisor'                => $idsupervisor,
                'idinspetor'                  => $idinspetor,
                'priority'                    => $priority,
                'Attention'                   => $Attention,
                'type_network_idtype_network' => $type_network_idtype_network,
                'date_programming'            => $date_programming,
                'construtor'                  => $construtor,
                'material'                    => $material,
                'service_type_idservice_type' => $type_obr,
                'date_sicerco'                => $date_sicerco,
                'date_distri'                 => $date_distri,
                'measurer'                    => $measurer,
                'date_ps'                     => $date_ps,
                'date_df'                     => $date_df,

            ]);

        $data = json_encode($data);
        if ($update) {
            $this->log($_user, $data, $idodi, 'Actualizo Servicio');
        }

        return response()->json(['status' => 'ok', 'response' => $update], 200);
    }

    public function autocomplete(Request $request)
    {
        $odi = $request->input("odi");
        $id  = $request->input("id");

        switch ($id) {
            case (1):
                $search = DB::table('client')
                    ->join('odi', 'odi.client', '=', 'client.idclient')
                    ->where('client.name_client', 'like', '%' . $odi . '%')
                    ->select('odi.*', 'client.name_client', 'client.name_client as data', DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=odi.idsupervisor) AS supervisor"),
                        DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=odi.idinspetor) AS inspector"),
                        DB::raw("(SELECT  name_materials FROM materials where materials.idmaterials=odi.material) AS name_material"),
                        DB::raw("(SELECT  name_builder FROM builder where builder.idbuilder=odi.construtor) AS name_construtor"))
                    ->take(10)
                    ->get();
                break;

            case (2):
                $search = DB::table('odi')
                    ->where('identifacation', 'like', '%' . $odi . '%')
                //->leftjoin('','','=','')
                    ->select('odi.*', 'odi.identifacation as data', DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=odi.idsupervisor) AS supervisor"),
                        DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=odi.idinspetor) AS inspector"),
                        DB::raw("(SELECT  name_materials FROM materials where materials.idmaterials=odi.material) AS name_material"),
                        DB::raw("(SELECT  name_builder FROM builder where builder.idbuilder=odi.construtor) AS name_construtor"))
                    ->take(10)
                    ->get();
                break;
            case (3):
                $search = DB::table('odi')
                    ->where('address', 'like', '%' . $odi . '%')
                //->leftjoin('','','=','')
                    ->select('odi.*', 'odi.address as data', DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=odi.idsupervisor) AS supervisor"),
                        DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=odi.idinspetor) AS inspector"),
                        DB::raw("(SELECT  name_materials FROM materials where materials.idmaterials=odi.material) AS name_material"),
                        DB::raw("(SELECT  name_builder FROM builder where builder.idbuilder=odi.construtor) AS name_construtor"))
                    ->take(10)
                    ->get();
                break;
            case (4):
                $search = DB::table('odi')
                    ->where('number_acta', 'like', '%' . $odi . '%')
                //->leftjoin('','','=','')
                    ->select('odi.*', 'odi.number_acta as data', DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=odi.idsupervisor) AS supervisor"),
                        DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=odi.idinspetor) AS inspector"),
                        DB::raw("(SELECT  name_materials FROM materials where materials.idmaterials=odi.material) AS name_material"),
                        DB::raw("(SELECT  name_builder FROM builder where builder.idbuilder=odi.construtor) AS name_construtor"))
                    ->take(10)
                    ->get();
                break;

            case (5):
                $search = DB::table('odi')
                    ->where('idodi', 'like', '%' . $odi . '%')
                //->leftjoin('','','=','')
                    ->select('odi.*', 'odi.idodi as data', DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=odi.idsupervisor) AS supervisor"),
                        DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=odi.idinspetor) AS inspector"),
                        DB::raw("(SELECT  name_materials FROM materials where materials.idmaterials=odi.material) AS name_material"),
                        DB::raw("(SELECT  name_builder FROM builder where builder.idbuilder=odi.construtor) AS name_construtor"))
                    ->take(10)
                    ->get();
                break;
        }

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function photoid(Request $request)
    {
        $idphoto = $request->input("idphoto");

        $search = DB::table('photos')
            ->where('idphotos', $idphoto)
            ->first();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function defectos(Request $request)
    {
        $id_defectos = $request->input("id_defectos");
        $type        = $request->input("type");
        $idodi       = $request->input("idodi");
        $state       = $request->input("state");
        $typename    = $request->input("typename");

        if ($state == true) {

            $insert = DB::table('default')
                ->insert([
                    'id_defectos' => $id_defectos,
                    'type'        => $type,
                    'idodi'       => $idodi,
                    'state'       => $state,
                    'typename'    => $typename,
                ]);
        } else {
            $delete = DB::table('default')
                ->where('idodi', $idodi)
                ->where('type', $type)
                ->where('id_defectos', $id_defectos)
                ->delete();

        }

    }

    public function search_defectos(Request $request)
    {
        $idodi = $request->input("idodi");
        $type  = $request->input("type");

        $search = DB::table('default')
            ->where('idodi', $idodi)
            ->where('type', $type)
            ->where('type', $type)
            ->get();

        $test = $this->search_test($idodi);
        return response()->json(['status' => 'ok', 'response' => $search], 200);

    }

    public function correcion_defectos(Request $request)
    {
        $idodi     = $request->input("idodi");
        $correcion = $request->input("correcion");
        $value     = $request->input("value");
        $valor     = $request->input("valor");

        if ($value == true) {
            $insert = DB::table('correction')
                ->insert([

                    'idodi'     => $idodi,
                    'state'     => $value,
                    'type'      => $valor,
                    'type_name' => $correcion,
                ]);
        } else {
            $delete = DB::table('correction')
                ->where('idodi', $idodi)
                ->where('type_name', $correcion)
                ->delete();
        }

    }

    public function search_correcion_defectos(Request $request)
    {
        $idodi = $request->input("idodi");

        $search = DB::table('correction')
            ->where('idodi', $idodi)
            ->get();
        $test = $this->search_test($idodi);

        $search_default = $this->search_default($idodi);
        return response()->json(['status' => 'ok', 'response' => $search, 'test' => $test, 'search_default' => $search_default], 200);
    }

    public function save_test(Request $request)
    {
        $idtest             = $request->input("idtest");
        $idstate_default    = $request->input("idstate_default");
        $idodi              = $request->input("idodi");
        $flow_meter_initial = $request->input("flow_meter_initial");
        $flow_meter_final   = $request->input("flow_meter_final");
        $flow_meter_essay   = $request->input("flow_meter_essay");
        $Pressure_initial   = $request->input("Pressure_initial");
        $Pressure_final     = $request->input("Pressure_final");
        $Pressure_essay     = $request->input("Pressure_essay");
        $he                 = $request->input("he");
        $ex                 = $request->input("ex");
        $tr                 = $request->input("tr");
        $ev                 = $request->input("ev");
        $co                 = $request->input("co");
        $me                 = $request->input("me");
        $ub                 = $request->input("ub");

        if ($idstate_default == null) {
            $this->insert_default($idodi, $he, $ex, $tr, $ev, $co, $me, $ub);
        } else {
            $this->update_default($idodi, $he, $ex, $tr, $ev, $co, $me, $ub, $idstate_default);
        }

        if ($idtest) {
            $insert = DB::table('test_service')
                ->insert([
                    'odi_idodi'          => $idodi,
                    'flow_meter_initial' => $flow_meter_initial,
                    'flow_meter_final'   => $flow_meter_final,
                    'flow_meter_essay'   => $flow_meter_essay,
                    'Pressure_initial'   => $Pressure_initial,
                    'Pressure_final'     => $Pressure_final,
                    'Pressure_essay'     => $Pressure_essay,
                ]);

            return response()->json(['status' => 'ok', 'response' => true], 200);
        } else {
            $insert = DB::table('test_service')
                ->where('idtest_service', $idtest)
                ->update([
                    'flow_meter_initial' => $flow_meter_initial,
                    'flow_meter_final'   => $flow_meter_final,
                    'flow_meter_essay'   => $flow_meter_essay,
                    'Pressure_initial'   => $Pressure_initial,
                    'Pressure_final'     => $Pressure_final,
                    'Pressure_essay'     => $Pressure_essay,
                ]);

            return response()->json(['status' => 'ok', 'response' => false], 200);
        }

    }

    public function insert_default($idodi, $he, $ex, $tr, $ev, $co, $me, $ub)
    {
        $insert = DB::table('state_default')
            ->insert([
                'odi_idodi' => $idodi,
                'he'        => $he,
                'ex'        => $ex,
                'tr'        => $tr,
                'ev'        => $ev,
                'co'        => $co,
                'me'        => $me,
                'ub'        => $ub,
            ]);

    }

    public function update_default($idodi, $he, $ex, $tr, $ev, $co, $me, $ub, $idstate_default)
    {
        $insert = DB::table('state_default')
            ->where('idstate_default', $idstate_default)
            ->update([
                'he' => $he,
                'ex' => $ex,
                'tr' => $tr,
                'ev' => $ev,
                'co' => $co,
                'me' => $me,
                'ub' => $ub,
            ]);

    }

    public function search_test($idodi)
    {
        $search = DB::table('test_service')
            ->where('odi_idodi', $idodi)
            ->first();

        return $search;
    }

    public function search_default($idodi)
    {
        $search = DB::table('state_default')
            ->where('odi_idodi', $idodi)
            ->first();

        return $search;
    }

    public function search(Request $request)
    {
        $client_idclient  = $request->input('client_idclient');
        $idclient_account = $request->input('idclient_account');

        $search = DB::table('client')
            ->join('client_account', 'client_account.client_idclient', '=', 'client.idclient')
            ->where('idclient_account', $idclient_account)
            ->first();

        $search_muni = DB::table('municipality')
            ->where('idmunicipality', $search->city)
            ->first();

        return response()->json(['status' => 'ok', 'response' => $search, 'id_departament' => $search_muni->id_departament], 200);
    }

    public function certficate_create(Request $request)
    {
        $idservice_certifications = $request->input('idservice_certifications');
        $state                    = $request->input('state');
        $id_user                  = $request->input('id_user');
        $number                   = $request->input('number');
        $odi_idodi                = $request->input('odi_idodi');
        $obssuper                 = $request->input('obssuper');
        $obsins                   = $request->input('obsins');
        $obsclient                = $request->input('obsclient');
        $idcounter_certificate    = $request->input('idcounter_certificate');

        if ($idservice_certifications == null) {
            $insert = DB::table('service_certifications')
                ->insert([
                    'state'     => $state,
                    'id_user'   => $id_user,
                    'number'    => $number,
                    'odi_idodi' => $odi_idodi,
                    'obssuper'  => $obssuper,
                    'obsins'    => $obsins,
                    'obsclient' => $obsclient,
                    'id_number' => $idcounter_certificate,

                ]);

            $update = DB::table('counter_certificate')
                ->where('idcounter_certificate', $idcounter_certificate)
                ->update([
                    'state' => 2,
                ]);
            return response()->json(['status' => 'ok', 'response' => true], 200);

        } else {
            $insert = DB::table('service_certifications')
                ->where('idservice_certifications', $idservice_certifications)
                ->update([
                    'state'     => $state,
                    'obssuper'  => $obssuper,
                    'obsins'    => $obsins,
                    'obsclient' => $obsclient,

                ]);
            return response()->json(['status' => 'ok', 'response' => false], 200);
        }
    }

    public function certficate_search(Request $request)
    {
        $odi = $request->input('odi');

        $serach = DB::table('service_certifications')
            ->where('odi_idodi', $odi)
            ->select('service_certifications.*', 'service_certifications.state as idstate',
                DB::raw("(SELECT CONCAT(name,' ',last_name) FROM users where users.idusers=service_certifications.id_user) AS user"),
                DB::raw('(CASE WHEN service_certifications.state = "1" THEN "Activo"
                WHEN service_certifications.state = "2" THEN "Realizado"
                WHEN service_certifications.state = "3" THEN "Aprobado"
                WHEN service_certifications.state = "4" THEN "Rechazado"
                WHEN service_certifications.state = "5" THEN "Declinado"
                WHEN service_certifications.state = "6" THEN "Cancelado"
                ELSE "Por Suspendido" END) AS state'))
            ->get();

        return response()->json(['status' => 'ok', 'response' => $serach], 200);
    }

    public function certficate_delete(Request $request)
    {
        $idservice_certifications = $request->input('idservice_certifications');

        $search = DB::table('image')
            ->where('service_certifications_idservice_certifications', $idservice_certifications)
            ->get();

        if (count($search) > 0) {
            return response()->json(['status' => 'ok', 'response' => false], 200);
        } else {

            $delete = DB::table('service_certifications')
                ->where('idservice_certifications', $idservice_certifications)
                ->delete();

            return response()->json(['status' => 'ok', 'response' => true], 200);
        }
    }

}
