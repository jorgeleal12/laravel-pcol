<?php

namespace App\Http\Controllers\NewControllers\administration\competition;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompetitionController extends Controller
{
    //
    public function save(Request $request)
    {
        $idcompetition = $request->input('idcompetition');
        $name          = $request->input('name');
        $type          = $request->input('type');

        if ($type == 1) {

            $insert = DB::table('competition')
                ->insert([
                    'name' => $name,
                ]);

            return response()->json(['status' => 'ok', 'response' => true], 200);
        }
        if ($type == 2) {

            $update = DB::table('competition')
                ->where('idcompetition', $idcompetition)
                ->update([
                    'name' => $name,
                ]);

            return response()->json(['status' => 'ok', 'response' => false], 200);
        }

    }

    public function search()
    {

        $search = DB::table('competition')
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function delete(Request $request)
    {

        $idcompetition = $request->input('idcompetition');

        $delete = DB::table('competition')
            ->where('idcompetition', $idcompetition)
            ->delete();

        return response()->json(['status' => 'ok', 'response' => $delete], 200);

    }
}
