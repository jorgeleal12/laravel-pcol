<?php

namespace App\Http\Controllers\NewControllers\appmovil;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppMovilController extends Controller
{
    //

    public function login(Request $request)
    {
        $user = $request->input("user");
        $pass = $request->input("pass");

        $search_employee = DB::table('employees')
            ->where('identification', $user)
            ->first();

        $search = DB::table('users')
            ->where('password', $pass)
            ->where('id', $user)
            ->select('users.name', 'users.last_name', 'users.idusers', 'users.email', 'users.id', 'users.type')
            ->first();

        if (!$search) {

            return response()->json(['status' => 'ok', 'response' => false], 200);
        } else {
            return response()->json(['status' => 'ok', 'response' => true, 'data' => $search, 'idemployees' => $search_employee->idemployees], 200);
        }

    }

    public function totalasignadas(Request $request)
    {
        $user = $request->input("user");
        $type = $request->input("type");

        if ($type == 1) {
            $search_asi = DB::table('odi')
                ->join('employees', 'employees.idemployees', '=', 'odi.idinspetor')
                ->where('employees.identification', $user)
                ->where('odi.state', 2)
                ->groupBy('odi.idinspetor')
                ->select('odi.idinspetor', DB::raw('count(odi.idodi) as total'))
                ->first();

            $search_rech = DB::table('service_certifications')
                ->join('odi', 'odi.idodi', '=', 'service_certifications.odi_idodi')
                ->join('employees', 'employees.idemployees', '=', 'odi.idinspetor')
                ->where('employees.identification', $user)
                ->where('service_certifications.state', 4)
                ->groupBy('odi.idinspetor')
                ->select('odi.idinspetor', DB::raw('count(odi.idodi) as total'))
                ->first();

            $search_etn = DB::table('odi')
                ->join('employees', 'employees.idemployees', '=', 'odi.idinspetor')
                ->where('employees.identification', $user)
                ->where('odi.state', 3)
                ->groupBy('odi.idinspetor')
                ->select('odi.idinspetor', DB::raw('count(odi.idodi) as total'))
                ->first();

        } else {
            $search_asi = DB::table('odi')
                ->join('employees', 'employees.idemployees', '=', 'odi.idsupervisor')
                ->where('employees.identification', $user)
                ->where('odi.state', 2)
                ->groupBy('odi.idsupervisor')
                ->select('odi.idsupervisor', DB::raw('count(odi.idodi) as total'))
                ->first();

            $search_rech = DB::table('service_certifications')
                ->join('odi', 'odi.idodi', '=', 'service_certifications.odi_idodi')
                ->join('employees', 'employees.idemployees', '=', 'odi.idsupervisor')
                ->where('employees.identification', $user)
                ->where('service_certifications.state', 4)
                ->groupBy('odi.idsupervisor')
                ->select('odi.idsupervisor', DB::raw('count(odi.idodi) as total'))
                ->first();

            $search_etn = DB::table('odi')
                ->join('employees', 'employees.idemployees', '=', 'odi.idsupervisor')
                ->where('employees.identification', $user)
                ->where('odi.state', 3)
                ->groupBy('odi.idsupervisor')
                ->select('odi.idsupervisor', DB::raw('count(odi.idodi) as total'))
                ->first();

        }

        return response()->json(['status' => 'ok', 'response' => true, 'data' => $search_asi, 'search_rech' => $search_rech, 'search_etn' => $search_etn], 200);
    }

