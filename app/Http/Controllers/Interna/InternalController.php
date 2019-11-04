<?php

namespace App\Http\Controllers\Interna;

use App\Http\Controllers\Controller;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InternalController extends Controller
{
    public function search_consec(Request $request)
    {

        $consecutive = $request->input("term");
        $company     = $request->input("company");

        $search = DB::table('worki')
            ->where('id_company', '=', $company)
            ->where('consecutive', 'like', $consecutive . '%')
            ->orderBy('consecutive', 'ASC')
            ->select('worki.consecutive')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'consecutive' => $search], 200);
    }

    public function search_consec_dispache(Request $request)
    {

        $consecutive = $request->input("term");
        $company     = $request->input("company");

        $search = DB::table('worki')
            ->where('id_company', '=', $company)
            ->join('tipos_obr_internas', 'tipos_obr_internas.idtipos_obr_internas', '=', 'worki.worki_type_obr')
            ->where('consecutive', 'like', $consecutive . '%')
            ->orderBy('consecutive', 'ASC')
            ->select('worki.*', 'tipos_obr_internas.tipos_obr_internas_name')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'consecutive' => $search], 200);
    }

    public function search_pedido(Request $request)
    {

        $peido   = $request->input("term");
        $company = $request->input("company");

        $search = DB::table('worki')
            ->where('id_company', '=', $company)
            ->where('Pedido', 'like', $peido . '%')
            ->orderBy('Pedido', 'ASC')
            ->select('worki.Pedido')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'Pedido' => $search], 200);
    }

    public function search_ot(Request $request)
    {

        $peido   = $request->input("term");
        $company = $request->input("company");

        $search = DB::table('worki')
            ->where('id_company', '=', $company)
            ->where('Pedido', 'like', $peido . '%')
            ->orderBy('Pedido', 'ASC')
            ->select('worki.Pedido')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'Pedido' => $search], 200);
    }

    public function search_cedula(Request $request)
    {

        $Cedula  = $request->input("term");
        $company = $request->input("company");

        $search = DB::table('worki')
            ->where('id_company', '=', $company)
            ->where('Cedula', 'like', $Cedula . '%')
            ->orderBy('Cedula', 'ASC')
            ->select('worki.Cedula')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'Cedula' => $search], 200);
    }

    public function search_address(Request $request)
    {

        $Direccion = $request->input("term");
        $company   = $request->input("company");

        $search = DB::table('worki')
            ->where('id_company', '=', $company)
            ->where('Direccion', 'like', $Direccion . '%')
            ->orderBy('Direccion', 'ASC')
            ->select('worki.Direccion')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'Direccion' => $search], 200);
    }

    public function search_instal(Request $request)
    {

        $Instalacion = $request->input("term");
        $company     = $request->input("company");

        $search = DB::table('worki')
            ->where('id_company', '=', $company)
            ->where('Instalacion', 'like', $Instalacion . '%')
            ->orderBy('Instalacion', 'ASC')
            ->select('worki.Instalacion')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'Instalacion' => $search], 200);
    }

    public function searchpedido(Request $request)
    {
        $pedido  = $request->input("pedido");
        $company = $request->input("company");

        $search = DB::table('worki')
            ->join('municipality', 'worki.Municipio', '=', 'municipality.id_dane')
            ->where('id_company', '=', $company)

            ->where('Pedido', '=', $pedido)
            ->orderBy('Pedido', 'ASC')
            ->select('worki.*', 'municipality.name_municipality')
            ->get();
        return response()->json(['status' => 'ok', 'result' => $search], 200);
    }

    public function searchcedula(Request $request)
    {
        $cedula  = $request->input("cedula");
        $company = $request->input("company");

        $search = DB::table('worki')
            ->join('municipality', 'worki.Municipio', '=', 'municipality.id_dane')
            ->where('id_company', '=', $company)

            ->where('Cedula', '=', $cedula)
            ->orderBy('cedula', 'ASC')
            ->select('worki.*', 'municipality.name_municipality')
            ->get();
        return response()->json(['status' => 'ok', 'result' => $search], 200);
    }

    public function searchaddress(Request $request)
    {
        $address = $request->input("address");
        $company = $request->input("company");

        $search = DB::table('worki')
            ->join('municipality', 'worki.Municipio', '=', 'municipality.id_dane')
            ->where('id_company', '=', $company)

            ->where('Direccion', '=', $address)
            ->orderBy('Direccion', 'ASC')
            ->select('worki.*', 'municipality.name_municipality')
            ->get();
        return response()->json(['status' => 'ok', 'result' => $search], 200);
    }

    public function search_consecutive(Request $request)
    {
        $consecutive = $request->input("consec");
        $company     = $request->input("company");

        $search = DB::table('worki')
            ->leftjoin('municipality', 'worki.Municipio', '=', 'municipality.id_dane')
            ->leftjoin('tipos_obr_internas', 'worki.worki_type_obr', '=', 'tipos_obr_internas.idtipos_obr_internas')
            ->where('id_company', '=', $company)

            ->where('consecutive', '=', $consecutive)
            ->orderBy('consecutive', 'ASC')
            ->select('worki.*', 'municipality.name_municipality', 'tipos_obr_internas.tipos_obr_internas_name')
            ->get();
        return response()->json(['status' => 'ok', 'result' => $search], 200);
    }

    public function searchinstall(Request $request)
    {
        $install = $request->input("install");
        $company = $request->input("company");

        $search = DB::table('worki')
            ->join('municipality', 'worki.Municipio', '=', 'municipality.id_dane')
            ->where('id_company', '=', $company)

            ->where('Instalacion', '=', $install)
            ->orderBy('Instalacion', 'ASC')
            ->select('worki.*', 'municipality.name_municipality')
            ->get();
        return response()->json(['status' => 'ok', 'result' => $search], 200);
    }

    public function searchobr(Request $request)
    {
        $idwork = $request->input("idwork");

        $search = DB::table('worki')
            ->join('municipality', 'worki.Municipio', '=', 'municipality.id_dane')
            ->leftJoin('series', 'worki.idworkI', '=', 'series.idobr')
            ->where('idworkI', '=', $idwork)
            ->select('worki.*', 'municipality.name_municipality', 'worki.worki_state as worki_state_ant', 'series.idseries as serie_id', 'worki.Serie_Medidor as Serie_Medidor', 'series.serie_marca',

                DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=worki.programado_A) AS nameprogramado"),
                DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=worki.Hace_1) AS nameHace_1"),
                DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=worki.Hace_2) AS nameHace_2"),
                DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=worki.Hace_3) AS nameHace_3"),
                DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=worki.Planea) AS namePlanea"),
                DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=worki.Verifica) AS nameVerifica"),
                DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=worki.Aprueba) AS nameAprueba"))
            ->first();

        $ot = DB::table('ot')
            ->where('id_obr', '=', $idwork)
            ->join('subtipo_obr_internas', 'ot.sub_tipo', '=', 'subtipo_obr_internas.idsubtipo_obr_internas')
            ->orderBy('idsubtipo_obr_internas', 'ASC')
            ->get();

        return response()->json(['status' => 'ok', 'result' => $search, 'ot' => $ot], 200);
    }

    public function update_ot(Request $request)
    {
        $consec   = $request->input("consec");
        $id_obr   = $request->input("id_obr");
        $user     = $request->input("user");
        $company  = $request->input("company");
        $contract = $request->input("contract");
        $obs      = $request->input("obs");
        $ot       = $request->input("ot");
        $state    = $request->input("state");

        for ($i = 0; $i < count($ot); $i++) {

            $idOT       = $ot[$i]["idOT"];
            $OT         = $ot[$i]["OT"];
            $fprogra    = $ot[$i]["fprogra"];
            $fstate     = $ot[$i]["fstate"];
            $sub_tipo   = $ot[$i]["sub_tipo"];
            $sub_estado = $ot[$i]["sub_estado"];

            $search = DB::table('ot')
                ->where('idOT', '=', $idOT)
                ->select('ot.sub_estado', 'ot.sub_tipo')
                ->first();

            $estado  = $search->sub_estado;
            $subtipo = $search->sub_tipo;

            if ($estado != $sub_estado or $subtipo != $sub_tipo) {

                InternalController::historico($consec, $contract, $company, $OT, $sub_estado, $obs, $sub_tipo, $user, $state);
            }

            $update_ot = DB::table('ot')
                ->where('idOT', '=', $idOT)
                ->update([
                    'sub_estado' => $sub_estado,
                    'sub_tipo'   => $sub_tipo,
                    'fprogra'    => $fprogra,
                    'fstate'     => $fstate,
                ]);

        }
    }
    public function historico($consec, $contract, $company, $OT, $sub_estado, $obs, $sub_tipo, $user, $state)
    {
        $consec;
        $contract;
        $company;
        $OT;
        $sub_estado;
        $obs;
        $sub_tipo;
        $user;
        $state;
        $date   = date('Y-m-d h:i:s a', time());
        $insert = DB::table('histo_obr')
            ->insert([
                'histo_obr_consec'   => $consec,
                'histo_obr_contrac'  => $contract,
                'histo_obr_company'  => $company,
                'histo_obr_ot'       => $OT,
                'histo_obr_state'    => $sub_estado,
                'histo_obr_date'     => $date,
                'histo_obr_obs'      => $obs,
                'histo_obr_sub_tipo' => $sub_tipo,
                'histo_obr_tipo'     => $state,
                'histo_obr_user'     => $user,

            ]);
    }
    public function subtipo_obr_internas(Request $request)
    {
        $id_tipo = $request->input("id_tipo");

        $subtipo_obr_internas = DB::table('subtipo_obr_internas')
        //     ->where('id_tipo', '=', $id_tipo)
            ->get();

        return response()->json(['status' => 'ok', 'subtipo_obr_internas' => $subtipo_obr_internas], 200);
    }

    public function sub_state(Request $request)
    {
        //$id_tipo = $request->input("sub_state");

        $sub_state = DB::table('sub_state')
        //     ->where('id_tipo', '=', $id_tipo)
            ->get();

        return response()->json(['status' => 'ok', 'sub_state' => $sub_state], 200);
    }

    public function tipo_obr()
    {

        $tipos_obr_internas = DB::table('tipos_obr_internas')
            ->get();

        return response()->json(['status' => 'ok', 'tipos_obr_internas' => $tipos_obr_internas], 200);
    }

    public function state()
    {

        $state_obr = DB::table('state_obr')
            ->get();

        return response()->json(['status' => 'ok', 'state_obr' => $state_obr], 200);
    }

    public function Tipo_Anillo()
    {

        $Tipo_Anillo = DB::table('tipo_anillo')
            ->get();

        return response()->json(['status' => 'ok', 'Tipo_Anillo' => $Tipo_Anillo], 200);
    }

    public function Tipo_Empalme()
    {

        $Tipo_Empalme = DB::table('tipo_empalme')
            ->get();

        return response()->json(['status' => 'ok', 'Tipo_Empalme' => $Tipo_Empalme], 200);
    }

    public function Accesorio()
    {

        $accesorio = DB::table('accesorio')
            ->get();

        return response()->json(['status' => 'ok', 'accesorio' => $accesorio], 200);
    }

    public function Permiso_Ruptura()
    {

        $permiso_ruptura = DB::table('permiso_ruptura')
            ->get();

        return response()->json(['status' => 'ok', 'permiso_ruptura' => $permiso_ruptura], 200);
    }

    public function Estado_Acometida()
    {

        $estado_acometida = DB::table('estado_acometida')
            ->get();

        return response()->json(['status' => 'ok', 'estado_acometida' => $estado_acometida], 200);
    }

    public function update(Request $request)
    {
        $user         = $request->input("user");
        $idworkI      = $request->input("data.idworkI");
        $consecutive  = $request->input("data.consecutive");
        $idcontrato   = $request->input("data.idcontrato");
        $id_company   = $request->input("data.id_company");
        $Cedula       = $request->input("data.Cedula");
        $Solicitante  = $request->input("data.Solicitante");
        $Telefono     = $request->input("data.Telefono");
        $Tel_Contacto = $request->input("data.Tel_Contacto");
        $Direccion    = $request->input("data.Direccion");
        $Municipio    = $request->input("data.Municipio");
        $Instalacion  = $request->input("data.Instalacion");
        $Barrio       = $request->input("data.Barrio");
        $Zona         = $request->input("data.Zona");
        $Estrato      = $request->input("data.Estrato");
        $worki_state  = $request->input("data.worki_state");
        $Fecha_Prog   = $request->input("data.Fecha_Prog");

        $Atualizacion       = $request->input("data.Atualizacion");
        $Vencimiento        = $request->input("data.Vencimiento");
        $Fecha_Constru      = $request->input("data.Fecha_Constru");
        $Fecha_Svc          = $request->input("data.Fecha_Svc");
        $Acta               = $request->input("data.Acta");
        $Cocineta           = $request->input("data.Cocineta");
        $Cocineta_Entregada = $request->input("data.Cocineta_Entregada");
        $Tipo_medidor       = $request->input("data.Tipo_medidor");
        $Serie_Medidor      = $request->input("data.Serie_Medidor");
        $serie_id           = $request->input("data.serie_id");
        $Obs_Pedido         = mb_strtoupper($request->input("data.Obs_Pedido"));
        $Obs_servicio       = mb_strtoupper($request->input("data.Obs_servicio"));

        $Fecha_Estado    = $request->input("data.Fecha_Estado");
        $Fecha_Recorrido = $request->input("data.Fecha_Recorrido");
        $nameprogramado  = $request->input("data.nameprogramado");
        $nameAprueba     = $request->input("data.nameAprueba");
        $nameHace_1      = $request->input("data.nameHace_1");
        $nameHace_2      = $request->input("data.nameHace_2");
        $nameHace_3      = $request->input("data.nameHace_3");
        $nameVerifica    = $request->input("data.nameVerifica");
        $namePlanea      = $request->input("data.namePlanea");

        $programado_A = $request->input("data.programado_A");
        $Aprueba      = $request->input("data.Aprueba");
        $Hace_1       = $request->input("data.Hace_1");
        $Hace_2       = $request->input("data.Hace_2");
        $Hace_3       = $request->input("data.Hace_3");
        $Verifica     = $request->input("data.Verifica");
        $Planea       = $request->input("data.Planea");

        $f_Planea   = $request->input("data.f_Planea");
        $f_Hace_1   = $request->input("data.f_Hace_1");
        $f_Hace_2   = $request->input("data.f_Hace_2");
        $f_Hace_3   = $request->input("data.f_Hace_3");
        $f_Verifica = $request->input("data.f_Verifica");
        $f_Aprueba  = $request->input("data.f_Aprueba");

        $Aprueba1      = isset($nameAprueba["idemployees"]) ? $nameAprueba["idemployees"] : $Aprueba;
        $programado_A1 = isset($nameprogramado["idemployees"]) ? $nameprogramado["idemployees"] : $programado_A;
        $Hace_11       = isset($nameHace_1["idemployees"]) ? $nameHace_1["idemployees"] : $Hace_1;
        $Hace_22       = isset($nameHace_2["idemployees"]) ? $nameHace_2["idemployees"] : $Hace_2;
        $Hace_33       = isset($nameHace_3["idemployees"]) ? $nameHace_3["idemployees"] : $Hace_3;
        $Verifica1     = isset($nameVerifica["idemployees"]) ? $nameVerifica["idemployees"] : $Verifica;
        $Planea1       = isset($namePlanea["idemployees"]) ? $namePlanea["idemployees"] : $Planea;

        $search = DB::table('worki')
            ->Where('idworkI', $idworkI)
            ->select('worki.Obs_servicio', 'worki.worki_state')
            ->first();

        $obsservicio = $search->Obs_servicio;
        $state       = $search->worki_state;

        if ($obsservicio != $Obs_servicio or $state != $worki_state) {
            InternalController::historico($consecutive, $idcontrato, $id_company, null, null, $Obs_servicio, null, $user, $worki_state);
        }

        try {

            $update = DB::table('worki')
                ->where('idworkI', '=', $idworkI)
                ->update([

                    'Instalacion'        => $Instalacion,
                    'worki_state'        => $worki_state,
                    'Direccion'          => $Direccion,
                    'Solicitante'        => $Solicitante,
                    'Cedula'             => $Cedula,
                    'Telefono'           => $Telefono,
                    'Tel_Contacto'       => $Tel_Contacto,
                    'Municipio'          => $Municipio,
                    'Acta'               => $Acta,
                    // 'Estrato'            => $Acta,
                    'Barrio'             => $Barrio,
                    'Zona'               => $Zona,
                    'Serie_Medidor'      => $Serie_Medidor,
                    'Vencimiento'        => $Vencimiento,
                    'Atualizacion'       => $Atualizacion,
                    'Fecha_Svc'          => $Fecha_Svc,
                    'Fecha_Constru'      => $Fecha_Constru,
                    'Acta'               => $Acta,
                    'Cocineta'           => $Cocineta,
                    'Cocineta_Entregada' => $Cocineta_Entregada,
                    'Tipo_medidor'       => $Tipo_medidor,
                    'Obs_Pedido'         => $Obs_Pedido,
                    'Obs_servicio'       => $Obs_servicio,
                    'Fecha_Recorrido'    => $Fecha_Recorrido,
                    'Fecha_Estado'       => $Fecha_Estado,
                    'programado_A'       => $programado_A1,
                    'Planea'             => $Planea1,
                    'Hace_1'             => $Hace_11,
                    'Hace_2'             => $Hace_22,
                    'Hace_3'             => $Hace_33,
                    'Verifica'           => $Verifica1,
                    'Aprueba'            => $Aprueba1,
                    'f_Planea'           => $f_Planea,
                    'f_Hace_1'           => $f_Hace_1,
                    'f_Hace_2'           => $f_Hace_2,
                    'f_Hace_3'           => $f_Hace_3,
                    'f_Verifica'         => $f_Verifica,
                    'f_Aprueba'          => $f_Aprueba,
                    'Fecha_Prog'         => $Fecha_Prog,

                ]);

            $state = true;

            if ($serie_id == '') {

                $updateSerie = DB::table('series')
                    ->where('idobr', '=', $idworkI)
                    ->update([
                        'serie_estado' => 2,
                        'idobr'        => '',
                    ]);
            }
            if ($serie_id != '' and $Tipo_medidor == 0) {
                $updateSerie = DB::table('series')
                    ->where('idseries', '=', $serie_id)
                    ->update([
                        'serie_estado' => 3,
                        'idobr'        => $idworkI,
                    ]);
            }

        } catch (\Exception $e) {

            $state = false;
        }

        return response()->json(['status' => 'ok', 'state' => $state], 200);
    }

    public function serie_medidor(Request $request)
    {

        $serie = $request->input("serie");

        $num    = '58';
        $serie1 = $num . $serie;
        $search = DB::table('series')
            ->where('serie_nro_serie', '=', $serie1)
            ->select('series.*')
            ->first();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }
    public function clasificacion()
    {
        $clasificacion_dac = DB::table('clasificacion_dac')
            ->get();

        return response()->json(['status' => 'ok', 'clasificacion_dac' => $clasificacion_dac], 200);
    }

    public function motivos_dac(Request $request)
    {
        $clasificacion = $request->input("clasificacion");

        $motivos_dac = DB::table('motivos_dac')
            ->where('id_clasificacion', '=', $clasificacion)
            ->get();

        return response()->json(['status' => 'ok', 'motivos_dac' => $motivos_dac], 200);
    }

    public function dac(Request $request)
    {
        $consecutive = $request->input("consecutive");
        $company     = $request->input("company");

        $dac = DB::table('dac_worki')
            ->join('clasificacion_dac', 'dac_worki.id_clasif', '=', 'clasificacion_dac.idclasificacion_dac')
            ->join('motivos_dac', 'dac_worki.id_mot', '=', 'motivos_dac.idmotivos_dac')
            ->where('dac_consec', '=', $consecutive)
            ->where('dac_company', '=', $company)
            ->select('dac_worki.*', 'clasificacion_dac.name_dac', 'motivos_dac.name_mot')
            ->get();

        return response()->json(['status' => 'ok', 'dac' => $dac], 200);
    }

    public function save_dac(Request $request)
    {
        $consecutive       = $request->input("consecutive");
        $company           = $request->input("company");
        $idcontrato        = $request->input("idcontrato");
        $clasificacion_dac = $request->input("dac.id_clasif");
        $motivo_dac        = $request->input("dac.id_mot");
        $date_dac          = $request->input("dac.date");
        $obs_dac           = mb_strtoupper($request->input("dac.obs"));

        try {
            $dac = DB::table('dac_worki')
                ->insert([
                    'dac_company' => $company,
                    'idcontrato'  => $idcontrato,
                    'dac_consec'  => $consecutive,
                    'date'        => $date_dac,
                    'id_mot'      => $motivo_dac,
                    'id_clasif'   => $clasificacion_dac,
                    'obs'         => $obs_dac,

                ]);
            $response = true;

        } catch (\Exception $e) {

            $response = false;
        }

        return response()->json(['status' => 'ok', 'response' => $response], 200);
    }

    public function search_dac(Request $request)
    {
        $iddac_worki = $request->input("iddac_worki");

        $search_dac = DB::table('dac_worki')
            ->where('iddac_worki', '=', $iddac_worki)
            ->first();

        return response()->json(['status' => 'ok', 'response' => $search_dac], 200);
    }

    public function update_dac(Request $request)
    {
        $iddac_worki       = $request->input("dac.iddac_worki");
        $clasificacion_dac = $request->input("dac.id_clasif");
        $motivo_dac        = $request->input("dac.id_mot");
        $date_dac          = $request->input("dac.date");
        $obs_dac           = mb_strtoupper($request->input("dac.obs"));
        try {
            $update = DB::table('dac_worki')
                ->where('iddac_worki', '=', $iddac_worki)
                ->update([
                    'date'      => $date_dac,
                    'id_mot'    => $motivo_dac,
                    'id_clasif' => $clasificacion_dac,
                    'obs'       => $obs_dac,
                ]);
            $response = true;
        } catch (\Exception $e) {
            $response = false;
        }
        return response()->json(['status' => 'ok', 'response' => $response], 200);
    }

    public function search_histo(Request $request)
    {
        $contract = $request->input("contract");
        $consec   = $request->input("consec");

        $search = DB::table('histo_obr')

            ->leftjoin('subtipo_obr_internas', 'subtipo_obr_internas.idsubtipo_obr_internas', '=', 'histo_obr.histo_obr_sub_tipo')

            ->leftjoin('tipos_obr_internas', 'histo_obr.histo_obr_tipo', '=', 'tipos_obr_internas.idtipos_obr_internas')

            ->leftjoin('sub_state', 'histo_obr.histo_obr_state', '=', 'sub_state.idsub_state')

            ->leftjoin('usuarios', 'histo_obr.histo_obr_user', '=', 'usuarios.usuario_cedula')
            ->where('histo_obr_consec', $consec)
            ->where('histo_obr_contrac', $contract)
            ->select('histo_obr.*', 'subtipo_obr_internas.subtipo_obr_internas_name', 'sub_state.sub_state_name',
                'tipos_obr_internas.tipos_obr_internas_name as nombretipo', 'usuarios.usuario_name')
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function search_itemsapli(Request $request)
    {
        $company = $request->input("company");
        $consec  = $request->input("consec");

        $search = DB::table('items_aplicables')
            ->where('items_idcompnay', $company)
            ->where('items_consec', $consec)
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function item_ap(Request $request)
    {
        $idOT   = $request->input("idOT");
        $search = DB::table('items_aplicables')
            ->where('items_aplicables_ot', $idOT)

            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function itemaplic_update(Request $request)
    {
        $name = mb_strtoupper($request->input("name"));
        $id   = $request->input("id");

        try {
            $update = DB::table('items_aplicables')
                ->where('iditems_aplicables', '=', $id)
                ->update(['items_name' => $name]);
            $response = true;
        } catch (\Exception $e) {
            $response = false;
        }

        return response()->json(['status' => 'ok', 'response' => $response], 200);
    }

    public function itemaplic_inser(Request $request)
    {

        $campany  = $request->input("campany");
        $contract = $request->input("contract");
        $consec   = $request->input("consec");
        $name     = mb_strtoupper($request->input("name"));
        $idot     = $request->input("idot");
        $idworkI  = $request->input("idworkI");
        try {
            $nsert = DB::table('items_aplicables')
                ->insert(['items_idcompnay' => $campany,
                    'items_idcontract'          => $contract,
                    'items_aplicables_ot'       => $idot,
                    'items_consec'              => $consec,
                    'items_name'                => $name,
                    'id_obr'                    => $idworkI,
                ]);

            $response = true;
        } catch (\Exception $e) {
            $response = false;
        }

        return response()->json(['status' => 'ok', 'response' => $response], 200);
    }

    public function itemaplic_delet(Request $request)
    {

        $id = $request->input("iditem");

        try {
            $update = DB::table('items_aplicables')
                ->where('iditems_aplicables', '=', $id)
                ->delete();
            $response = true;
        } catch (\Exception $e) {
            $response = false;
        }

        return response()->json(['status' => 'ok', 'response' => $response], 200);
    }

    public function pdf()
    {

        $company_name  = $_POST['company_name'];
        $company       = $_POST['company'];
        $contract_name = $_POST['contract_name'];
        $contract      = $_POST['contract'];
        $company_name  = str_replace(' ', '', $company_name);
        $carpeta       = public_path('/public/' . $company_name . '/' . $contract_name . '/');

        if (!File::exists($carpeta)) {
            $path = public_path('/public/' . $company_name . '/' . $contract_name . '/');
            File::makeDirectory($path, 0777, true);
        } else {

        }
        move_uploaded_file($_FILES['pdf']['tmp_name'], $carpeta . $_FILES['pdf']['name']);

        $url = $company_name . '/' . $contract_name . '/' . $_FILES['pdf']['name'];

        return response()->json(['status' => 'ok', 'url' => $url], 200);
    }

    public function image_upload()
    {

        $id_obr        = $_POST['id_obr'];
        $contract      = $_POST['contract'];
        $company_name  = $_POST['company_name'];
        $contract_name = $_POST['contract_name'];
        $consec        = $_POST['consec'];

        $image = $_FILES;

        foreach ($image as &$image) {

            $name    = $image['name'];
            $file    = $image['tmp_name'];
            $type    = $image['type'];
            $hoy     = date("Y_m_d_H_i_s");
            $Typedoc = explode("/", $type);

            $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

            $strlength = strlen($characters);

            $random       = '';
            $company_name = str_replace(' ', '', $company_name);

            for ($i = 0; $i < 15; $i++) {
                $random .= $characters[rand(0, $strlength - 1)];
            }

            if ($Typedoc[1] == 'jpeg' or $Typedoc[1] == 'png') {

                $namefile = $random . '.' . $Typedoc[1];
                $carpeta  = public_path('/public/internas/images/' . $company_name . '/' . $contract_name . '/' . $consec . '/');

                if (!File::exists($carpeta)) {
                    $path = public_path('/public/internas/images/' . $company_name . '/' . $contract_name . '/' . $consec . '/');
                    File::makeDirectory($path, 0777, true);
                }
                $url = '/internas/images/' . $company_name . '/' . $contract_name . '/' . $consec . '/';
                move_uploaded_file($file, $carpeta . $namefile);

                InternalController::insert_image($id_obr, $namefile, $url);
            }

            if ($Typedoc[1] == 'pdf') {

                $namefile = $random . '.' . $Typedoc[1];
                $carpeta  = public_path('/public/internas/pdf/' . $company_name . '/' . $contract_name . '/' . $consec . '/');
                if (!File::exists($carpeta)) {
                    $path = public_path('/public/internas/pdf/' . $company_name . '/' . $contract_name . '/' . $consec . '/');
                    File::makeDirectory($path, 0777, true);
                }
                $url = '/internas/pdf/' . $company_name . '/' . $contract_name . '/' . $consec . '/';
                move_uploaded_file($file, $carpeta . $namefile);
                InternalController::insert_image($id_obr, $namefile, $url);
            }

        }

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function insert_image($id_obr, $namefile, $carpeta)
    {

        $insert = DB::table('image_internas')
            ->insert([
                'id_obr'     => $id_obr,
                'name_image' => $namefile,
                'url'        => $carpeta,
            ]);
    }

    public function search_image(Request $request)
    {
        $id_obr = $request->input("id_obr");
        $url1   = $request->input("url");
        $search = DB::table('image_internas')
            ->where('id_obr', '=', $id_obr)
            ->select('name_image', DB::raw("CONCAT('$url1', url,name_image) AS small"), DB::raw("CONCAT('$url1', url,name_image) AS medium"), DB::raw("CONCAT('$url1', url,name_image) AS big"))
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function search_idobr(Request $request)
    {
        $idobr  = $request->input("idobr");
        $search = DB::table('worki')
            ->where('idworkI', '=', $idobr)
            ->select('worki.*')
            ->first();

        return response()->json(['status' => 'ok', 'response' => $search->Fecha_Svc], 200);
    }

    public function movepdf(Request $request)
    {

        $id_obr = $request->input("id_obr");
        $ruta   = $request->input("ruta");

        $company_name  = $request->input("company_name");
        $contract_name = $request->input("contract_name");

        $company_name = str_replace(' ', '', $company_name);

        $carpetanew = public_path('/public/internas/pdf/' . $company_name . '/' . $contract_name . '/' . $id_obr . '/');
        $url        = '/internas/pdf/' . $company_name . '/' . $contract_name . '/' . $id_obr . '/';
        $carpetaold = public_path('/public/' . $ruta);

        $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        $strlength = strlen($characters);

        $random = '';

        for ($i = 0; $i < 15; $i++) {
            $random .= $characters[rand(0, $strlength - 1)];
        }

        $namefile = $random . '.' . 'pdf';

        if (!File::exists($carpetanew)) {

            $carpetanew = public_path('/public/internas/pdf/' . $company_name . '/' . $contract_name . '/' . $id_obr . '/');
            File::makeDirectory($carpetanew, 0777, true);
        }
        File::move($carpetaold, $carpetanew . $namefile);
        InternalController::insert_image($id_obr, $namefile, $url);
        return response()->json(['status' => 'ok'], 200);
    }

}
