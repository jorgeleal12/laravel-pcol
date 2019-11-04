<?php

namespace App\Http\Controllers\Operation;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OperationController extends Controller
{
    //+

    public function list_tipo()
    {

        $List = DB::table('class_oym')
            ->get();

        return response()->json(['status' => 'ok', 'List' => $List], 200);

    }

    function list(Request $request) {
        $type = $request->input("type");

        $clasificacion = DB::table('descripcion_omy')
            ->where('id_class', '=', $type)
            ->where('id_type', '=', 1)
            ->orderBy('name_descripcion', 'ASC')
            ->get();

        $servicio = DB::table('descripcion_omy')
            ->where('id_class', '=', $type)
            ->where('id_type', '=', 2)
            ->orderBy('name_descripcion', 'ASC')
            ->get();

        $type_net = DB::table('descripcion_omy')
            ->where('id_class', '=', $type)
            ->where('id_type', '=', 3)
            ->orderBy('name_descripcion', 'ASC')
            ->get();

        $causa = DB::table('descripcion_omy')
            ->where('id_class', '=', $type)
            ->where('id_type', '=', 5)
            ->orderBy('name_descripcion', 'ASC')
            ->get();

        $elemento_int = DB::table('descripcion_omy')
            ->where('id_class', '=', $type)
            ->where('id_type', '=', 4)
            ->orderBy('name_descripcion', 'ASC')
            ->get();

        $material = DB::table('descripcion_omy')
            ->where('id_class', '=', $type)
            ->where('id_type', '=', 6)
            ->orderBy('name_descripcion', 'ASC')
            ->get();

        return response()->json(['status' => 'ok', 'clasificacion' => $clasificacion, 'type_net' => $type_net, 'servicio' => $servicio, 'causa' => $causa, 'elemento_int' => $elemento_int, 'material' => $material], 200);
    }

    public function state()
    {
        $search = DB::table('state_oym')
            ->get();
        return response()->json(['status' => 'ok', 'List' => $search], 200);
    }

    public function search_consec(Request $request)
    {

        $consecutive = $request->input("term");
        $company     = $request->input("company");

        $search = DB::table('oym')
            ->where('company', '=', $company)
            ->where('consecutive', 'like', $consecutive . '%')
            ->orderBy('consecutive', 'ASC')
            ->select('oym.consecutive', 'oym.id_oym')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'consecutive' => $search], 200);
    }

    public function search_consec_dispachet(Request $request)
    {

        $consecutive = $request->input("term");
        $company     = $request->input("company");

        $search = DB::table('oym')
            ->leftjoin('class_oym', 'oym.type', '=', 'class_oym.id_classoym')
            ->leftjoin('job_oym', 'oym.type_job', '=', 'job_oym.id_job')
            ->leftjoin('list_activity_oym', 'oym.activity', '=', 'list_activity_oym.id_activity')
            ->where('company', '=', $company)
            ->where('consecutive', 'like', $consecutive . '%')
            ->orderBy('consecutive', 'ASC')
            ->select('oym.consecutive', 'oym.id_oym', 'oym.cod_instalacion as address', 'oym.pedido')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'consecutive' => $search], 200);
    }

    public function search_pedido(Request $request)
    {

        $pedido  = $request->input("term");
        $company = $request->input("company");

        $search = DB::table('oym')
            ->where('company', '=', $company)
            ->where('pedido', 'like', $pedido . '%')
            ->orderBy('pedido', 'ASC')
            ->select('oym.pedido', 'oym.id_oym')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'pedido' => $search], 200);
    }

    public function consec(Request $request)
    {
        $id_oym = $request->input('id_oym');

        $search = DB::table('oym')
            ->where('id_oym', $id_oym)
            ->get();

        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function searchoym(Request $request)
    {

        $id_oym = $request->input('id_oym');

        $search = DB::table('oym')
            ->leftjoin('class_oym', 'oym.type', '=', 'class_oym.id_classoym')
            ->leftjoin('job_oym', 'oym.type_job', '=', 'job_oym.id_job')
            ->leftjoin('list_activity_oym', 'oym.activity', '=', 'list_activity_oym.id_activity')
            ->where('id_oym', $id_oym)
            ->select('oym.*', 'class_oym.*', 'job_oym.*', 'list_activity_oym.*', 'oym.state as state_ant',

                DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=oym.idprogramado) AS nameprogramado"),
                DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=oym.idHace_1) AS nameHace_1"),
                DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=oym.idHace_2) AS nameHace_2"),
                DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=oym.idPlanea) AS namePlanea"),
                DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=oym.idVerifica) AS nameVerifica"),
                DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=oym.idAprueba) AS nameAprueba"))

            ->first();