    public function seach_asignadas(Request $request)
    {
        $user = $request->input("user");
        $type = $request->input("type");
        $id   = $request->input("id");
        $state;
        $search_emplye = DB::table('employees')
            ->where('identification', $user)
            ->first();

        switch ($id) {
            case (1):
                $search = DB::table('odi')
                    ->leftjoin('employees', 'employees.idemployees', '=', 'odi.idinspetor')
                    ->leftjoin('type_service', 'type_service.idtype_service', '=', 'odi.type_service_idtype_service')
                    ->leftjoin('type_network', 'type_network.idtype_network', '=', 'odi.type_network_idtype_network')
                    ->leftjoin('contract', 'contract.idcontract', '=', 'odi.contract_idcontract')
                    ->leftjoin('state', 'state.id_state', '=', 'odi.state')
                    ->leftjoin('client', 'client.idclient', '=', 'odi.client')
                    ->where('odi.idsupervisor', $search_emplye->idemployees)
                    ->where('odi.state', 2)
                    ->select('odi.*', 'state.name_state',
                        'client.name_client',
                        'client.phone as phone',
                        'employees.name',
                        'employees.last_name',
                        'employees.idemployees',
                        'employees.identification',
                        'type_service.name_type',
                        'type_network.name_network',
                        'contract.contract_name',
                        DB::raw('(CASE WHEN odi.priority = "1" THEN "Alta"
WHEN odi.priority = "2" THEN "Media"
ELSE "Baja" END) AS name_priority'),
                        DB::raw('(CASE WHEN odi.type_gas = "1" THEN "Natural"
ELSE "GLP" END) AS name_gas'),
                        DB::raw('(CASE WHEN odi.Attention = "1" THEN "Mañana"
ELSE "Tarde" END) AS name_atencion'),
                        DB::raw("(SELECT  name_materials FROM materials where materials.idmaterials=odi.material) AS name_material"),
                        DB::raw("(SELECT  name_builder FROM builder where builder.idbuilder=odi.construtor) AS name_construtor"))

                    ->get();
                return response()->json(['status' => 'ok', 'response' => true, 'data' => $search], 200);
                break;
            case (2):
                $search = DB::table('odi')
                    ->leftjoin('employees', 'employees.idemployees', '=', 'odi.idinspetor')
                    ->leftjoin('type_service', 'type_service.idtype_service', '=', 'odi.type_service_idtype_service')
                    ->leftjoin('type_network', 'type_network.idtype_network', '=', 'odi.type_network_idtype_network')
                    ->leftjoin('contract', 'contract.idcontract', '=', 'odi.contract_idcontract')
                    ->leftjoin('state', 'state.id_state', '=', 'odi.state')
                    ->leftjoin('client', 'client.idclient', '=', 'odi.client')
                    ->where('odi.idsupervisor', $search_emplye->idemployees)
                    ->where('odi.state', 3)
                    ->select('odi.*', 'state.name_state',
                        'client.name_client',
                        'client.phone as phone',
                        'employees.name',
                        'employees.last_name',
                        'employees.idemployees',
                        'employees.identification',
                        'type_service.name_type',
                        'type_network.name_network',
                        'contract.contract_name',
                        DB::raw('(CASE WHEN odi.priority = "1" THEN "Alta"
WHEN odi.priority = "2" THEN "Media"
ELSE "Baja" END) AS name_priority'),
                        DB::raw('(CASE WHEN odi.type_gas = "1" THEN "Natural"
ELSE "GLP" END) AS name_gas'),
                        DB::raw('(CASE WHEN odi.Attention = "1" THEN "Mañana"
ELSE "Tarde" END) AS name_atencion'),
                        DB::raw("(SELECT  name_materials FROM materials where materials.idmaterials=odi.material) AS name_material"),
                        DB::raw("(SELECT  name_builder FROM builder where builder.idbuilder=odi.construtor) AS name_construtor"))

                    ->get();
                return response()->json(['status' => 'ok', 'response' => true, 'data' => $search], 200);

                break;
            case (3):
                $search = DB::table('service_certifications')
                    ->join('odi', 'odi.idodi', '=', 'service_certifications.odi_idodi')
                    ->leftjoin('employees', 'employees.idemployees', '=', 'odi.idinspetor')
                    ->leftjoin('type_service', 'type_service.idtype_service', '=', 'odi.type_service_idtype_service')
                    ->leftjoin('type_network', 'type_network.idtype_network', '=', 'odi.type_network_idtype_network')
                    ->leftjoin('contract', 'contract.idcontract', '=', 'odi.contract_idcontract')
                    ->leftjoin('state', 'state.id_state', '=', 'odi.state')
                    ->leftjoin('client', 'client.idclient', '=', 'odi.client')
                    ->where('odi.idsupervisor', $search_emplye->idemployees)
                    ->where('service_certifications.state', 3)
                    ->select('odi.*', 'state.name_state',
                        'client.name_client',
                        'client.phone as phone',
                        'employees.name',
                        'employees.last_name',
                        'employees.idemployees',
                        'employees.identification',
                        'type_service.name_type',
                        'type_network.name_network',
                        'contract.contract_name',
                        DB::raw('(CASE WHEN odi.priority = "1" THEN "Alta"
WHEN odi.priority = "2" THEN "Media"
ELSE "Baja" END) AS name_priority'),
                        DB::raw('(CASE WHEN odi.type_gas = "1" THEN "Natural"
ELSE "GLP" END) AS name_gas'),
                        DB::raw('(CASE WHEN odi.Attention = "1" THEN "Mañana"
ELSE "Tarde" END) AS name_atencion'),
                        DB::raw("(SELECT  name_materials FROM materials where materials.idmaterials=odi.material) AS name_material"),
                        DB::raw("(SELECT  name_builder FROM builder where builder.idbuilder=odi.construtor) AS name_construtor"))

                    ->get();
                return response()->json(['status' => 'ok', 'response' => true, 'data' => $search], 200);
                break;
            case (4):
                $search = DB::table('service_certifications')
                    ->join('odi', 'odi.idodi', '=', 'service_certifications.odi_idodi')
                    ->leftjoin('employees', 'employees.idemployees', '=', 'odi.idinspetor')
                    ->leftjoin('type_service', 'type_service.idtype_service', '=', 'odi.type_service_idtype_service')
                    ->leftjoin('type_network', 'type_network.idtype_network', '=', 'odi.type_network_idtype_network')
                    ->leftjoin('contract', 'contract.idcontract', '=', 'odi.contract_idcontract')
                    ->leftjoin('state', 'state.id_state', '=', 'odi.state')
                    ->leftjoin('client', 'client.idclient', '=', 'odi.client')
                    ->where('odi.idsupervisor', $search_emplye->idemployees)
                    ->where('service_certifications.state', 3)
                    ->select('odi.*', 'state.name_state',
                        'client.name_client',
                        'client.phone as phone',
                        'employees.name',
                        'employees.last_name',
                        'employees.idemployees',
                        'employees.identification',
                        'type_service.name_type',
                        'type_network.name_network',
                        'contract.contract_name',
                        DB::raw('(CASE WHEN odi.priority = "1" THEN "Alta"
WHEN odi.priority = "2" THEN "Media"
ELSE "Baja" END) AS name_priority'),
                        DB::raw('(CASE WHEN odi.type_gas = "1" THEN "Natural"
ELSE "GLP" END) AS name_gas'),
                        DB::raw('(CASE WHEN odi.Attention = "1" THEN "Mañana"
ELSE "Tarde" END) AS name_atencion'),
                        DB::raw("(SELECT  name_materials FROM materials where materials.idmaterials=odi.material) AS name_material"),
                        DB::raw("(SELECT  name_builder FROM builder where builder.idbuilder=odi.construtor) AS name_construtor"))

                    ->get();
                return response()->json(['status' => 'ok', 'response' => true, 'data' => $search], 200);
        }
    }

