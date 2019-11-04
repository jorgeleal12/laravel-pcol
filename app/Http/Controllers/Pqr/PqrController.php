<?php

namespace App\Http\Controllers\Pqr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PqrController extends Controller
{
    public function state_pqr()
    {

        $search = DB::table('state_pqr')
            ->get();

        return response()->json(['status' => 'ok', 'data' => $search], 200);
    }

    public function origin_pqr()
    {
        $search = DB::table('origin_pqr')
            ->get();

        return response()->json(['status' => 'ok', 'data' => $search], 200);
    }

    public function type_pqr()
    {
        $search = DB::table('type_pqr')
            ->get();

        return response()->json(['status' => 'ok', 'data' => $search], 200);
    }

    public function type_queja()
    {
        $search = DB::table('type_queja')
            ->get();

        return response()->json(['status' => 'ok', 'data' => $search], 200);
    }

    public function reason_pqr(Request $request)
    {

        $id_type = (int) $request->input("id_type");
        $search  = DB::table('reason_pqr')
            ->where('id_type', '=', $id_type)
            ->get();

        return response()->json(['status' => 'ok', 'data' => $search], 200);
    }

    public function save_pqr(Request $request)
    {

        $admission_date = $request->input("data.admission_date");
        $identification = $request->input("data.identification");
        $deadline       = $request->input("data.deadline");
        $imputable      = $request->input("data.imputable");
        $consecutive    = $request->input("data.consecutive");
        $applicant      = $request->input("data.applicant");
        $kind           = $request->input("data.kind");
        $state          = $request->input("data.state");
        $pqr_epm        = $request->input("data.pqr_epm");
        $address        = $request->input("data.address");
        $type_complaint = $request->input("data.type_complaint");

        $origin_complaint  = $request->input("data.origin_complaint");
        $reason_pqr        = $request->input("data.reason_pqr");
        $Installation      = $request->input("data.Installation");
        $responsable       = $request->input("data.responsable");
        $Responsable_atid  = $request->input("data.Responsable_atid");
        $Responsable_pqrid = $request->input("data.Responsable_pqrid");
        $Responsable_gesid = $request->input("data.Responsable_gesid");
        $date_attention    = $request->input("data.date_attention");
        $observations      = $request->input("data.observations");
        $answer            = $request->input("data.answer");
        $type              = $request->input("type");
        $save              = DB::table('pqr')
            ->insert([

                'admission_date'    => $admission_date,
                'identification'    => $identification,
                'deadline'          => $deadline,
                'imputable'         => $imputable,
                'consecutive'       => $consecutive,
                'applicant'         => $applicant,
                'kind'              => $kind,
                'state'             => $state,
                'pqr_epm'           => $pqr_epm,
                'address'           => $address,
                'type_complaint'    => $type_complaint,
                'origin_complaint'  => $origin_complaint,
                'reason_pqr'        => $reason_pqr,
                'Installation'      => $Installation,
                'Responsable_atid'  => $Responsable_atid,
                'Responsable_pqrid' => $Responsable_pqrid,
                'Responsable_gesid' => $Responsable_gesid,
                'date_attention'    => $date_attention,
                'observations'      => $observations,
                'answer'            => $answer,
                'type'              => $type,

            ]);

        return response()->json(['status' => 'ok', 'response' => true], 200);

    }

