<?php

namespace App\Http\Controllers\NewControllers\user\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function create(Request $request)
    {

        $name      = $request->input("name");
        $last_name = $request->input("last_name");
        $email     = $request->input("email");
        //$password          = Hash::make($request->password);
        $password          = $request->input("password");
        $state             = $request->input("state");
        $rol_idrol         = $request->input("rol_idrol");
        $company_idcompany = $request->input("company_idcompany");
        $id                = $request->input("id"); //cedula o identificacion del usuario
        $contract          = $request->input("contract");
        $type              = $request->input("type");

        $insert = DB::table('users')
            ->insertGetid([

                'name'              => $name,
                'last_name'         => $last_name,
                'email'             => $email,
                'password'          => $password,
                'state'             => $state,
                'rol_idrol'         => $rol_idrol,
                'company_idcompany' => $company_idcompany,
                'id'                => $id,
                'type'              => $type,
            ]);

        $this->contract_user($contract, $insert);
        return response()->json(['status' => 'ok', 'reponse' => true, 'result' => $insert], 200);
    }

    public function contract_user($contract, $insertid)
    {
        foreach ($contract as $contracts) {

            $insert = DB::table('contract_user')
                ->insert([
                    'users_idusers' => $insertid,
                    'idcontract'    => $contracts,
                ]);
        }
    }

    public function delete_contract(Request $request)
    {

        $idusers    = $request->input("idusers");
        $idcontract = $request->input("idcontract");

        $search = DB::table('contract_user')
            ->where('users_idusers', $idusers)
            ->where('idcontract', $idcontract)
            ->first();
        // var_dump($search);

        if ($search) {
            $delete = DB::table('contract_user')
                ->where('idcontract', $idcontract)
                ->where('users_idusers', $idusers)
                ->delete();
            // $delete = DB::table('contract_user')
            //     ->where('idcontract', $contracts)
            //     ->where('users_idusers', $idusers)
            //     ->delete();
            // } else {
            //     $insert = DB::table('contract_user')
            //         ->insert([
            //             'users_idusers' => $idusers,
            //             'idcontract'    => $contracts,
            //         ]);
        } else {
            $insert = DB::table('contract_user')
                ->insert([
                    'users_idusers' => $idusers,
                    'idcontract'    => $idcontract,
                ]);
        }

    }

    public function searchs(Request $request)
    {

        $search = DB::table('users')
            ->join('rol', 'rol.idrol', '=', 'users.rol_idrol')
            ->select('users.*', 'rol.*', 'users.state as id_state', DB::raw('(CASE WHEN users.state = "1" THEN "Activo" ELSE "Cancelado" END) AS state'))
            ->get();

        return response()->json(['status' => 'ok', 'reponse' => true, 'result' => $search], 200);
    }

    public function update(Request $request)
    {
        $idusers           = $request->input("idusers");
        $name              = $request->input("name");
        $last_name         = $request->input("last_name");
        $email             = $request->input("email");
        $state             = $request->input("state");
        $rol_idrol         = $request->input("rol_idrol");
        $company_idcompany = $request->input("company_idcompany");
        $id                = $request->input("id"); //cedula o identificacion del usuario
        $type              = $request->input("type");
        $contract          = $request->input("contract");

        $insert = DB::table('users')
            ->where('idusers', $idusers)
            ->update([
                'name'              => $name,
                'last_name'         => $last_name,
                'email'             => $email,
                'state'             => $state,
                'rol_idrol'         => $rol_idrol,
                'company_idcompany' => $company_idcompany,
                'id'                => $id,
                'type'              => $type,
            ]);

        return response()->json(['status' => 'ok', 'reponse' => true], 200);
    }

    public function search_contract(Request $request)
    {

        $idusers = $request->input("idusers");

        $search = DB::table('contract_user')
            ->where('users_idusers', $idusers)
            ->select('contract_user.idcontract')
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function delete(Request $request)
    {
        $iduser = $request->input("iduser");

        $delete = DB::table('users')
            ->where('idusers', $iduser)
            ->delete();
        return response()->json(['status' => 'ok', 'response' => true], 200);

    }

    public function search_user(Request $request)
    {
        $cedula = $request->input("cedula");

        $search = DB::table('employees')
            ->where('identification', $cedula)
            ->first();

        if ($search) {
            $search_user = DB::table('users')
                ->where('id', $search->identification)
                ->first();

            if ($search_user) {
                return response()->json(['status' => 'ok', 'response' => false], 200);
            } else {

                return response()->json(['status' => 'ok', 'response' => true, 'result' => $search], 200);
            }
        } else {
            echo '2';
        }

    }
}
