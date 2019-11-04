<?php

namespace App\Http\Controllers\NewControllers\login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    //
    public function login(Request $request)
    {

        $user     = $request->input("user");
        $pass     = $request->input("pass");
        $company  = $request->input("company");
        $contract = $request->input("contract");

        $search = DB::table('users')
            ->join('contract_user', 'contract_user.users_idusers', '=', 'users.idusers')
            ->where('idcontract', $contract)
            ->where('id', $user)
            ->where('password', $pass)
            ->where('company_idcompany', $company)
            ->where('state', 1)
            ->select('users.id as cedula', 'users.name', 'users.last_name', 'users.company_idcompany', DB::raw('"session" as id'), 'users.idusers as idusers', 'idcontract')
            ->first();

        if ($search) {
            return response()->json(['status' => 'ok', 'reponse' => true, 'result' => $search], 200);
        } else {
            return response()->json(['status' => 'ok', 'reponse' => false], 200);
        }

    }
    public function load_rol(Request $request)
    {
        $idusers = $request->input("idusers");

        $search = DB::table('users')
            ->join('rol', 'rol.idrol', '=', 'users.rol_idrol')
            ->join('rol_permission', 'rol_permission.rol_idrol', '=', 'rol.idrol')
            ->join('action_permission', 'action_permission.idaction_permission', '=', 'rol_permission.idaction_permission')
            ->where('idusers', $idusers)
            ->select('rol.idrol', 'rol_permission.idrol_permission', 'rol_permission.idaction_permission', 'rol_permission.save', 'rol_permission.delete', 'rol_permission.edit', 'action_permission.idpermission as id')
            ->get();

        return response()->json(['status' => 'ok', 'reponse' => $search], 200);
    }
}