    public function search_pqr(Request $request)
    {

        $id_obr = $request->input("id_obr");
        $type   = $request->input("type");
        if ($type == 1) {
            $search = DB::table('pqr')
                ->leftJoin('worki', 'pqr.consecutive', '=', 'worki.idworkI')
                ->leftJoin('type_queja', 'pqr.kind', '=', 'type_queja.id_type_queja')
                ->leftJoin('state_pqr', 'pqr.state', '=', 'state_pqr.id_state_pqr')
                ->where('pqr.consecutive', '=', $id_obr)
                ->where('pqr.type', '=', $type)
                ->select('pqr.*', 'worki.Direccion as address', 'worki.consecutive as consec', 'state_pqr.state_name', 'type_queja.name_type_q',
                    DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=pqr.Responsable_atid) AS Responsable_at"),
                    DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=pqr.Responsable_pqrid) AS Responsable_pqr"),
                    DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=pqr.Responsable_gesid) AS Responsable_ges"))
                ->get();
        }
        if ($type == 2) {
            $search = DB::table('pqr')

                ->leftJoin('obr_externa', 'pqr.consecutive', '=', 'obr_externa.idobr_externa')
                ->leftJoin('type_queja', 'pqr.kind', '=', 'type_queja.id_type_queja')
                ->leftJoin('state_pqr', 'pqr.state', '=', 'state_pqr.id_state_pqr')
                ->where('pqr.consecutive', '=', $id_obr)
                ->where('pqr.type', '=', $type)
                ->select('pqr.*', 'obr_externa.obr_direccion as address', 'obr_externa.obr_consecutivo as consec', 'state_pqr.state_name', 'type_queja.name_type_q',
                    DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=pqr.Responsable_atid) AS Responsable_at"),
                    DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=pqr.Responsable_pqrid) AS Responsable_pqr"),
                    DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=pqr.Responsable_gesid) AS Responsable_ges"))
                ->get();

        }

        return response()->json(['status' => 'ok', 'response' => $search], 200);

    }

    public function edit(Request $request)
    {

        $idpqr = $request->input("idpqr");
        $type  = $request->input("type");
        if ($type == 1) {
            $search = DB::table('pqr')
                ->leftJoin('worki', 'pqr.consecutive', '=', 'worki.idworkI')
                ->where('pqr.idpqr', '=', $idpqr)
                ->where('pqr.type', '=', $type)
                ->orderBy('pqr.idpqr', 'ASC')
                ->select('pqr.*', 'worki.Direccion as address', 'worki.consecutive as consec',
                    DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=pqr.Responsable_atid) AS Responsable_at"),
                    DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=pqr.Responsable_pqrid) AS Responsable_pqr"),
                    DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=pqr.Responsable_gesid) AS Responsable_ges"))
                ->first();
        }
        if ($type == 2) {

            $search = DB::table('pqr')
                ->leftJoin('obr_externa', 'pqr.consecutive', '=', 'obr_externa.idobr_externa')
                ->where('pqr.idpqr', '=', $idpqr)
                ->where('pqr.type', '=', $type)
                ->orderBy('pqr.idpqr', 'ASC')
                ->select('pqr.*', 'obr_externa.obr_direccion as address', 'obr_externa.obr_consecutivo as consec',
                    DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=pqr.Responsable_atid) AS Responsable_at"),
                    DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=pqr.Responsable_pqrid) AS Responsable_pqr"),
                    DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=pqr.Responsable_gesid) AS Responsable_ges"))
                ->first();
        }
        return response()->json(['status' => 'ok', 'response' => $search], 200);

    }

    public function delete(Request $request)
    {
        $idpqr = $request->input("idpqr");
        $type  = $request->input("type");

        $delete = DB::table('pqr')
            ->where('pqr.idpqr', '=', $idpqr)
            ->where('pqr.type', '=', $type)
            ->delete();
        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function update(Request $request)
    {
        $type = $request->input("type");

        $idpqr          = $request->input("data.idpqr");
        $admission_date = $request->input("data.admission_date");
        $identification = $request->input("data.identification");
        $deadline       = $request->input("data.deadline");
        $imputable      = $request->input("data.imputable");
        $consecutive    = $request->input("data.consecutive");
        $applicant      = $request->input("data.applicant");
        $kind           = $request->input("data.kind");
        $state          = $request->input("data.state");
        $pqr_epm        = $request->input("data.pqr_epm");
        $address        = $request->input("data.address");
        $type_complaint = $request->input("data.type_complaint");

        $origin_complaint  = $request->input("data.origin_complaint");
        $reason_pqr        = $request->input("data.reason_pqr");
        $Installation      = $request->input("data.Installation");
        $responsable       = $request->input("data.responsable");
        $Responsable_atid  = $request->input("data.Responsable_atid");
        $Responsable_pqrid = $request->input("data.Responsable_pqrid");
        $Responsable_gesid = $request->input("data.Responsable_gesid");
        $date_attention    = $request->input("data.date_attention");
        $observations      = $request->input("data.observations");
        $answer            = $request->input("data.answer");

        $save = DB::table('pqr')
            ->where('idpqr', '=', $idpqr)
            ->where('type', '=', $type)
            ->update([

                'admission_date'    => $admission_date,
                'identification'    => $identification,
                'deadline'          => $deadline,
                'imputable'         => $imputable,
                'consecutive'       => $consecutive,
                'applicant'         => $applicant,
                'kind'              => $kind,
                'state'             => $state,
                'pqr_epm'           => $pqr_epm,
                'address'           => $address,
                'type_complaint'    => $type_complaint,
                'origin_complaint'  => $origin_complaint,
                'reason_pqr'        => $reason_pqr,
                'Installation'      => $Installation,
                'Responsable_atid'  => $Responsable_atid,
                'Responsable_pqrid' => $Responsable_pqrid,
                'Responsable_gesid' => $Responsable_gesid,
                'date_attention'    => $date_attention,
                'observations'      => $observations,
                'answer'            => $answer,

            ]);

        return response()->json(['status' => 'ok', 'response' => true], 200);
    }

    public function search_obr(Request $request)
    {

        $idobr = $request->input("idobr");

        $search = DB::table('worki')
            ->where('idworkI', '=', $idobr)
            ->select('worki.consecutive as consec', 'worki.Direccion as address', 'worki.Solicitante as applicant', 'worki.Instalacion as Installation', 'worki.idworkI as consecutive')
            ->first();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function search_externas(Request $request)
    {
        $idobr = $request->input("idobr");

        $search = DB::table('obr_externa')
            ->where('idobr_externa', '=', $idobr)
            ->select('obr_consecutivo as consec', 'obr_direccion as address', 'idobr_externa as consecutive')
            ->first();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }
}
