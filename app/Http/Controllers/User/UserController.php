<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    public $url;
    public function create(Request $request)
    {

        $usuario_name      = mb_strtoupper($request->input("usuario_name"));
        $usuario_last_name = mb_strtoupper($request->input("usuario_last_name"));
        $usuario_cedula    = $request->input("usuario_cedula");
        $usuario_mail      = $request->input("usuario_mail");
        $usuario_pass      = $request->input("usuario_pass");
        $usuario_idprofile = $request->input("usuario_idprofile");
        $id_company        = $request->input("id_company");
        $id_state          = $request->input("id_state");
        try {
            $iduser = DB::table('usuarios')
                ->insertGetId([
                    'usuario_name'      => $usuario_name,
                    'usuario_last_name' => $usuario_last_name,
                    'usuario_cedula'    => $usuario_cedula,
                    'usuario_mail'      => $usuario_mail,
                    'usuario_pass'      => $usuario_pass,
                    'usuario_idprofile' => $usuario_idprofile,
                    'company'           => $id_company,
                    'id_state'          => $id_state,

                ]);
            $result = true;
            UserController::profile_company($iduser, $id_company);
        } catch (\Exception $e) {
            $result = false;
        }
        return response()->json(['status' => 'ok', 'response' => $result], 200);
    }

    public function profile_company($iduser, $id_company)
    {
        $insert = DB::table('profile_company')
            ->insert([
                'id_company' => $id_company,
                'id_user'    => $iduser,
            ]);
    }

    public function update_user(Request $request)
    {

        $idusuarios        = $request->input("idusuarios");
        $usuario_name      = mb_strtoupper($request->input("usuario_name"));
        $usuario_last_name = mb_strtoupper($request->input("usuario_last_name"));
        $usuario_cedula    = $request->input("usuario_cedula");
        $usuario_mail      = $request->input("usuario_mail");
        $usuario_pass      = $request->input("usuario_pass");
        $usuario_idprofile = $request->input("usuario_idprofile");
        $id_company        = $request->input("id_company");
        $state             = $request->input("id_state");

        DB::table('usuarios')
            ->where('idusuarios', '=', $idusuarios)
            ->update([
                'usuario_name'      => $usuario_name,
                'usuario_last_name' => $usuario_last_name,
                'usuario_cedula'    => $usuario_cedula,
                'usuario_mail'      => $usuario_mail,
                'usuario_pass'      => $usuario_pass,
                'usuario_idprofile' => $usuario_idprofile,
                'id_state'          => $state,

            ]);
        $result = true;

        return response()->json(['status' => 'ok', 'response' => $result], 200);
    }

    public function user_search(Request $request)
    {
        $idusuarios = $request->input("idusuarios");
        $idcompany  = $request->input("company");
        $search     = DB::table('usuarios')
            ->join('profile_company', 'usuarios.idusuarios', '=', 'profile_company.id_user')
            ->where('profile_company.id_company', '=', $idcompany)
            ->where('idusuarios', '=', $idusuarios)
            ->first();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }
    public function search_init(Request $request)
    {
        $idcompany = $request->input("idcompany");
        $search    = DB::table('usuarios')
            ->join('p_profiles', 'usuarios.usuario_idprofile', '=', 'p_profiles.idP_profiles')
            ->join('states', 'usuarios.id_state', '=', 'states.idstate')
            ->join('profile_company', 'usuarios.idusuarios', '=', 'profile_company.id_user')
            ->where('profile_company.id_company', '=', $idcompany)
            ->where('company', '=', $idcompany)
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }
    public function readJson(Request $request)
    {
        $user     = $request->input("user");
        $pass     = $request->input("password");
        $contract = $request->input("contract");
        $company  = $request->input("company");

        $var1 = DB::table('usuarios')
            ->join('profile_company', 'usuarios.idusuarios', '=', 'profile_company.id_user')
            ->join('permisos_contract', 'profile_company.idprofile_company', '=', 'permisos_contract.id_profile_company')
            ->join('contract', 'contract.idcontract', '=', 'permisos_contract.id_contract')

        #->join('P_profiles', 'employees.idUser','=', 'P_profiles.idP_profiles')
        #->join('profile_submenu', 'P_profiles.idP_profiles','=', 'profile_submenu.id_profile')
        #->join('permisos_obras', 'profile_submenu.id','=', 'permisos_obras.profile_submenu_idsubmenu')
            ->where('usuarios.usuario_cedula', $user)
            ->where('usuarios.usuario_pass', $pass)
            ->where('permisos_contract.id_contract', $contract)
            ->where('profile_company.id_company', $company)
            ->select('usuarios.*', 'usuarios.usuario_name', 'usuarios.usuario_last_name', 'permisos_contract.*', 'contract.*', 'profile_company.idprofile_company')
            ->first();

        if (!$var1) {
            $response        = false;
            $identification  = false;
            $name            = '';
            $search_cellar   = [];
            $profile_company = '';
        } else {
            $response        = true;
            $identification  = $var1->usuario_cedula;
            $name            = $var1->usuario_name . ' ' . $var1->usuario_last_name;
            $profile_company = $var1->idprofile_company;
            $search_cellar   = DB::table('permisos_cellar')
                ->where('id_profile_company', $var1->idprofile_company)
                ->where('id_company', $var1->company)
                ->select('permisos_cellar.*')
                ->get();
        }
        return response()->json(['status' => 'ok', 'data' => $response, 'identification' => $identification, 'name' => $name, 'cellar' => $search_cellar, 'profile_company' => $profile_company], 200);
    }

    public function change(Request $request)
    {

        $identification = $request->input("identification");
        $contract       = $request->input("contract");
        $company        = $request->input("company");

        $var = DB::table('usuarios')
            ->join('profile_company', 'usuarios.idusuarios', '=', 'profile_company.id_user')
            ->join('permisos_contract', 'profile_company.idprofile_company', '=', 'permisos_contract.id_profile_company')
            ->join('contract', 'contract.idcontract', '=', 'permisos_contract.id_contract')

        #->join('P_profiles', 'employees.idUser','=', 'P_profiles.idP_profiles')
        #->join('profile_submenu', 'P_profiles.idP_profiles','=', 'profile_submenu.id_profile')
        #->join('permisos_obras', 'profile_submenu.id','=', 'permisos_obras.profile_submenu_idsubmenu')
            ->where('usuarios.usuario_cedula', $identification)
            ->where('permisos_contract.id_contract', $contract)
            ->where('profile_company.id_company', $company)
            ->select('usuarios.*', 'usuarios.usuario_name', 'usuarios.usuario_last_name', 'permisos_contract.*', 'contract.*', 'profile_company.idprofile_company')
            ->first();

        if ($var) {
            $response        = true;
            $identification  = $var->usuario_cedula;
            $name            = $var->usuario_name . ' ' . $var->usuario_last_name;
            $profile_company = $var->idprofile_company;
            $search_cellar   = DB::table('permisos_cellar')
                ->where('id_profile_company', $var->idprofile_company)
                ->where('id_company', $var->company)
                ->select('permisos_cellar.*')
                ->get();

            return response()->json(['status' => 'ok', 'data' => true, 'identification' => $identification, 'name' => $name, 'cellar' => $search_cellar, 'profile_company' => $profile_company], 200);

        }
        return response()->json(['status' => 'ok', 'response' => true, 'data' => false], 200);
    }

    public function add_contract(Request $request)
    {
        $idprofile = $request->input("idprofile");
        $idcontrac = $request->input("idcontrac");
        $business  = $request->input("business");

        $search = DB::table('permisos_contract')
            ->where('id_profile_company', '=', $idprofile)
            ->where('id_contract', '=', $idcontrac)
            ->where('business', '=', $business)
            ->first();

        if (!$search) {

            $search = DB::table('permisos_contract')
                ->insert([
                    'id_profile_company' => $idprofile,
                    'id_contract'        => $idcontrac,
                    'business'           => $business,
                ]);
            $exist = false;
        } else {
            $exist = true;
        }

        return response()->json(['status' => 'ok', 'data' => $exist], 200);
    }

    public function search_contract(Request $request)
    {
        $idprofile = $request->input("idprofile");
        $search    = DB::table('permisos_contract')
            ->join('contract', 'permisos_contract.id_contract', '=', 'contract.idcontract')
            ->where('id_profile_company', '=', $idprofile)
            ->get();

        return response()->json(['status' => 'ok', 'data' => $search], 200);
    }

    public function delete_contract(Request $request)
    {
        $idpermisos_contract = $request->input("idpermisos_contract");

        $search = DB::table('permisos_contract')
            ->where('idpermisos_contract', '=', $idpermisos_contract)
            ->delete();
        return response()->json(['status' => 'ok', 'data' => true], 200);
    }

    public function session_movil(Request $request)
    {

        $password = $request->input("password");
        $usuario  = $request->input("usuario");

        $search = DB::table('usuarios')
            ->where('usuario_cedula', '=', $usuario)
            ->where('usuario_pass', '=', $password)
            ->first();
        return response()->json(['status' => 'ok', 'data' => $search], 200);
    }

    public function cargarCompany(Request $request)
    {

        $cargarCompany = DB::table('business')

            ->get();
        return response()->json(['status' => 'ok', 'search' => $cargarCompany], 200);

    }

    public function cargarAlmacen(Request $request)
    {

        $cargarCompany = DB::table('cellar')

            ->get();
        return response()->json(['status' => 'ok', 'search' => $cargarCompany], 200);

    }

    public function search_cellar(Request $request)
    {
        $idprofile = $request->input("idprofile");

        $search = DB::table('permisos_cellar')
            ->join('cellar', 'permisos_cellar.id_cellar', '=', 'cellar.idcellar')
            ->where('id_profile_company', '=', $idprofile)
            ->get();

        return response()->json(['status' => 'ok', 'data' => $search], 200);
    }

    public function save_cellar(Request $request)
    {
        $idprofile  = $request->input("idprofile");
        $idalmacen  = $request->input("idalmacen");
        $id_company = $request->input("idcompany");

        $search = DB::table('permisos_cellar')
            ->where('id_profile_company', '=', $idprofile)
            ->where('id_cellar', '=', $idalmacen)
            ->where('id_company', '=', $id_company)
            ->first();

        if (!$search) {

            $search = DB::table('permisos_cellar')
                ->insert([
                    'id_profile_company' => $idprofile,
                    'id_cellar'          => $idalmacen,
                    'id_company'         => $id_company,
                ]);
            $exist = false;
        } else {
            $exist = true;
        }

        return response()->json(['status' => 'ok', 'data' => $exist], 200);
    }

    public function delete_cellar(Request $request)
    {
        $id_permits_cellar = $request->input("id_permits_cellar");

        $delete = DB::table('permisos_cellar')
            ->where('id_permits_cellar', $id_permits_cellar)
            ->delete();
        return response()->json(['status' => 'ok', 'data' => true], 200);
    }

    public function reset(Request $request)
    {
        $resuser     = $request->input("mail");
        $respassword = $request->input("pass");

        $usuarios = DB::table('usuarios')
            ->where('usuario_cedula', '=', $resuser)
            ->first();

        $random     = '';
        $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $strlength  = strlen($characters);

        for ($i = 0; $i < 15; $i++) {

            $random .= $characters[rand(0, $strlength - 1)];
        }

        if (!$usuarios) {
// cuando el usuario no existe
            echo 'no';

        } else {

            //cuando el usuario si existe
            // mensaje para decir que usuario no existe valide usuario
            $name = $usuarios->usuario_name;
            $mail = $usuarios->usuario_mail;

            echo $random;
            $create = DB::table('usuarios')
                ->where('usuario_cedula', '=', $resuser)
                ->update([
                    'random'   => $random,
                    'pass_new' => $respassword,
                ]);
        }

        // echo $mail ;
        // echo  $pass;
        $this->url = 'http://192.168.1.8/sip/public/api/user/conf-pass?key=' . $random . '&iduser=' . $usuarios->idusuarios;

        $data = array("url" => $this->url);

        Mail::send('email_password.reset_pass', $data, function ($message) use ($usuarios) {

            //var_dump($message);
            $message->from('sistemas@grupoempresarialcyc.com', 'Sistemas' . $usuarios->usuario_name);
            $message->to($usuarios->usuario_mail, $usuarios->usuario_name);
            $message->cc('sip@grupoempresarialcyc.com', $usuarios->usuario_name);
            $message->subject('Restablecimiento de contraseña');

        });
    }

    public function conf_pass(Request $request)
    {
        $randon = $request->input("key");
        $iduser = $request->input("iduser");

        $new_pass = db::table('usuarios')
            ->where('idusuarios', $iduser)
            ->first();

        $pass_new = $new_pass->pass_new;

        $update = DB::table('usuarios')
            ->where('idusuarios', $iduser)
            ->where('random', $randon)
            ->update([
                'usuario_pass' => $pass_new,
                'random'       => null,
                'pass_new'     => null,

            ]);
        echo "Su contraseña ha sido restablecida con éxito.";
    }

    public function saveIncome(Request $request)
    {
        $perfil      = $request->input("perfil");
        $contraseña = $request->input("contraseña");
        $estado      = $request->input("estado");

        $search = DB::table('edit_income')
            ->where('idprofile', '=', $perfil)
            ->first();

        if (!$search) {

            $search = DB::table('edit_income')
                ->insert([
                    'idprofile'   => $perfil,
                    'password'    => $contraseña,
                    'edit_income' => $estado,
                ]);
            $exist = false;
        } else {
            $exist = true;
        }

        return response()->json(['status' => 'ok', 'data' => $exist], 200);
    }

}