//si es dia habil
        if ($search->a_c == 1) {

            $fechaEmision    = Carbon::parse($search->date_assignment);
            $fechaExpiracion = Carbon::parse($search->closing_date);

            $diasDiferencia = $fechaExpiracion->diffInDays($fechaEmision);

            $dias = $this->feriado($search->date_assignment, $search->date_expiration, $search->ans);

            $date_close    = $this->feriado($search->closing_date, $search->date_expiration, $diasDiferencia);
            $dias_vencidos = $diasDiferencia - $dias;
        } else {

        }

        return response()->json(['status' => 'ok', 'search' => $search, 'dias' => $search->ans], 200);
    }

    public function update(Request $request)
    {

        $id_oym          = $request->input('data.id_oym');
        $consecutive     = $request->input('data.consecutive');
        $pedido          = $request->input('data.pedido');
        $ot              = $request->input('data.ot');
        $type            = $request->input('data.type');
        $cod_instalacion = $request->input('data.cod_instalacion');
        $user            = $request->input('data.user');
        $address         = $request->input('data.address');
        $phone           = $request->input('data.phone');
        $date_expiration = $request->input('data.date_expiration');
        $date_assignment = $request->input('data.date_assignment');
        $date_arriaval   = $request->input('data.date_arriaval');
        $date_control    = $request->input('data.date_control');
        $type_work       = $request->input('data.type_work');
        $state           = $request->input('data.state');
        $state_ant       = $request->input('data.state_ant');
        $type_job        = $request->input('data.type_job');
        $action          = $request->input('data.action');
        $service         = $request->input('data.service');
        $type_net        = $request->input('data.type_net');
        $cause_event     = $request->input('data.cause_event');
        $activity        = $request->input('data.activity');
        $meter_type_r    = $request->input('data.meter_type_r');
        $meter_type_n    = $request->input('data.meter_type_n');
        $meter_brand_r   = $request->input('data.meter_brand_r');
        $meter_brand_n   = $request->input('data.meter_brand_n');
        $meter_series_r  = $request->input('data.meter_series_r');
        $meter_series_n  = $request->input('data.meter_series_n');
        $meter_reading_r = $request->input('data.meter_reading_r');
        $meter_reading_n = $request->input('data.meter_reading_n');
        $obs             = $request->input('data.obs');
        $company         = $request->input('data.company');
        $idcontract      = $request->input('data.idcontract');
        $municipio       = $request->input('data.municipio');

        $material  = $request->input('data.material');
        $element   = $request->input('data.element');
        $secuencia = $request->input('data.secuencia');

        $Fecha_Prog    = $request->input('data.Fecha_Prog');
        $idprogramado  = $request->input('data.idprogramado');
        $idPlanea      = $request->input('data.idPlanea');
        $f_Planea      = $request->input('data.f_Planea');
        $idHace_1      = $request->input('data.idHace_1');
        $f_Hace_1      = $request->input('data.f_Hace_1');
        $idHace_2      = $request->input('data.idHace_2');
        $f_Hace_2      = $request->input('data.f_Hace_2');
        $idVerifica    = $request->input('data.idVerifica');
        $f_Verifica    = $request->input('data.f_Verifica');
        $idAprueba     = $request->input('data.idAprueba');
        $f_Aprueba     = $request->input('data.f_Aprueba');
        $obscr         = $request->input('data.obscr');
        $closing_date  = $request->input('data.closing_date');
        $type_activity = $request->input('data.type_activity');
        $user          = $request->input('user');

        if ($state_ant == $state) {

        } else {

            $this->historico($state, $consecutive, $ot, $pedido, $type, $obscr, $user, $id_oym);
        }

        $update = DB::table('oym')
            ->where('id_oym', $id_oym)
            ->update([
                'user'            => $user,
                'address'         => $address,
                'phone'           => $phone,
                'date_expiration' => $date_expiration,
                'date_assignment' => $date_assignment,
                'date_arriaval'   => $date_arriaval,
                'date_control'    => $date_control,
                'type_work'       => $type_work,
                'state'           => $state,
                'type_job'        => $type_job,
                'action'          => $action,
                'service'         => $service,
                'type_net'        => $type_net,
                'cause_event'     => $cause_event,
                'activity'        => $activity,
                'meter_type_r'    => $meter_type_r,
                'meter_type_n'    => $meter_type_n,
                'meter_brand_r'   => $meter_brand_r,
                'meter_brand_n'   => $meter_brand_n,
                'meter_series_r'  => $meter_series_r,
                'meter_series_n'  => $meter_series_n,
                'meter_reading_r' => $meter_reading_r,
                'meter_reading_n' => $meter_reading_n,
                'obs'             => $obs,
                'municipio'       => $municipio,
                'material'        => $material,
                'element'         => $element,
                'secuencia'       => $secuencia,
                'Fecha_Prog'      => $Fecha_Prog,
                'idprogramado'    => $idprogramado,
                'idPlanea'        => $idPlanea,
                'f_Planea'        => $f_Planea,
                'idHace_1'        => $idHace_1,
                'f_Hace_1'        => $f_Hace_1,
                'idHace_2'        => $idHace_2,
                'f_Hace_2'        => $f_Hace_2,
                'idVerifica'      => $idVerifica,
                'f_Verifica'      => $f_Verifica,
                'idAprueba'       => $idAprueba,
                'f_Aprueba'       => $f_Aprueba,
                'obscr'           => $obscr,
                'ot'              => $ot,
                'closing_date'    => $closing_date,
                'type_activity'   => $type_activity,
            ]);

        return response()->json(['status' => 'ok', 'search' => true], 200);
    }

    public function historico($state, $consecutive, $ot, $pedido, $type, $obscr, $user, $id_oym)
    {

        $search = DB::table('histo_oym')
            ->where('histo_obr_state', 6)
            ->where('date_clase', null)
            ->first();

        $date = date('Y-m-d h:i:s a', time());

        if (!$search) {

            $insert = DB::table('histo_oym')

                ->insert([
                    'histo_obr_state' => $state,
                    'consecutive'     => $consecutive,
                    'histo_obr_obs'   => $obscr,
                    'histo_obr_user'  => $user,
                    'type'            => $type,
                    'pedido'          => $pedido,
                    'histo_obr_date'  => $date,
                    'date'            => $date,
                    'id_obr'          => $id_oym,
                ]);
        } else {

            $search = DB::table('histo_oym')
                ->where('histo_obr_state', 6)
                ->where('date_clase', null)
                ->update([
                    'date_clase' => $date]);

            $insert = DB::table('histo_oym')

                ->insert([
                    'histo_obr_state' => $state,
                    'consecutive'     => $consecutive,
                    'histo_obr_obs'   => $obscr,
                    'histo_obr_user'  => $user,
                    'type'            => $type,
                    'pedido'          => $pedido,
                    'histo_obr_date'  => $date,
                    'date'            => $date,
                    'id_obr'          => $id_oym,
                ]);
        }

    }

    public function search_items(Request $request)
    {
        $id_oym = $request->input('id_oym');

        $search = DB::table('items_oym')
            ->join('item_cobro', 'items_oym.id_items', '=', 'item_cobro.iditem_cobro')
            ->orderBy('iditems', 'ASC')
            ->where('id_oym', '=', $id_oym)
            ->select('items_oym.*', 'item_cobro.item_cobro_code', 'item_cobro.item_cobro_code as codigo', 'item_cobro.item_cobro_name', 'items_oym.quanity_acta as quantity_acta'

                , 'items_oym.id_items as iditem_cobro')
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function insert(Request $request)
    {

        $id_oym = (int) $request->input("id_oym");

        $data = $request->input("data");

        for ($i = 0; $i < count($data); $i++) {

            $iditems       = isset($data[$i]["iditems"]) ? $data[$i]["iditems"] : 0;
            $codigo        = isset($data[$i]["codigo"]) ? $data[$i]["codigo"] : '';
            $date          = isset($data[$i]["date"]) ? $data[$i]["date"] : 0;
            $date_acta     = isset($data[$i]["date_acta"]) ? $data[$i]["date_acta"] : 0;
            $iditem_cobro  = isset($data[$i]["iditem_cobro"]) ? $data[$i]["iditem_cobro"] : 0;
            $items_state   = isset($data[$i]["id_state"]) ? $data[$i]["id_state"] : 0;
            $quantity      = isset($data[$i]["quantity"]) ? $data[$i]["quantity"] : 0;
            $quantity_acta = isset($data[$i]["quantity_acta"]) ? $data[$i]["quantity_acta"] : '';
            $acta          = isset($data[$i]["acta"]) ? $data[$i]["acta"] : 0;

            if ($iditems == 0 and $codigo != '') {

                $insert = DB::table('items_oym')
                    ->insert([
                        'id_items'     => $iditem_cobro,
                        'date'         => $date,
                        'quantity'     => $quantity,
                        'id_state'     => $items_state,
                        'quanity_acta' => $quantity_acta,
                        'date_acta'    => $date_acta,
                        'acta'         => $acta,
                        'id_oym'       => $id_oym,
                    ]);

            }

            if ($iditems != 0 and $codigo != '') {

                $Update = DB::table('items_oym')
                    ->where('iditems', '=', $iditems)
                    ->update([
                        'id_items'     => $iditem_cobro,
                        'date'         => $date,
                        'quantity'     => $quantity,
                        'id_state'     => $items_state,
                        'quanity_acta' => $quantity_acta,
                        'date_acta'    => $date_acta,
                        'acta'         => $acta,

                    ]);
            }

        }

        $search = OperationController::search($id_oym);

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function search($id_oym)
    {
        $search = DB::table('items_oym')
            ->join('item_cobro', 'items_oym.id_items', '=', 'item_cobro.iditem_cobro')
            ->orderBy('iditems', 'ASC')
            ->where('id_oym', '=', $id_oym)
            ->select('items_oym.*', 'item_cobro.item_cobro_code', 'item_cobro.item_cobro_code as codigo', 'item_cobro.item_cobro_name', 'items_oym.quanity_acta as quantity_acta'

                , 'items_oym.id_items as iditem_cobro')
            ->get();

        return $search;
    }

    public function delete_items(Request $request)
    {
        $id_oym       = (int) $request->input("id_oym");
        $iditem_cobro = (int) $request->input("iditem_cobro");

        $delete = DB::table('items_oym')
            ->where('iditems', '=', $iditem_cobro)
            ->delete();

        $search = OperationController::search($id_oym);

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function insert_material(Request $request)
    {

        $id_oym = (int) $request->input("id_oym");
        $data   = $request->input("data");

        for ($i = 0; $i < count($data); $i++) {

            $id_materiales = isset($data[$i]["id_materiales"]) ? $data[$i]["id_materiales"] : 0;
            $codigo        = isset($data[$i]["codigo"]) ? $data[$i]["codigo"] : '';
            $idmateriales  = isset($data[$i]["id_material"]) ? $data[$i]["id_material"] : 0;
            $date          = isset($data[$i]["date"]) ? $data[$i]["date"] : 0;
            $date_acta     = isset($data[$i]["date_acta"]) ? $data[$i]["date_acta"] : 0;
            $id_state      = isset($data[$i]["id_state"]) ? $data[$i]["id_state"] : 0;
            $quantity      = isset($data[$i]["quantity"]) ? $data[$i]["quantity"] : 0;
            $quantity_acta = isset($data[$i]["quantity_acta"]) ? $data[$i]["quantity_acta"] : 0;
            $acta          = isset($data[$i]["acta"]) ? $data[$i]["acta"] : '';

            if ($id_materiales == 0 and $codigo != '') {

                $insert = DB::table('materiales_oym')
                    ->insert([
                        'id_material'   => $idmateriales,
                        'date'          => $date,
                        'quantity'      => $quantity,
                        'id_state'      => $id_state,
                        'quantity_acta' => $quantity_acta,
                        'date_acta'     => $date_acta,
                        'acta'          => $acta,
                        'id_oym'        => $id_oym,
                    ]);

            } else {

                $update = DB::table('materiales_oym')
                    ->where('id_materiales', '=', $id_materiales)
                    ->update([
                        'id_material'   => $idmateriales,
                        'date'          => $date,
                        'quantity'      => $quantity,
                        'id_state'      => $id_state,
                        'quantity_acta' => $quantity_acta,
                        'date_acta'     => $date_acta,
                        'acta'          => $acta,
                    ]);

            }
        }

        $search = OperationController::search_mate($id_oym);

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function searchmaterial(Request $request)
    {

        $id_oym = (int) $request->input("id_oym");

        $search = OperationController::search_mate($id_oym);

        return response()->json(['status' => 'ok', 'response' => $search], 200);

    }

    public function search_mate($id_oym)
    {

        $search = DB::table('materiales_oym')
            ->join('materiales', 'materiales_oym.id_material', '=', 'materiales.idmateriales')
            ->orderBy('id_material', 'ASC')
            ->where('id_oym', '=', $id_oym)
            ->select('materiales_oym.*', 'materiales.code as codigo', 'materiales.code', 'materiales.description')
            ->get();

        return $search;
    }

    public function search_image(Request $request)
    {

        $id_oym = (int) $request->input("id_oym");
        $url1   = $request->input("url");

        $search = DB::table('images_oym')
            ->where('id_oym', '=', $id_oym)
            ->select('name_image', DB::raw("CONCAT('$url1', url,name_image) AS small"), DB::raw("CONCAT('$url1', url,name_image) AS medium"), DB::raw("CONCAT('$url1', url,name_image) AS big"))
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);

    }

    public function search_acti(Request $request)
    {
        $id_oym = (int) $request->input("id_oym");
        $search = OperationController::search_activity($id_oym);
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function search_activity($id_oym)
    {

        $search = DB::table('activity_oym')
            ->leftjoin('employees', 'employees.idemployees', '=', 'activity_oym.id_employe')
            ->leftjoin('gangs', 'employees.id_gangs', '=', 'gangs.idgangs')
            ->leftjoin('activities', 'activities.idactivities', '=', 'activity_oym.idactivity')
            ->where('id_oym', '=', $id_oym)
            ->orderBy('idactivity_oym', 'ASC')
            ->select('gangs.*', 'activity_oym.acti_date', 'activity_oym.idactivity', 'activity_oym.quantity', 'activity_oym.value', 'activity_oym.id_state', 'activity_oym.date_pay', 'activity_oym.id_employe as idemployee',
                DB::raw('CONCAT(employees.name," ",employees.last_name) AS employee'),
                DB::raw(' ROUND(quantity * value , 2) AS total'),

                'activity_oym.idactivity_oym', 'activities.activities_name as activity', 'activity_oym.idactivity', 'activity_oym.obs')
            ->get();

        return $search;
    }

    public function list_type_activity()
    {

        $search = DB::table('type_activity_oym')
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function search_oym_date(Request $request)
    {

        $id_oym = (int) $request->input("id_oym");
        $search = DB::table('oym')
            ->where('id_oym', $id_oym)
            ->select('oym.f_Hace_1')
            ->first();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function delet_activity(Request $request)
    {
        $id_oym         = (int) $request->input("id_oym");
        $idactivity_oym = (int) $request->input("idactivity_oym");

        $delete = DB::table('activity_oym')
            ->where('idactivity_oym', '=', $idactivity_oym)
            ->delete();

        $search = OperationController::search_activity($id_oym);

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function save_activitys(Request $request)
    {
        $id_oym = (int) $request->input("id_oym");

        $data = $request->input("data");

        for ($i = 0; $i < count($data); $i++) {

            $idactivity_oym = isset($data[$i]["idactivity_oym"]) ? $data[$i]["idactivity_oym"] : null;

            $id_employe = isset($data[$i]["idemployee"]) ? $data[$i]["idemployee"] : null;
            $acti_date  = isset($data[$i]["acti_date"]) ? $data[$i]["acti_date"] : null;
            $id_state   = isset($data[$i]["id_state"]) ? $data[$i]["id_state"] : null;
            $idactivity = isset($data[$i]["idactivity"]) ? $data[$i]["idactivity"] : null;
            $obs        = isset($data[$i]["obs"]) ? $data[$i]["obs"] : null;
            $quantity   = isset($data[$i]["quantity"]) ? $data[$i]["quantity"] : null;
            $value      = isset($data[$i]["value"]) ? $data[$i]["value"] : null;
            $date_pay   = isset($data[$i]["date_pay"]) ? $data[$i]["date_pay"] : null;
            $obs        = mb_strtoupper(isset($data[$i]["obs"]) ? $data[$i]["obs"] : null);

            if ($idactivity_oym == null and $id_employe != null) {

                $insert = DB::table('activity_oym')
                    ->insert([

                        'id_employe' => $id_employe,
                        'id_oym'     => $id_oym,
                        'acti_date'  => $acti_date,
                        'idactivity' => $idactivity,
                        'quantity'   => $quantity,
                        'value'      => $value,
                        'id_state'   => $id_state,
                        'date_pay'   => $date_pay,
                        'obs'        => $obs,
                    ]);
            } else {

                $update = DB::table('activity_oym')
                    ->where('idactivity_oym', '=', $idactivity_oym)
                    ->update([

                        'id_employe' => $id_employe,
                        'acti_date'  => $acti_date,
                        'idactivity' => $idactivity,
                        'quantity'   => $quantity,
                        'value'      => $value,
                        'id_state'   => $id_state,
                        'date_pay'   => $date_pay,
                        'obs'        => $obs,
                    ]);
            }
        }

        $search = OperationController::search_activity($id_oym);

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function imagesend_oym(Request $request)
    {

        // $contract      = $param['contract'];

        $company_name  = 'CONSORCIOC&G';
        $contract_name = 'C0-2018-043';
        $consec        = $_POST['consec'];

        $id_oym = $_POST['id_oym'];

        $company_name = str_replace(' ', '', $company_name);
        $image        = $_FILES;
        $hoy          = date("Y-m-d H:i");

        foreach ($image as &$image) {

            $name = $image['name'];
            $file = $image['tmp_name'];
            $type = $image['type'];

            $Typedoc = explode("/", $type);

            $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

            $strlength = strlen($characters);

            $random       = '';
            $company_name = str_replace(' ', '', $company_name);

            $data = $this->search_data($consec);

            $activity = DB::table('list_activity_oym')
                ->where('id_activity', $data->activity)
                ->first();

            for ($i = 0; $i < 15; $i++) {
                $random .= $characters[rand(0, $strlength - 1)];
            }

            if ($Typedoc[1] == 'jpeg' or $Typedoc[1] == 'jpg') {

                $namefile = $random . '.' . $Typedoc[1];
                $carpeta  = public_path('/public/oym/images/' . $company_name . '/' . $contract_name . '/' . $data->pedido . '-' . $data->ot . '-' . $data->cod_instalacion . '-' . $activity->name_activity . '/');

                if (!File::exists($carpeta)) {

                    $path = public_path('/public/oym/images/' . $company_name . '/' . $contract_name . '/' . $data->pedido . '-' . $data->ot . '-' . $data->cod_instalacion . '-' . $activity->name_activity . '/');
                    File::makeDirectory($path, 0777, true);

                }
                //$img = Image::make($file)->resize(1920, 1080);

                $url = '/oym/images/' . $company_name . '/' . $contract_name . '/' . $data->pedido . '-' . $data->ot . '-' . $data->cod_instalacion . '-' . $activity->name_activity . '/';
                move_uploaded_file($file, $carpeta . $namefile);

                // $this->insert_image_oym($id_oym, $namefile, $url);
            }

            if ($Typedoc[1] == 'pdf') {

                $namefile = $random . '.' . $Typedoc[1];
                $carpeta  = public_path('/public/oym/pdf/' . $company_name . '/' . $contract_name . '/' . $data->pedido . '-' . $data->ot . '-' . $data->cod_instalacion . '-' . $activity->name_activity . '/');

                if (!File::exists($carpeta)) {

                    $path = public_path('/public/oym/pdf/' . $company_name . '/' . $contract_name . '/' . $data->pedido . '-' . $data->ot . '-' . $data->cod_instalacion . '-' . $activity->name_activity . '/');
                    File::makeDirectory($path, 0777, true);

                }
                //$img = Image::make($file)->resize(1920, 1080);

                $url = '/oym/pdf/' . $company_name . '/' . $contract_name . '/' . $data->pedido . '-' . $data->ot . '-' . $data->cod_instalacion . '-' . $activity->name_activity . '/';
                move_uploaded_file($file, $carpeta . $namefile);

                //  $this->insert_image_oym($id_oym, $namefile, $url);
            }

        }

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function search_data($consec)
    {

        $data = DB::table('oym')
            ->where('consecutive', $consec)
            ->first();
        return $data;

    }

    public function insert_image_oym($id_oym, $namefile, $carpeta)
    {

        $insert = DB::table('images_oym')
            ->insert([
                'id_oym'     => $id_oym,
                'name_image' => $namefile,
                'url'        => $carpeta,
            ]);
    }

    public function search_dac(Request $request)
    {
        $id_oym = $request->input("id_oym");

        $search = DB::table('dac_oym')
            ->leftjoin('clasificacion_dacoym', 'dac_oym.id_clasif', '=', 'clasificacion_dacoym.idclasificacion_dac')
            ->leftjoin('motivos_dacoym', 'dac_oym.id_mot', '=', 'motivos_dacoym.idmotivos_dac')
            ->where('dac_idoym', $id_oym)
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);

    }

    public function clasificacion(Request $request)
    {

        $search_clasificacion = DB::table('clasificacion_dacoym')
            ->get();

        return response()->json(['status' => 'ok', 'clasificacion_dac' => $search_clasificacion], 200);
    }

    public function motivos_dac(Request $request)
    {

        $search_clasificacion = DB::table('motivos_dacoym')
            ->get();

        return response()->json(['status' => 'ok', 'motivos_dac' => $search_clasificacion], 200);
    }

    public function save_dac(Request $request)
    {
        $id_oym     = $request->input("id_oym");
        $idcontrato = $request->input("idcontrato");
        $company    = $request->input("company");

        $obs       = $request->input("dac.obs");
        $id_clasif = $request->input("dac.id_clasif");
        $id_mot    = $request->input("dac.id_mot");
        $date      = $request->input("dac.date");

        $inser = DB::table('dac_oym')
            ->insert([
                'obs'         => $obs,
                'id_clasif'   => $id_clasif,
                'id_mot'      => $id_mot,
                'date'        => $date,
                'dac_idoym'   => $id_oym,
                'dac_company' => $company,
                'idcontrato'  => $idcontrato,

            ]);

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function update_dac(Request $request)
    {

        $id_dac    = $request->input("dac.iddac_worki");
        $obs       = $request->input("dac.obs");
        $id_clasif = $request->input("dac.id_clasif");
        $id_mot    = $request->input("dac.id_mot");
        $date      = $request->input("dac.date");

        $update = DB::table('dac_oym')
            ->where('iddac_worki', $id_dac)
            ->update([
                'obs'       => $obs,
                'id_clasif' => $id_clasif,
                'id_mot'    => $id_mot,
                'date'      => $date,
            ]);

        return response()->json(['status' => 'ok', 'response' => true], 200);

    }

    public function search_dacone(Request $request)
    {
        $iddac_worki = $request->input("iddac_worki");

        $search = DB::table('dac_oym')
            ->where('iddac_worki', $iddac_worki)
            ->first();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function feriado($date_ini, $date_end, $dias_counter)
    {

        $fecha_noti = date('Y-m-d', strtotime($date_ini));
        $num_accion = $date_end;

        //2018-08-08
        //2018-08-15

        //Arreglo con todos los feriados
        $feriados = array('2018-08-20',
            '2018-10-15',
            '2018-11-05',
            '2018-11-12',
            '2018-12-08',
            '2018-12-25',
            '2019-01-01',
            '2019-03-25',
            '2019-04-18',
            '2019-04-19',
            '2019-05-01',
            '2019-06-03',
            '2019-06-24',
            '2019-07-01',
            '2019-07-20',
            '2019-08-07',
            '2019-08-19',
            '2019-10-14',
            '2019-11-04',
            '2019-11-11',
            '2019-12-25',
        );
        //Timestamp De Fecha De Comienzo
        $comienzo = strtotime($fecha_noti);

        //Inicializo la Fecha Final
        $fecha_venci_noti = $comienzo;
        //Inicializo El Contador
        //$i = 0; while ($i < 7)
        $dias = 0;

        for ($i = 0; $i < $dias_counter; $i++) {
            //Le Sumo un Dia a La Fecha Final (86400 Segundos)
            $fecha_venci_noti += 86400;
            //Inicializo a FALSE La Variable Para Saber Si Es Feriado
            $es_feriado = false;
            //Recorro Todos Los Feriados
            foreach ($feriados as $key => $feriado) {

                //Verifico Si La Fecha Final Actual Es Feriado O No
                if (date("Y-m-d", $fecha_venci_noti) === date("Y-m-d", strtotime($feriado))) {
                    //En Caso de Ser feriado Cambio Mi variable A TRUE
                    $es_feriado = true;
                }
            }
            //Verifico Que No Sea Un Sabado, Domingo O Feriado
            if (!(date("w", $fecha_venci_noti) == 0 || $es_feriado)) {

                //En Caso De No Ser Sabado, Domingo O Feriado Aumentamos Nuestro contador

            } else {

                //var_dump(date("w", $fecha_venci_noti) . 'festivo');
                $dias++;
                //var_dump(date("w", $fecha_venci_noti).'festivo');
            }

        }
        // var_dump(date("Y-m-d", $fecha_venci_noti));

        return $dias;

    }

    public function search_histo(Request $request)
    {
        $id_oym = $request->input("id_oym");

        $Search = DB::table('histo_oym')
            ->where('id_obr', $id_oym)
            ->leftjoin('oym', 'histo_oym.id_obr', '=', 'oym.id_oym')
            ->leftjoin('state_oym', 'state_oym.id_state', '=', 'histo_oym.histo_obr_state')
            ->select('oym.*', 'histo_oym.*', 'state_oym.*', DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.Users_id_identification=histo_oym.histo_obr_user) AS usuario"))
            ->get();

        return response()->json(['status' => 'ok', 'response' => $Search], 200);
    }

}