    public function photos_service(Request $request)
    {
        $type_network = $request->input("type_network");

        $search = DB::table('photos_service')
            ->join('photos', 'photos.idphotos', 'photos_service.photos_idphotos')
            ->where('type_network_idtype_network', $type_network)
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);

    }

    public function registerToken(Request $request)
    {
        $token  = $request->input("token");
        $iduser = $request->input("iduser");

        $update = DB::table('users')
            ->where('idusers', $iduser)
            ->update([
                'token' => $token,
            ]);
    }

    public function search_materials(Request $request)
    {

        $search = DB::table('materials')
            ->select('materials.*', DB::raw('(CASE WHEN materials.state = "1" THEN "Activo"
        WHEN materials.state = "2" THEN "Inactivo"
        ELSE "Por confirmar" END) AS name_state'))
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function search_builder()
    {

        $search = DB::table('builder')
            ->select('builder.*', DB::raw('(CASE WHEN builder.state = "1" THEN "Activo"
        WHEN builder.state = "2" THEN "Inactivo"
        ELSE "Por confirmar" END) AS name_state'))
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);

    }

    public function search_certificate(Request $request)
    {
        $idodi = $request->input("idodi");

        $search = DB::table('service_certifications')
            ->where('odi_idodi', $idodi)
            ->select('service_certifications.*',
                DB::raw('(CASE WHEN service_certifications.state = "1" THEN "Activo"
            WHEN service_certifications.state = "2" THEN "Atendido"
            WHEN service_certifications.state = "3" THEN "Aprobado"
            WHEN service_certifications.state = "4" THEN "Rechazado"
            WHEN service_certifications.state = "5" THEN "Declinado"
            WHEN service_certifications.state = "6" THEN "Cancelado"
            ELSE "Por Suspendido" END) AS name_state'))
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function number_certificate(Request $request)
    {
        $idusers = $request->input("idusers");

        $search = DB::table('employees')
            ->where('identification', $idusers)
            ->first();

        $search_cert = DB::table('Number_cetificate')
            ->leftjoin('counter_certificate', 'counter_certificate.Number_cetificate_idNumber_cetificate', '=', 'Number_cetificate.idNumber_cetificate')
            ->where('idemployees', $search->idemployees)
            ->where('state', 1)
            ->take(1)
            ->first();
        return response()->json(['status' => 'ok', 'response' => $search_cert], 200);
    }

    public function save_certificate(Request $request)
    {
        $number                                = $request->input("number");
        $idservice_certifications              = $request->input("idservice_certifications");
        $odi_idodi                             = $request->input("odi_idodi");
        $idemployees                           = $request->input("id_user");
        $Number_cetificate_idNumber_cetificate = $request->input("Number_cetificate_idNumber_cetificate");
        $obssuper                              = $request->input("obssuper");
        $obsins                                = $request->input("obsins");
        $obsclient                             = $request->input("obsclient");
        $hoy                                   = date("Y_m_d_H_i_s");

        if ($idservice_certifications) {
            $update = DB::table('service_certifications')
                ->where('idservice_certifications', $idservice_certifications)
                ->update([
                    'obssuper'  => $obssuper,
                    'obsins'    => $obsins,
                    'obsclient' => $obsclient,
                ]);
            return response()->json(['status' => 'ok', 'response' => false], 200);
        } else {
            $select = DB::table('Number_cetificate')
                ->leftjoin('counter_certificate', 'counter_certificate.Number_cetificate_idNumber_cetificate', '=', 'Number_cetificate.idNumber_cetificate')
                ->where('number_', $number)
                ->where('idemployees', $idemployees)
                ->where('Number_cetificate_idNumber_cetificate', $Number_cetificate_idNumber_cetificate)
                ->first();

            $insert = DB::table('service_certifications')
                ->insertGetid([
                    'state'     => 1,
                    'number'    => $number,
                    'odi_idodi' => $odi_idodi,
                    'obssuper'  => $obssuper,
                    'obsins'    => $obsins,
                    'obsclient' => $obsclient,
                    'id_user'   => $idemployees,
                    'id_number' => $select->idcounter_certificate,
                    'date'      => $hoy,

                ]);

            $update_counter = DB::table('counter_certificate')
                ->where('number_', $number)
                ->where('Number_cetificate_idNumber_cetificate', $Number_cetificate_idNumber_cetificate)
                ->update([
                    'state' => 2,
                ]);

            return response()->json(['status' => 'ok', 'response' => true, 'result' => $insert], 200);
        }

    }

    public function ViewImage(Request $request)
    {
        $idservice = $request->input("idservice");
        $id_odi    = $request->input("id_odi");

        $search = DB::table('image')
            ->where('odi_idodi', $id_odi)
            ->where('service_certifications_idservice_certifications', $idservice)
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function SaveService(Request $request)
    {

        $name_priority               = $request->input("name_priority");
        $date_programming            = date('Y-m-d', strtotime($request->input("date_programming"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_programming")));
        $Attention                   = $request->input("Attention");
        $name_gas                    = $request->input("name_gas");
        $name_type                   = $request->input("name_type");
        $name_network                = $request->input("name_network");
        $contract_name               = $request->input("contract_name");
        $client                      = $request->input("client");
        $identifacation              = $request->input("identifacation");
        $phone                       = $request->input("phone");
        $type_service_idtype_service = $request->input("type_service_idtype_service");
        $type_network_idtype_network = $request->input("type_network_idtype_network");
        $address                     = $request->input("address");
        $name_state                  = $request->input("name_state");
        $state                       = $request->input("state");
        $name_material               = $request->input("name_material");
        $name_construtor             = $request->input("name_construtor");
        $priority                    = $request->input("priority");
        $material                    = $request->input("material");
        $construtor                  = $request->input("construtor");
        $idodi                       = $request->input("idodi");
        $city                        = $request->input("city");
        $service_type_idservice_type = $request->input("service_type_idservice_type");
        $user                        = $request->input("user");
        $user_type                   = $request->input("user_type");
        $type_gas                    = $request->input("type_gas");

        if ($user_type == 2) {
            $employee = 'idinspetor';
        } else {
            $employee = 'idsupervisor';
        }

        $search_city = DB::table('municipality')
            ->where('idmunicipality', $city)
            ->first();

        if ($idodi) {

            $update = DB::table('odi')
                ->where('idodi', $idodi)
                ->update([
                    'material'   => $material,
                    'construtor' => $construtor,
                    // 'state'      => 3,
                    'type_gas'   => $type_gas,

                ]);
            return response()->json(['status' => 'ok', 'response' => false], 200);

        } else {
            $hoy    = date("Y-m-d");
            $insert = DB::table('odi')
                ->insert([
                    'priority'                    => $priority,
                    'date_programming'            => $date_programming,
                    'Attention'                   => $Attention,
                    'type_service_idtype_service' => $type_service_idtype_service,
                    'type_network_idtype_network' => $type_network_idtype_network,
                    'contract_idcontract'         => 1,
                    'company_idcompany'           => 7,
                    'client'                      => $client,
                    'address'                     => $address,
                    'identifacation'              => $identifacation,
                    'date_assignment'             => $hoy,
                    'city'                        => $city,
                    'department_iddepartment'     => $search_city->id_departament,
                    'service_type_idservice_type' => $service_type_idservice_type,
                    'material'                    => $material,
                    'construtor'                  => $construtor,
                    'state'                       => 2,
                    'type_gas'                    => $type_gas,
                    $employee                     => $user,
                ]);

            return response()->json(['status' => 'ok', 'response' => true], 200);
        }

    }

    public function SearchClient()
    {
        $search = DB::table('client')
            ->orderBy('idclient', 'asc')
            ->select('client.*', 'client.state as idstate', DB::raw('(CASE WHEN client.state = "1" THEN "Activo" WHEN client.state = "2" THEN "Inactivo" ELSE "Por confirmar" END) AS state'))
            ->paginate(15);

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function AutoListClient(Request $request)
    {
        $client = $request->input("client");

        $search = DB::table('client')
            ->where('name_client', 'like', '%' . $client . '%')
            ->orderBy('idclient', 'asc')
            ->select('client.*', 'client.state as idstate', DB::raw('(CASE WHEN client.state = "1" THEN "Activo" WHEN client.state = "2" THEN "Inactivo" ELSE "Por confirmar" END) AS state'))
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);

    }

    public function ListAcount(Request $request)
    {
        $idclient = $request->input("idclient");

        $search = DB::table('client_account')
            ->leftjoin('municipality', 'client_account.city', 'municipality.idmunicipality')
            ->where('client_idclient', $idclient)
            ->select('client_account.*', 'municipality.name_municipality as name_city', 'client_account.state as idstate', DB::raw('(CASE WHEN client_account.state = "1" THEN "Activo" WHEN client_account.state = "2" THEN "Inactivo" ELSE "Por confirmar" END) AS state'))
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function ListCity()
    {
        $search = DB::table('municipality')
            ->leftjoin('departments', 'departments.departments_dane', 'municipality.id_departament')
            ->paginate(30);
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function AutoCity(Request $request)
    {
        $city = $request->input("city");

        $search = DB::table('municipality')
            ->where('municipality.name_municipality', 'like', '%' . $city . '%')
            ->leftjoin('departments', 'departments.departments_dane', 'municipality.id_departament')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function ListMaterial()
    {
        $search = DB::table('materials')
            ->select('materials.*', 'materials.state as idstate', DB::raw('(CASE WHEN materials.state = "1" THEN "Activo" WHEN materials.state = "2" THEN "Inactivo" ELSE "Por confirmar" END) AS state'))
            ->paginate(30);
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function AutoListMaterial(Request $request)
    {
        $material = $request->input("material");

        $search = DB::table('materials')
            ->where('materials.name_materials', 'like', '%' . $material . '%')
            ->select('materials.*', 'materials.state as idstate', DB::raw('(CASE WHEN materials.state = "1" THEN "Activo" WHEN materials.state = "2" THEN "Inactivo" ELSE "Por confirmar" END) AS state'))
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function MaterialCertificate(Request $request)
    {
        $material = $request->input("material");

        $search = DB::table('material_certificate')
            ->where('materials_idmaterials', $material)
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }
    public function ListBuilder()
    {
        $search = DB::table('builder')
            ->select('builder.*', 'builder.state as idstate', DB::raw('(CASE WHEN builder.state = "1" THEN "Activo" WHEN builder.state = "2" THEN "Inactivo" ELSE "Por confirmar" END) AS state'))
            ->paginate(30);
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function AutoListBuilder(Request $request)
    {

        $builder = $request->input("builder");
        $search  = DB::table('builder')
            ->where('builder.name_builder', 'like', '%' . $builder . '%')
            ->select('builder.*', 'builder.state as idstate', DB::raw('(CASE WHEN builder.state = "1" THEN "Activo" WHEN builder.state = "2" THEN "Inactivo" ELSE "Por confirmar" END) AS state'))
            ->take(10)
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }
    public function listsic(Request $request)
    {
        $builder = $request->input("builder");

        $search = DB::table('sic_builder')
            ->where('builder_idbuilder', $builder)
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);

    }

    public function listcom(Request $request)
    {
        $builder = $request->input("builder");

        $search = DB::table('competitions_builder')
            ->where('builder_idbuilder', $builder)
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);

    }

    public function search_address(Request $request)
    {
        $address = $request->input("address");

        $search = DB::table('client_account')
            ->where('idclient_account', $address)
            ->first();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function certificate_material(Request $request)
    {
        $certificate = $request->input("idmaterial_certificate");

        $search = DB::table('material_documents')
            ->where('material_certificate_idmaterial_certificate', $certificate)
            ->first();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function sic_builder(Request $request)
    {
        $builder_idbuilder = $request->input("builder_idbuilder");

        $search = DB::table('document_builder')
            ->where('sic_builder_idsic_builder', $builder_idbuilder)
            ->first();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function com_builder(Request $request)
    {
        $builder_idbuilder = $request->input("builder_idbuilder");

        $search = DB::table('document_builder')
            ->where('competitions_builder_idcompetitions_builder', $builder_idbuilder)
            ->first();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function change_state(Request $request)
    {
        $idstate   = $request->input("idstate");
        $idservice = $request->input("idservice");

        $update = DB::table('service_certifications')
            ->where('idservice_certifications', $idservice)
            ->update([
                'state' => $idstate,
            ]);

        return response()->json(['status' => 'ok', 'response' => $update], 200);
    }

    public function change_active(Request $request)
    {
        $idstate                               = $request->input("idstate");
        $idservice                             = $request->input("idservice");
        $number                                = $request->input("number");
        $Number_cetificate_idNumber_cetificate = $request->input("Number_cetificate_idNumber_cetificate");

        $update = DB::table('service_certifications')
            ->where('idservice_certifications', $idservice)
            ->update([
                'state' => $idstate,
            ]);

        return response()->json(['status' => 'ok', 'response' => $update], 200);
    }

    public function change_active_service(Request $request)
    {

        $idodi = $request->input("idodi");

        $search = DB::table('service_certifications')
            ->where('odi_idodi', $idodi)
            ->where('state', 1)
            ->select('state')
            ->get();

        foreach ($search as $search) {
            $search->state;

            if ($search->state == 1) {

                return response()->json(['status' => 'ok', 'response' => false], 200);
            }
        }

        $update = DB::table('odi')
            ->where('idodi', $idodi)
            ->update([
                'state' => 3,
            ]);
        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

}
