<?php

namespace App\Http\Controllers\NewControllers\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    //

    public function create(Request $request)
    {
        $idemployees             = $request->input("idemployees");
        $name                    = $request->input("name");
        $last_name               = $request->input("last_name");
        $sex                     = $request->input("sex");
        $identification          = $request->input("identification");
        $phone                   = $request->input("phone");
        $cel                     = $request->input("cel");
        $address                 = $request->input("address");
        $civil_status            = $request->input("civil_status");
        $bank                    = $request->input("bank");
        $account_type            = $request->input("account_type");
        $account_number          = $request->input("account_number");
        $birthdate               = $request->input("birthdate");
        $pension_idpension       = $request->input("pension_idpension");
        $arl_idarl               = $request->input("arl_idarl");
        $eps_ideps               = $request->input("eps_ideps");
        $city                    = $request->input("city");
        $department_iddepartment = $request->input("department_iddepartment");
        $phone_contact           = $request->input("phone_contact");

        $search = DB::table('employees')
            ->where('identification', $identification)
            ->first();

        if ($search) {
            return response()->json(['status' => 'ok', 'response' => false], 200);
        }

        $insert = DB::table('employees')
            ->insertGetid([

                'name'                    => $name,
                'last_name'               => $last_name,
                'sex'                     => $sex,
                'identification'          => $identification,
                'phone'                   => $phone,
                'cel'                     => $cel,
                'address'                 => $address,
                'civil_status'            => $civil_status,
                'bank'                    => $bank,
                'account_type'            => $account_type,
                'account_number'          => $account_number,
                'birthdate'               => $birthdate,
                'pension_idpension'       => $pension_idpension,
                'arl_idarl'               => $arl_idarl,
                'eps_ideps'               => $eps_ideps,
                'city'                    => $city,
                'department_iddepartment' => $department_iddepartment,
                'phone_contact'           => $phone_contact,

            ]);

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function update(Request $request)
    {
        $idemployees             = $request->input("idemployees");
        $name                    = $request->input("name");
        $last_name               = $request->input("last_name");
        $sex                     = $request->input("sex");
        $identification          = $request->input("identification");
        $phone                   = $request->input("phone");
        $cel                     = $request->input("cel");
        $address                 = $request->input("address");
        $civil_status            = $request->input("civil_status");
        $bank                    = $request->input("bank");
        $account_type            = $request->input("account_type");
        $account_number          = $request->input("account_number");
        $birthdate               = $request->input("birthdate");
        $pension_idpension       = $request->input("pension_idpension");
        $arl_idarl               = $request->input("arl_idarl");
        $eps_ideps               = $request->input("eps_ideps");
        $city                    = $request->input("city");
        $department_iddepartment = $request->input("department_iddepartment");
        $phone_contact           = $request->input("phone_contact");

        $insert = DB::table('employees')
            ->where('idemployees', $idemployees)
            ->update([
                'name'                    => $name,
                'last_name'               => $last_name,
                'sex'                     => $sex,
                'identification'          => $identification,
                'phone'                   => $phone,
                'cel'                     => $cel,
                'address'                 => $address,
                'civil_status'            => $civil_status,
                'bank'                    => $bank,
                'account_type'            => $account_type,
                'account_number'          => $account_number,
                'birthdate'               => $birthdate,
                'pension_idpension'       => $pension_idpension,
                'arl_idarl'               => $arl_idarl,
                'eps_ideps'               => $eps_ideps,
                'city'                    => $city,
                'department_iddepartment' => $department_iddepartment,
                'phone_contact'           => $phone_contact,

            ]);

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function autocomplete(Request $request)
    {
        $employee = $request->input("employee");

        $search = DB::table('employees')
            ->where('name', 'like', '%' . $employee . '%')
            ->orWhere('identification', 'like', '%' . $employee . '%')
            ->select('employees.*', DB::raw('CONCAT(employees.name," ",employees.last_name) AS full_name'))
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function search_employee()
    {
        $search = DB::table('employees')
            ->orderBy('name', 'ASC')
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function create_arl(Request $request)
    {
        $idarl    = $request->input("idarl");
        $name_arl = $request->input("name_arl");
        $type     = $request->input("type");

        if ($type == 1) {
            $insert = DB::table('arl')
                ->insert([
                    'name_arl' => $name_arl,
                ]);
            return response()->json(['status' => 'ok', 'response' => true], 200);
        } else {
            $update = DB::table('arl')
                ->where('idarl', $idarl)
                ->update([
                    'name_arl' => $name_arl,
                ]);
            return response()->json(['status' => 'ok', 'response' => false], 200);
        }

    }

    public function search_arl()
    {

        $search = DB::table('arl')
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function delete_arl(Request $request)
    {
        $idarl = $request->input("idarl");

        $delete = DB::table('arl')
            ->where('idarl', $idarl)
            ->delete();

        return response()->json(['status' => 'ok', 'response' => $delete], 200);
    }
    public function search_eps()
    {
        $search = DB::table('eps')
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function create_eps(Request $request)
    {
        $ideps    = $request->input("ideps");
        $name_eps = $request->input("name_eps");
        $type     = $request->input("type");

        if ($type == 1) {
            $insert = DB::table('eps')
                ->insert([
                    'name_eps' => $name_eps,
                ]);
            return response()->json(['status' => 'ok', 'response' => true], 200);
        } else {
            $update = DB::table('eps')
                ->where('ideps', $ideps)
                ->update([
                    'name_eps' => $name_eps,
                ]);
            return response()->json(['status' => 'ok', 'response' => false], 200);
        }

    }

    public function delete_eps(Request $request)
    {
        $ideps = $request->input("ideps");

        $delete = DB::table('eps')
            ->where('ideps', $ideps)
            ->delete();

        return response()->json(['status' => 'ok', 'response' => $delete], 200);
    }

    public function search_pension()
    {
        $search = DB::table('pension')
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function create_pension(Request $request)
    {
        $idpension    = $request->input("idpension");
        $name_pension = $request->input("name_pension");
        $type         = $request->input("type");

        if ($type == 1) {
            $insert = DB::table('pension')
                ->insert([
                    'name_pension' => $name_pension,
                ]);
            return response()->json(['status' => 'ok', 'response' => true], 200);
        } else {
            $update = DB::table('pension')
                ->where('idpension', $idpension)
                ->update([
                    'name_pension' => $name_pension,
                ]);
            return response()->json(['status' => 'ok', 'response' => false], 200);
        }

    }

    public function delete_pension(Request $request)
    {
        $idpension = $request->input("idpension");

        $delete = DB::table('pension')
            ->where('idpension', $idpension)
            ->delete();

        return response()->json(['status' => 'ok', 'response' => $delete], 200);
    }
}
