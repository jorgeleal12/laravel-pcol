<?php

namespace App\Http\Controllers\NewControllers\odi;

use App\Http\Controllers\Controller;
use FCM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class ProgrammingController extends Controller
{
    //

    public function search(Request $request)
    {

        $search = DB::table('odi')
            ->leftjoin('departments', 'departments.departments_dane', '=', 'odi.department_iddepartment')
            ->leftjoin('client', 'client.idclient', '=', 'odi.client')
            ->leftjoin('client_account', 'client_account.idclient_account', '=', 'odi.address')
            ->leftjoin('municipality', 'municipality.idmunicipality', '=', 'client_account.city')
            ->leftjoin('service_type', 'service_type.idservice_type', '=', 'odi.service_type_idservice_type')
            ->leftjoin('type_service', 'type_service.idtype_service', '=', 'odi.type_service_idtype_service')
            ->leftjoin('type_network', 'type_network.idtype_network', '=', 'odi.type_network_idtype_network')
            ->where('odi.state', 1)
            ->select('odi.*', 'client.name_client', 'client_account.address', 'departments.name_departments', 'municipality.name_municipality', 'client_account.number_acount', 'type_service.name_type', 'type_network.name_network', DB::raw('(CASE WHEN odi.priority = "1" THEN "Alta"
                WHEN odi.priority = "2" THEN "Media"

                ELSE "Baja" END) AS prioridad'),
                DB::raw('(CASE WHEN odi.Attention = "1" THEN "Mañana"


                ELSE "Tarde" END) AS atencion'))
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function programming(Request $request)
    {

        $idinspetor       = $request->input('idinspetor');
        $idsupervisor     = $request->input('idsupervisor');
        $data             = $request->input('data');
        $date_programming = date('Y-m-d', strtotime($request->input("date_programming"))) == '1969-12-31' ? null : date('Y-m-d', strtotime($request->input("date_programming")));

        for ($i = 0; $i < count($data); $i++) {
            $state = isset($data[$i]["state1"]) ? $data[$i]["state1"] : null;

            $idsupervisor = isset($data[$i]["idsupervisor"]) ? $data[$i]["idsupervisor"] : null;
            $idinspetor   = isset($data[$i]["idinspetor"]) ? $data[$i]["idinspetor"] : null;
            $idodi        = isset($data[$i]["idinspetor"]) ? $data[$i]["idodi"] : null;

            if ($state != null) {
                $update = DB::table('odi')
                    ->where('idodi', $idodi)
                    ->update([
                        'idsupervisor'     => $idsupervisor,
                        'idinspetor'       => $idinspetor,
                        'date_programming' => $date_programming,
                        'state'            => 2,

                    ]);
            }
        }
        $this->sendmessage($idinspetor, $idsupervisor);

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function sendmessage($idinspetor, $idsupervisor)
    {

        $searchtoken = DB::table('users')
            ->where('id', '1039679695')
            ->first();

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder('Programación');
        $notificationBuilder->setBody('Hay servicios Nuevos')
            ->setSound('default');
        $notificationBuilder
            ->setClickAction("FCM_PLUGIN_ACTIVITY");

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => 'my_data']);

        $option       = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data         = $dataBuilder->build();

        $token = $searchtoken->token;

        $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
    }
}
