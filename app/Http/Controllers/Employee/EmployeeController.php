<?php

namespace App\Http\Controllers\Employee;

use App\employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{

    public function autocomplete_employee(Request $request)
    {
        $this->employee = (String)$request->input("term");

        $employee = DB::table('employees')

            ->where('name', 'like', '%' . $this->employee . '%')
            ->orWhere('idemployees', 'like', '%' . $this->employee . '%')
            ->select('employees.*')
            ->take(10)
            ->get();

        $results = array();

        foreach ($employee as $employees) {

            $results[] = [
                'value'          => $employees->name . ' ' . $employees->last_name,
                'label'          => '(' . $employees->idemployees . ')' . ' ' . $employees->name . ' ' . $employees->last_name,
                'idemployees'    => $employees->idemployees,
                'identification' => $employees->Users_id_identification,
                'name'           => $employees->name,
                'last_name'      => $employees->last_name,

            ];

        }
        return response()->json($results);

    }

    public function searc_employee(Request $request)
    {

        $term = $request->input("term");

        $employee = DB::table('employees')
            ->leftjoin('gangs', 'gangs.idgangs', '=', 'employees.id_gangs')

            ->where('name', 'like', '%' . $term . '%')
            ->orWhere('idemployees', $term . '%')
            ->orderBy('name', 'ASC')
            ->select('employees.*', DB::raw('CONCAT(employees.name," ",employees.last_name) AS full_name'), 'gangs.*')
            ->take(16)
            ->get();

        return response()->json(['status' => 'ok', 'employee' => $employee], 200);
    }

    public function insert_employee(Request $request)
    {

        $Users_id_identification = $request->input("Users_id_identification");
        $account_type            = (INT)$request->input("account_type");
        $address                 = (String)mb_strtoupper($request->input("address"));
        $age                     = (INT)$request->input("age");
        $arp                     = (INT)$request->input("arp");
        $birth_date              = $request->input("birth_date");
        $civil_status            = (INT)$request->input("civil_status");
        $education_level         = (INT)$request->input("education_level");
        $eps                     = (INT)$request->input("eps");
        $final_risk_level        = (INT)$request->input("final_risk_level");
        $id_bank                 = (INT)$request->input("id_bank");
        $id_charge               = (INT)$request->input("id_charge");
        $id_clasificacion        = (INT)$request->input("id_clasificacion");
        $id_company              = (INT)$request->input("id_company");
        $id_contract             = (INT)$request->input("id_contract");
        $id_depart               = (INT)$request->input("id_depart");
        $id_municipality         = (INT)$request->input("id_municipality");
        $id_sex                  = (INT)$request->input("id_sex");
        $last_name               = (String)mb_strtoupper($request->input("last_name"));
        $n_account               = (INT)$request->input("n_account");
        $name                    = (String)mb_strtoupper($request->input("name"));
        $pension                 = (INT)$request->input('pension');
        $phone                   = (INT)$request->input("phone");
        $phone_cel               = (INT)$request->input("phone_cel");
        $place_of_work           = (INT)$request->input("place_of_work");
        $state                   = (INT)$request->input("state");
        $sub_charge              = (INT)$request->input("sub_charge");
        $tel_contact             = (INT)$request->input("tel_contact");
        $sub_charge              = (INT)$request->input("sub_charge");
        $final_risk_level        = (INT)$request->input("final_risk_level");

        $id_gangs = (INT)$request->input("id_gangs");

        $insert_employee = DB::table('employees')
            ->insertGetId([
                'birth_date'              => $birth_date,
                'phone'                   => $phone,
                'phone_cel'               => $phone_cel,
                'tel_contact'             => $tel_contact,
                'id_bank'                 => $id_bank,
                'n_account'               => $n_account,
                'account_type'            => $account_type,
                'civil_status'            => $civil_status,
                'place_of_work'           => $place_of_work,
                'state'                   => $state,
                'eps'                     => $eps,
                'pension'                 => $pension,
                'id_charge'               => $id_charge,
                'id_depart'               => $id_depart,
                'id_sex'                  => $id_sex,
                'id_clasificacion'        => $id_clasificacion,
                'id_contract'             => $id_contract,
                'name'                    => $name,
                'last_name'               => $last_name,
                'address'                 => $address,
                'id_municipality'         => $id_municipality,
                'Users_id_identification' => $Users_id_identification,
                'education_level'         => $education_level,
                'id_gangs'                => $id_gangs,
                'arp'                     => $arp,
                'sub_charge'              => $sub_charge,
                'final_risk_level'        => $final_risk_level,

            ]);

        return response()->json(['status' => 'ok', 'idemploye' => $insert_employee], 200);
    }

    public function upload_image(Request $request)
    {
        $idemploye = (int)$request->input("idemploye");
        $image     = $_FILES;

        // echo $name = $image['name'];
        $name = $image["image"]['name'];
        $file = $image["image"]['tmp_name'];

        try {
            \Storage::disk('imagen')->put($idemploye . '.jpeg', \File::get($file));

            $update = DB::table('employees')
                ->where('idemployees', '=', $idemploye)
                ->update([
                    'image' => $idemploye,
                ]);
            $result = true;

        } catch (Exception $e) {
            $result = false;
        }

        return response()->json(['status' => 'ok', 'data' => $result], 200);
    }

    public function search_employee(Request $request)
    {

        try {

            $idemployees             = (String)$request->input("id");
            $Users_id_identification = (String)$request->input("identification");

            $search_employee = DB::table('employees')
                ->join('contract', 'employees.id_contract', '=', 'contract.idcontract')

                ->join('business', 'contract.id_empresa', '=', 'business.idbusiness')
                ->where('Users_id_identification', '=', $Users_id_identification)
                ->select('employees.*', 'business.idbusiness as id_company')
                ->first();

        } catch (\Exception $e) {

        }

        return response()->json(['status' => 'ok', 'data' => $search_employee], 200);
    }

    public function update_employee(Request $request)
    {
        $Users_id_identification = $request->input("Users_id_identification");
        $idemployees             = (INT)$request->input("idemployees");
        $account_type            = (INT)$request->input("account_type");
        $address                 = (String)mb_strtoupper($request->input("address"));
        $age                     = (INT)$request->input("age");
        $arp                     = (INT)$request->input("arp");
        $birth_date              = $request->input("birth_date");
        $civil_status            = (INT)$request->input("civil_status");
        $education_level         = (INT)$request->input("education_level");
        $eps                     = (INT)$request->input("eps");
        $final_risk_level        = (INT)$request->input("final_risk_level");
        $id_bank                 = (INT)$request->input("id_bank");
        $id_charge               = (INT)$request->input("id_charge");
        $id_clasificacion        = (INT)$request->input("id_clasificacion");
        $id_company              = (INT)$request->input("id_company");
        $id_contract             = (INT)$request->input("id_contract");
        $id_depart               = (INT)$request->input("id_depart");
        $id_municipality         = (INT)$request->input("id_municipality");
        $id_sex                  = (INT)$request->input("id_sex");
        $last_name               = (String)mb_strtoupper($request->input("last_name"));
        $n_account               = $request->input("n_account");
        $name                    = (String)mb_strtoupper($request->input("name"));
        $pension                 = (INT)$request->input('pension');
        $phone                   = (INT)$request->input("phone");
        $phone_cel               = $request->input("phone_cel");
        $place_of_work           = (INT)$request->input("place_of_work");
        $state                   = (INT)$request->input("state");
        $sub_charge              = (INT)$request->input("sub_charge");
        $tel_contact             = (INT)$request->input("tel_contact");
        $sub_charge              = (INT)$request->input("sub_charge");
        $final_risk_level        = (INT)$request->input("final_risk_level");
        $id_gangs                = (INT)$request->input("id_gangs");
        $update_employee         = DB::table('employees')
            ->where('idemployees', '=', $idemployees)
            ->update([
                'birth_date'              => $birth_date,
                'phone'                   => $phone,
                'phone_cel'               => $phone_cel,
                'tel_contact'             => $tel_contact,
                'id_bank'                 => $id_bank,
                'n_account'               => $n_account,
                'account_type'            => $account_type,
                'civil_status'            => $civil_status,
                'place_of_work'           => $place_of_work,
                'state'                   => $state,
                'eps'                     => $eps,
                'pension'                 => $pension,
                'id_charge'               => $id_charge,
                'id_depart'               => $id_depart,
                'id_sex'                  => $id_sex,
                'id_clasificacion'        => $id_clasificacion,
                'id_contract'             => $id_contract,
                'name'                    => $name,
                'last_name'               => $last_name,
                'address'                 => $address,
                'id_municipality'         => $id_municipality,
                'Users_id_identification' => $Users_id_identification,
                'education_level'         => $education_level,
                'id_gangs'                => $id_gangs,
                'arp'                     => $arp,
                'sub_charge'              => $sub_charge,
                'final_risk_level'        => $final_risk_level,

            ]);

        $update = true;

        return response()->json(['status' => 'ok', 'update' => $update], 200);
    }

    public function insert_contrat(Request $request)
    {

        $idcontracts   = $request->input("idcontracts");
        $id_company    = $request->input("id_company");
        $contract_type = $request->input("contract_type");
        $extensions    = $request->input("extensions");
        $start_date    = $request->input("start_date");
        $final_date    = $request->input("final_date");
        $position      = $request->input("position");
        $salary        = (float)$request->input("salary");
        $for_distance  = (float)$request->input("for_distance");
        $per_bearing   = (float)$request->input("per_bearing");
        $fixed_bonus   = (float)$request->input("fixed_bonus");
        $id_employee   = $request->input("id_employee");
        $charges       = $request->input("charges");

        $idcontract     = $request->input("idcontract");
        $reason_end     = $request->input("reason_end");
        $insert_contrat = DB::table('contracts')->insertGetId([
            'reason_end'    => mb_strtoupper($reason_end),
            'id_company'    => $id_company,
            'contract_type' => $contract_type,
            'start_date'    => $start_date,
            'final_date'    => $final_date,
            'salary'        => $salary,
            'for_distance'  => $for_distance,
            'per_bearing'   => $per_bearing,
            'fixed_bonus'   => $fixed_bonus,
            'id_employee'   => $id_employee,
            'charges'       => $charges,
            'idcontract'    => $idcontracts,

        ]);
        $insert = true;

        return response()->json(['status' => 'ok', 'idcontrat' => $insert_contrat], 200);
    }

    public function view_contract(Request $request)
    {
        $id_employee = $request->input("id_employee");

        $contract = DB::table('contracts')
            ->leftjoin('business', 'contracts.id_company', '=', 'business.idbusiness')
            ->leftjoin('sub_charge', 'sub_charge.idsub_charge', '=', 'contracts.charges')
            ->leftjoin('contract', 'contracts.idcontract', '=', 'contract.idcontract')
            ->leftjoin('type_contract', 'contracts.contract_type', '=', 'type_contract.idtype_contract')
            ->where('id_employee', '=', $id_employee)
            ->select('contracts.*', 'business.*', 'sub_charge.*', 'type_contract.*', 'contract.contract_name')
            ->get();

        return response()->json(['status' => 'ok', 'contract' => $contract], 200);
    }

    public function request_contract(Request $request)
    {
        $id_contract = $request->input("id_contract");

        $contract = DB::table('contracts')
            ->where('idcontracts', '=', $id_contract)
            ->select('contracts.*')
            ->get();

        return response()->json(['status' => 'ok', 'contract' => $contract], 200);
    }
    public function get_SubCharge(Request $request)
    {
        $idcharge = $request->input("idcharge");

        $search = DB::table('sub_charge')
            ->join('charges', 'sub_charge.id_charge', '=', 'charges.idcharges')
            ->join('clas_charger', 'charges.id_class', '=', 'clas_charger.idclas_charger')
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }
    public function charge(Request $request)
    {

        $idsubcharge = $request->input("idsubcharge");

        $search = DB::table('sub_charge')
            ->join('charges', 'sub_charge.id_charge', '=', 'charges.idcharges')
            ->join('clas_charger', 'charges.id_class', '=', 'clas_charger.idclas_charger')
            ->where('idsub_charge', '=', $idsubcharge)
            ->first();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function update_contract(Request $request)
    {

        $idcontracts   = $request->input("idcontracts");
        $id_company    = $request->input("id_company");
        $contract_type = $request->input("contract_type");
        $extensions    = $request->input("extensions");
        $start_date    = $request->input("start_date");
        $final_date    = $request->input("final_date");
        $position      = $request->input("position");
        $salary        = (float)$request->input("salary");
        $for_distance  = (float)$request->input("for_distance");
        $per_bearing   = (float)$request->input("per_bearing");
        $fixed_bonus   = (float)$request->input("fixed_bonus");
        $id_employee   = $request->input("id_employee");
        $charges       = $request->input("charges");

        $idcontract     = $request->input("idcontract");
        $reason_end     = $request->input("reason_end");
        $insert_contrat = DB::table('contracts')
            ->where('idcontracts', '=', $idcontracts)
            ->update([
                'reason_end'    => mb_strtoupper($reason_end),
                'id_company'    => $id_company,
                'contract_type' => $contract_type,
                'start_date'    => $start_date,
                'final_date'    => $final_date,
                'salary'        => $salary,
                'for_distance'  => $for_distance,
                'per_bearing'   => $per_bearing,
                'fixed_bonus'   => $fixed_bonus,
                'id_employee'   => $id_employee,
                'charges'       => $charges,
                'idcontract'    => $idcontract,

            ]);
        $insert = true;

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }
}
