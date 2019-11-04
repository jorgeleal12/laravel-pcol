<?php

namespace App\Http\Controllers\Activities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivitiesController extends Controller
{
    public function save(Request $request)
    {

        $activities_name  = (String) $request->input("data.activities_name");
        $activities_state = (int) $request->input("data.activities_state");
        $activities_type  = (int) $request->input("data.activities_type");
        $activities_value = (float) mb_strtoupper($request->input("data.activities_value"));

        $idcontract = (int) $request->input("idcontract");
        try {
            $insert = DB::table('activities')
                ->insert([
                    'activities_contrac' => $idcontract,
                    'activities_name'    => $activities_name,
                    'activities_state'   => $activities_state,
                    'activities_type'    => $activities_type,
                    'activities_value'   => $activities_value,
                ]);
            $response = true;
        } catch (\Exception $e) {
            $response = false;
        }

        return response()->json(['status' => 'ok', 'response' => $response], 200);
    }

    public function search(Request $request)
    {
        $contrac = (int) $request->input("contrac");
        $type    = (int) $request->input("type");

        $search = DB::table('activities')
            ->where('activities_contrac', '=', $contrac)
            ->where('activities_type', '=', $type)
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function update(Request $request)
    {

        $idactivities     = (int) $request->input("idactivities");
        $activities_name  = mb_strtoupper($request->input("activities_name"));
        $activities_state = (int) $request->input("activities_state");
        $activities_type  = (int) $request->input("activities_type");
        $activities_value = (float) $request->input("activities_value");

        try {
            $update = DB::table('activities')
                ->where('idactivities', '=', $idactivities)
                ->update([
                    'activities_name'  => $activities_name,
                    'activities_state' => $activities_state,
                    'activities_type'  => $activities_type,
                    'activities_value' => $activities_value,

                ]);
            $response = true;
        } catch (\Exception $e) {
            $response = false;
        }
        return response()->json(['status' => 'ok', 'response' => $response], 200);
    }

    public function delete(Request $request)
    {
        $idactivities = (int) $request->input("idactivities");

        try {
            $delete = DB::table('activities')
                ->where('idactivities', '=', $idactivities)
                ->delete();
            $response = true;
        } catch (\Exception $e) {
            $response = false;

        }
        return response()->json(['status' => 'ok', 'response' => $response], 200);
    }

    public function autocomple(Request $request)
    {
        $term               = $request->input("term");
        $type               = $request->input("type");
        $activities_contrac = $request->input("contract");

        $search = DB::table('activities')
            ->where('activities_contrac', '=', $activities_contrac)
            ->where('activities_type', '=', $type)
            ->where('activities_name', 'like', '%' . $term . '%')
            ->select('activities.*')
            ->take(10)
            ->get();
        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }
}
