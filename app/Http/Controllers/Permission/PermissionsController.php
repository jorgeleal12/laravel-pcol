<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionsController extends Controller
{

    public function profile(Request $request)
    {
        $identification = $request->input("id");
        $submenu        = $request->input("submenu");
        $company        = $request->input("company");

        #$var1= DB::table('employees')
        #->join('employees', 'p_profiles.idP_profiles','=', 'employees.idUser')

        #->join('P_profiles', 'profile_submenu.id_profile','=', 'P_profiles.idP_profiles')
        #->join('permisos_obras', 'profile_submenu.id','=', 'permisos_obras.profile_submenu_idsubmenu')
        #->where('employees.Users_id_identification',  $identification)
        #->where('profile_submenu.idsubmenu', $submenu)
        #->select('employees.*')
        #->first();

        $var1 = DB::table('usuarios')
            ->leftjoin('profile_company', 'usuarios.idusuarios', '=', 'profile_company.id_user')
            ->leftjoin('p_profiles', 'usuarios.usuario_idprofile', '=', 'p_profiles.idP_profiles')
            ->leftjoin('permisos', 'profile_company.idprofile_company', '=', 'permisos.id_profile')
            ->where('permisos.profile_idsubmenu', $submenu)
            ->where('usuarios.usuario_cedula', $identification)
            ->where('usuarios.company', $company)
            ->select('usuarios.*', 'permisos.*', 'profile_company.*')
            ->first();

        return response()->json([
            'status'         => 'ok',
            'identification' => $var1->usuario_cedula,
            'save'           => $var1->save,
            'update'         => $var1->update,
            'delete'         => $var1->delete,
            'permit1'        => $var1->permit1,
            'permit2'        => $var1->permit2,
            'permit3'        => $var1->permit3,
            'permit4'        => $var1->permit4,
            'permit5'        => $var1->permit5,

        ], 200);

    }

    public function obr(Request $request)
    {

        $identification = $request->input("user");
        $var1           = DB::table('usuarios')

            ->join('p_profiles', 'usuarios.usuario_idprofile', '=', 'p_profiles.idP_profiles')

            ->join('permisos_obras', 'p_profiles.idP_profiles', '=', 'permisos_obras.profile_submenu_idsubmenu')
        #->where('users.id_identification',  $identification)

            ->where('usuarios.usuario_cedula', $identification)
            ->select('usuarios.usuario_cedula', 'permisos_obras.actividades', 'permisos_obras.eventos', 'permisos_obras.items', 'permisos_obras.materiales', 'permisos_obras.programacion')
            ->first();

        return response()->json(['status' => 'ok', 'permit_obr' => $var1], 200);
    }

    public function profile_search(Request $request)
    {

        $profile = $request->input("idprofile");
        $company = $request->input("company");

        $profiles = DB::table('p_profiles')
        //  ->leftjoin('profile_submenu', 'p_profiles.idP_profiles', '=', 'profile_submenu.id_profile')
        //  ->leftjoin('submenu', 'profile_submenu.idsubmenu', '=', 'submenu.submenu')
            ->leftjoin('permisos', 'p_profiles.idP_profiles', '=', 'permisos.id_profile')
            ->leftjoin('submenu', 'submenu.submenu', '=', 'permisos.profile_idsubmenu')
            ->WHERE('id_company', '=', $company)
            ->WHERE('idP_profiles', '=', $profile)
            ->select('p_profiles.*', 'permisos.*', 'submenu.*')
            ->get();

        return response()->json(['status' => 'ok', 'profiles' => $profiles], 200);

    }

    public function create_profile(Request $request)
    {

        $name    = $request->input("name");
        $company = $request->input("idcompnay");

        $inser_profile = DB::table('p_profiles')
            ->insertGetId([
                'name'       => $name,
                'id_company' => $company,

            ]);

        $Array_submenu = Config::get('Config.submenu');

        $permisos = PermissionsController::create_permisos($Array_submenu, $inser_profile);

        return response()->json(['status' => 'ok', 'profiles' => $inser_profile], 200);
    }

    public function create_permisos($Array_submenu, $inser_profile)
    {

        foreach ($Array_submenu as $key) {
            $insert = DB::table('permisos')
                ->insertGetId([
                    'save'              => 0,
                    'update'            => 0,
                    'delete'            => 0,
                    'permit1'           => 0,
                    'permit2'           => 0,
                    'permit3'           => 0,
                    'permit4'           => 0,
                    'permit5'           => 0,
                    'id_profile'        => $inser_profile,
                    'profile_idsubmenu' => $key,
                ]);

        }

    }

    public function update_permisos(Request $request)
    {
        $profile    = $request->input("profile");
        $permiso    = $request->input("permiso");
        $id_permiso = $request->input("id_permiso");
        $value      = $request->input("value");

        if ($value == false) {

            $value = 0;
        } else {

            $value = 1;
        }

        if ($permiso == 'save') {

            $permi = 'save';
        }

        if ($permiso == 'update') {

            $permi = 'update';
        }

        if ($permiso == 'delete') {

            $permi = 'delete';

        }
        if ($permiso == 'permit1') {

            $permi = 'permit1';

        }
        if ($permiso == 'permit2') {

            $permi = 'permit2';

        }
        if ($permiso == 'permit3') {

            $permi = 'permit3';

        }
        if ($permiso == 'permit4') {

            $permi = 'permit4';

        }
        if ($permiso == 'permit5') {

            $permi = 'permit5';

        }

        $update = DB::table('permisos')
            ->where('id_permiso', '=', $id_permiso)
            ->where('id_profile', '=', $profile)
            ->update([$permi => $value]);

        return response()->json(['status' => 'ok', 'data' => true], 200);
    }

    public function update_permits(Request $request)
    {
        $profile    = $request->input("profile");
        $permiso    = $request->input("permiso");
        $id_permiso = $request->input("id_permiso");
        $value      = $request->input("value");
        $permits    = $request->input("permits");

        if ($permits == 'internas') {

            PermissionsController::inters($profile, $permiso, $id_permiso, $value);
        }
        if ($permits == 'externas') {

            PermissionsController::externas($profile, $permiso, $id_permiso, $value);
        }
        if ($permits == 'contract') {

            PermissionsController::contract($profile, $permiso, $id_permiso, $value);
        }

    }

}
