<?php

namespace App\Http\Controllers\Interna;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    //

    public function save_activitys(Request $request)
    {
        $id_obr = (int) $request->input("id_obr");

        $data = $request->input("data");

        for ($i = 0; $i < count($data); $i++) {

            $idactivity_internas = isset($data[$i]["idactivity_internas"]) ? $data[$i]["idactivity_internas"] : 0;

            $id_employe = isset($data[$i]["idemployee"]) ? $data[$i]["idemployee"] : '';
            $acti_date  = isset($data[$i]["acti_date"]) ? $data[$i]["acti_date"] : 0;
            $id_state   = isset($data[$i]["id_state"]) ? $data[$i]["id_state"] : 0;
            $idactivity = isset($data[$i]["idactivity"]) ? $data[$i]["idactivity"] : 0;
            $obs        = isset($data[$i]["obs"]) ? $data[$i]["obs"] : 0;
            $quantity   = isset($data[$i]["quantity"]) ? $data[$i]["quantity"] : 0;
            $value      = isset($data[$i]["value"]) ? $data[$i]["value"] : 0;
            $date_pay   = isset($data[$i]["date_pay"]) ? $data[$i]["date_pay"] : 0;
            $obs        = mb_strtoupper(isset($data[$i]["obs"]) ? $data[$i]["obs"] : '');

            if ($idactivity_internas == 0 and $id_employe != '') {

                $insert = DB::table('activity_internas')
                    ->insert([

                        'id_employe' => $id_employe,
                        'id_obr'     => $id_obr,
                        'acti_date'  => $acti_date,
                        'idactivity' => $idactivity,
                        'quantity'   => $quantity,
                        'value'      => $value,
                        'id_state'   => $id_state,
                        'date_pay'   => $date_pay,
                        'obs'        => $obs,
                    ]);
            } else {

                $update = DB::table('activity_internas')
                    ->where('idactivity_internas', '=', $idactivity_internas)
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

        $search = ActivityController::search_activity($id_obr);

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function search(Request $request)
    {
        $id_obr = (int) $request->input("id_obr");
        $search = ActivityController::search_activity($id_obr);
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function search_activity($id_obr)
    {
        $search = DB::table('activity_internas')
            ->leftjoin('employees', 'employees.idemployees', '=', 'activity_internas.id_employe')
            ->leftjoin('gangs', 'employees.id_gangs', '=', 'gangs.idgangs')
            ->leftjoin('activities', 'activities.idactivities', '=', 'activity_internas.idactivity')
            ->where('id_obr', '=', $id_obr)
            ->orderBy('idactivity_internas', 'ASC')
            ->select('gangs.*', 'activity_internas.acti_date', 'activity_internas.idactivity', 'activity_internas.quantity', 'activity_internas.value', 'activity_internas.id_state', 'activity_internas.date_pay', 'activity_internas.id_employe as idemployee',
                DB::raw('CONCAT(employees.name," ",employees.last_name) AS employee'),
                DB::raw(' ROUND(quantity * value , 2) AS total'),

                'activity_internas.idactivity_internas', 'activities.activities_name as activity', 'activity_internas.idactivity', 'activity_internas.obs')
            ->get();

        return $search;
    }

    public function delete_activitys(Request $request)
    {
        $id_obr              = (int) $request->input("id_obr");
        $idactivity_internas = (int) $request->input("idactivity_internas");

        $delete = DB::table('activity_internas')
            ->where('idactivity_internas', '=', $idactivity_internas)
            ->delete();

        $search = ActivityController::search_activity($id_obr);
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

}
