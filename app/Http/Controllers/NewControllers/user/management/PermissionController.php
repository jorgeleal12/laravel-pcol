<?php

namespace App\Http\Controllers\NewControllers\user\management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    //
    public function create(Request $request)
    {
        $permission = $request->input("permission");

        $search = DB::table('permission')
            ->where('name_permission', $permission)
            ->first();

        if ($search) {
            return response()->json(['status' => 'ok', 'search' => false], 200);
        }

        $insert = DB::table('permission')
            ->insertGetid([
                'name_permission' => $permission,
            ]);

        $insert_permission = DB::table('action_permission')
            ->insert([
                'idpermission' => $insert,
                'save'         => false,
                'edit'         => false,
                'delete'       => false,
            ]);

        return response()->json(['status' => 'ok', 'search' => true], 200);
    }

    public function search(Request $request)
    {
        $search = DB::table('permission')
            ->leftjoin('action_permission', 'action_permission.idpermission', '=', 'permission.idpermission')

            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function update(Request $request)
    {
        $permission   = $request->input("permission");
        $idpermission = $request->input("idpermission");

        $update = DB::table('permission')
            ->where('idpermission', $idpermission)
            ->update([
                'name_permission' => $permission,
            ]);
        return response()->json(['status' => 'ok', 'response' => $update], 200);
    }

    public function create_rol(Request $request)
    {
        $name            = $request->input("name");
        $rolePermissions = $request->input("rolepermission");

        $insert = DB::table('rol')
            ->insertGetid([
                'name_rol' => $name,
            ]);

        foreach ($rolePermissions as $t) {
            $idrol_permission    = isset($t['idrol_permission']) ? $t['idrol_permission'] : null;
            $idaction_permission = $t['idaction_permission'];
            $idpermission        = $t['idpermission'];
            $save                = $t['save'];
            $edit                = $t['edit'];
            $delete              = $t['delete'];

            if (is_null($idrol_permission)) {
                $save = DB::table('rol_permission')
                    ->insert([
                        'rol_idrol'           => $insert,
                        'idaction_permission' => $idaction_permission,
                        'save'                => $save,
                        'edit'                => $edit,
                        'delete'              => $delete,
                    ]);
            } else {

            }

        }
    }

    public function searchs(Request $request)
    {

        $search = DB::table('rol')
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function search_rol(Request $request)
    {
        $idrol = $request->input("idrol");

        $search = DB::table('rol')
            ->join('rol_permission', 'rol_permission.rol_idrol', '=', 'rol.idrol')
            ->where('idrol', $idrol)
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);

    }

    public function update_rol(Request $request)
    {
        $name = $request->input("name");
        $id   = $request->input("id");

        $update = DB::table('rol')
            ->where('idrol', $id)
            ->update([
                'name_rol' => $name,

            ]);
        return response()->json(['status' => 'ok', 'response' => $update], 200);
    }

    public function update_permission_rol(Request $request)
    {
        $save   = $request->input("save");
        $edit   = $request->input("edit");
        $delete = $request->input("delete");

        $idaction_permission = $request->input("idaction_permission");
        $idpermission        = $request->input("idpermission");
        $name_permission     = $request->input("name_permission");
        $idrol_permission    = $request->input("idrol_permission");
        $idrol               = $request->input("idrol");

        $search = DB::table('rol_permission')
            ->where('idrol_permission', $idrol_permission)
            ->first();
        // var_dump($search);
        if ($search) {
            $update = DB::table('rol_permission')
                ->where('idrol_permission', $idrol_permission)
                ->update([
                    'save'   => $save,
                    'edit'   => $edit,
                    'delete' => $delete,

                ]);
            return response()->json(['status' => 'ok', 'response' => false], 200);
        } else {

            $insert = DB::table('rol_permission')
                ->insertGetid([
                    'save'                => $save,
                    'edit'                => $edit,
                    'delete'              => $delete,
                    'rol_idrol'           => $idrol,
                    'idaction_permission' => $idaction_permission,
                ]);
            return response()->json(['status' => 'ok', 'response' => true, 'result' => $insert], 200);
        }
    }
}
