<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    //

    public function search_payment(Request $request)
    {

        $data_ini   = $request->input("data_ini");
        $date_end   = $request->input("date_end");
        $idemployee = $request->input("idemployee");

        $state_data = $request->input("state_data");
        $state_pay  = $request->input("state_pay");
        $company    = $request->input("company");

        $data1 = array();
        $data2 = array();

        for ($i = 0; $i < count($state_data); $i++) {

            $idstate_obr = $state_data[$i]['idstate_obr'];

            $state = isset($state_data[$i]["state"]) ? $state_data[$i]["state"] : 0;

            if ($state != 0) {

                array_push($data1, $idstate_obr);
            }

        }

        for ($i = 0; $i < count($state_pay); $i++) {

            $idstate_activity = $state_pay[$i]['idstate_activity'];

            $state_pay = isset($state_pay[$i]["state_pay"]) ? $state_pay[$i]["state_pay"] : 0;

            if ($state_pay != 0) {

                array_push($data2, $idstate_activity);
            }

        }

        $search = DB::table('worki')
            ->leftjoin('activity_internas', 'activity_internas.id_obr', '=', 'worki.idworkI')
            ->leftjoin('employees', 'activity_internas.id_employe', '=', 'employees.idemployees')
            ->leftjoin('activities', 'activity_internas.idactivity', '=', 'activities.idactivities')
            ->where('employees.idemployees', 'like', '%' . $idemployee)
            ->whereIn('worki_state', $data1)
            ->where('worki.id_company', $company)
            ->whereIn('activity_internas.id_state', $data2)
            ->whereBetween('activity_internas.acti_date', [$data_ini, $date_end])
            ->groupBy('activity_internas.id_employe')
            ->orderBy('employees.name', 'ASC')
            ->select('employees.Users_id_identification', 'activity_internas.id_employe',

                DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=activity_internas.id_employe) AS empleado"),
                DB::raw("ROUND(SUM(activity_internas.quantity * activities.activities_value), 2) as total"))
            ->get();

        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function search_total(Request $request)
    {
        $data_ini   = $request->input("data_ini");
        $date_end   = $request->input("date_end");
        $idemployee = $request->input("idemployee");

        $name       = $request->input("name");
        $state_data = $request->input("state_data");
        $state_pay  = $request->input("state_pay");
        $company    = $request->input("company");

        $data1 = array();
        $data2 = array();

        for ($i = 0; $i < count($state_data); $i++) {

            $idstate_obr = $state_data[$i]['idstate_obr'];

            $state = isset($state_data[$i]["state"]) ? $state_data[$i]["state"] : 0;

            if ($state != 0) {

                array_push($data1, $idstate_obr);
            }

        }

        for ($i = 0; $i < count($state_pay); $i++) {

            $idstate_activity = $state_pay[$i]['idstate_activity'];

            $state_pay = isset($state_pay[$i]["state_pay"]) ? $state_pay[$i]["state_pay"] : 0;

            if ($state_pay != 0) {

                array_push($data2, $idstate_activity);
            }

        }

        $search = DB::table('worki')
            ->leftjoin('activity_internas', 'activity_internas.id_obr', '=', 'worki.idworkI')
            ->leftjoin('tipos_obr_internas', 'tipos_obr_internas.idtipos_obr_internas', '=', 'worki.worki_type_obr')
            ->leftjoin('state_obr', 'state_obr.idstate_obr', '=', 'worki.worki_state')
            ->leftjoin('employees', 'activity_internas.id_employe', '=', 'employees.idemployees')
            ->leftjoin('activities', 'activity_internas.idactivity', '=', 'activities.idactivities')

            ->leftjoin('state_activity', 'state_activity.idstate_activity', '=', 'activity_internas.id_state')
            ->where('activity_internas.id_employe', '=', $idemployee)
            ->whereIn('worki_state', $data1)
            ->where('worki.id_company', $company)
            ->whereIn('activity_internas.id_state', $data2)
            ->whereBetween('activity_internas.acti_date', [$data_ini, $date_end])
            ->orderBy('employees.name', 'ASC')
            ->select('employees.Users_id_identification', 'activity_internas.id_employe', 'activity_internas.quantity', 'activities.activities_value as valuei', 'activities.activities_name', 'worki.consecutive', 'activity_internas.acti_date', 'tipos_obr_internas.tipos_obr_internas_name',

                'state_obr.state_obr_name', 'state_activity.state_activity_name',
                DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=activity_internas.id_employe) AS empleado"),
                DB::raw("ROUND(activity_internas.quantity * activities.activities_value, 2) as total")
            )
            ->get();

        return response()->json(['status' => 'ok', 'search' => $search, 'name' => $name], 200);

    }

    public function pay(Request $request)
    {
        $company = $request->input("company");
        $total   = $request->input("data.total");
        $vpuntos = $request->input("data.vpuntos");
        $opay    = $request->input("data.opay");
        $meta    = $request->input("data.meta");
        $saldo   = $request->input("data.saldo");
        $odesc   = $request->input("data.odesc");
        $datepay = $request->input("data.datepay");
        $desc    = $request->input("data.desc");
        $presta  = $request->input("data.presta");
        $tpay    = $request->input("data.tpay");
        $obs     = $request->input("data.obs");

        $idemployee = $request->input("idemployee");

        $data_ini = $request->input("data_ini");
        $date_end = $request->input("date_end");

        $insert_pay = DB::table('pay_activity')
            ->insertGetId([

                'id_employee' => $idemployee,
                'total'       => $total,
                'meta'        => $meta,
                'vpunto'      => $vpuntos,
                'saldo'       => $saldo,
                'tpay'        => $tpay,
                'opay'        => $opay,
                'odesc'       => $odesc,
                'prestamos'   => $presta,
                'obs'         => $obs,
                'date_pay'    => $datepay,
            ]);

        $update_activity_employee = DB::table('activity_internas')
            ->leftjoin('worki', 'worki.idworkI', '=', 'activity_internas.id_obr')
            ->where('worki.id_company', $company)
            ->where('id_employe', $idemployee)
            ->where('id_state', '!=', 2)
            ->whereBetween('acti_date', [$data_ini, $date_end])
            ->update([
                'date_pay' => $datepay,
                'id_pay'   => $insert_pay,
                'id_state' => 2,
            ]);
        return response()->json(['status' => 'ok', 'response' => $insert_pay], 200);

    }

    public function searchpay(Request $request)
    {
        $idemployee = $request->input("idemployee");

        $company  = $request->input("company");
        $data_ini = $request->input("data_ini");

        $date_end = $request->input("date_end");

        $search_pay = DB::table('pay_activity')
            ->leftjoin('employees', 'pay_activity.id_employee', '=', 'employees.idemployees')
            ->where('pay_activity.id_employee', 'like', '%' . $idemployee)
            ->whereBetween('date_pay', [$data_ini, $date_end])
            ->select('pay_activity.*', 'employees.Users_id_identification', DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=pay_activity.id_employee) AS empleado"))
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search_pay], 200);
    }

    public function search_payupdate(Request $request)
    {

        $idemployee = $request->input("idemployee");
        $company    = $request->input("company");

        $name = $request->input("name");

        $idpay_activity = $request->input("idpay_activity");

        $search = DB::table('worki')
            ->leftjoin('activity_internas', 'activity_internas.id_obr', '=', 'worki.idworkI')
            ->leftjoin('tipos_obr_internas', 'tipos_obr_internas.idtipos_obr_internas', '=', 'worki.worki_type_obr')
            ->leftjoin('state_obr', 'state_obr.idstate_obr', '=', 'worki.worki_state')
            ->leftjoin('employees', 'activity_internas.id_employe', '=', 'employees.idemployees')
            ->leftjoin('activities', 'activity_internas.idactivity', '=', 'activities.idactivities')

            ->leftjoin('state_activity', 'state_activity.idstate_activity', '=', 'activity_internas.id_state')
            ->where('activity_internas.id_employe', '=', $idemployee)
            ->where('activity_internas.id_pay', $idpay_activity)
            ->where('worki.id_company', $company)
            ->orderBy('employees.name', 'ASC')
            ->select('employees.Users_id_identification', 'activity_internas.id_employe', 'activity_internas.quantity', 'activities.activities_value as valuei', 'activities.activities_name', 'worki.consecutive', 'activity_internas.acti_date', 'tipos_obr_internas.tipos_obr_internas_name',

                'state_obr.state_obr_name', 'state_activity.state_activity_name',
                DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=activity_internas.id_employe) AS empleado"),
                DB::raw("ROUND(activity_internas.quantity * activities.activities_value, 2) as total")
            )
            ->get();

        $search_pay = DB::table('pay_activity')
            ->where('id_employee', '=', $idemployee)
            ->where('idpay_activity', '=', $idpay_activity)
            ->select('pay_activity.*', 'pay_activity.vpunto as vpuntos', 'pay_activity.prestamos as presta', 'pay_activity.date_pay as datepay'

                , 'pay_activity.idpay_activity as idpay', 'pay_activity.obs as obs')
            ->first();

        return response()->json(['status' => 'ok', 'search' => $search, 'pay' => $search_pay, 'name' => $name], 200);
    }

    public function payupdate(Request $request)
    {

        $total          = $request->input("data.total");
        $idpay_activity = $request->input("data.idpay_activity");
        $vpuntos        = $request->input("data.vpuntos");
        $opay           = $request->input("data.opay");
        $meta           = $request->input("data.meta");
        $saldo          = $request->input("data.saldo");
        $odesc          = $request->input("data.odesc");
        $datepay        = $request->input("data.datepay");
        $desc           = $request->input("data.desc");
        $presta         = $request->input("data.presta");
        $tpay           = $request->input("data.tpay");
        $obs            = $request->input("data.obs");

        $idemployee = $request->input("idemployee");

        $insert_pay = DB::table('pay_activity')
            ->where('id_employee', '=', $idemployee)
            ->where('idpay_activity', '=', $idpay_activity)
            ->update([
                'total'     => $total,
                'meta'      => $meta,
                'vpunto'    => $vpuntos,
                'saldo'     => $saldo,
                'tpay'      => $tpay,
                'opay'      => $opay,
                'odesc'     => $odesc,
                'prestamos' => $presta,
                'obs'       => $obs,
                'date_pay'  => $datepay,
            ]);
        return response()->json(['status' => 'ok'], 200);
    }
}
