<?php

namespace App\Http\Controllers\NewControllers\administration\certificate;

use App\Http\Controllers\Controller;

// use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CertificateController extends Controller
{
    //

    public function create(Request $request)
    {

        $idNumber_cetificate = $request->input('idNumber_cetificate');
        $idemployees         = $request->input('idemployees');
        $number_ini          = $request->input('number_ini');
        $number_end          = $request->input('number_end');

        $select = DB::table('Number_cetificate')
            ->join('counter_certificate', 'counter_certificate.Number_cetificate_idNumber_cetificate', '=', 'Number_cetificate.idNumber_cetificate')
        // ->where('idemployees', $idemployees)
            ->where('counter_certificate.number_', $number_ini)
            ->get();

        if (count($select) == 0) {
            $insert = DB::table('Number_cetificate')
                ->insertGetid([
                    'idemployees' => $idemployees,
                    'number_ini'  => $number_ini,
                    'number_end'  => $number_end,

                ]);

            for ($i = $number_ini; $i <= $number_end; $i++) {
                $insert_number = DB::table('counter_certificate')
                    ->insert([
                        'number_'                               => $i,
                        'state'                                 => 1,
                        'Number_cetificate_idNumber_cetificate' => $insert,
                    ]);
            }

            return response()->json(['status' => 'ok', 'response' => true], 200);
        }

        return response()->json(['status' => 'ok', 'response' => false], 200);
    }

    public function search()
    {
        $select = DB::table('Number_cetificate')
            ->join('employees', 'employees.idemployees', '=', 'Number_cetificate.idemployees')
            ->select('Number_cetificate.*', DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=Number_cetificate.idemployees) AS employee"))
            ->get();
        return response()->json(['status' => 'ok', 'response' => $select], 200);
    }

    public function delete(Request $request)
    {
        $idNumber_cetificate = $request->input('idNumber_cetificate');
        $idemployees         = $request->input('idemployees');
        $number_ini          = $request->input('number_ini');
        $number_end          = $request->input('number_end');

        $select = DB::table('Number_cetificate')
            ->join('counter_certificate', 'counter_certificate.Number_cetificate_idNumber_cetificate', '=', 'Number_cetificate.idNumber_cetificate')
        // ->where('idemployees', $idemployees)
            ->where('counter_certificate.number_', $number_ini)
            ->get();

        if (count($select) > 0) {

            $select_nuber = DB::table('counter_certificate')
                ->where('Number_cetificate_idNumber_cetificate', $idNumber_cetificate)
                ->get();

            foreach ($select_nuber as $select_nuber) {

                if ($select_nuber->state == 2) {
                    return response()->json(['status' => 'ok', 'response' => true], 200);
                }
            }

            $delete = DB::table('counter_certificate')
                ->where('Number_cetificate_idNumber_cetificate', $idNumber_cetificate)
                ->delete();

            $deletecert = DB::table('Number_cetificate')
                ->where('idNumber_cetificate', $idNumber_cetificate)
                ->delete();
            return response()->json(['status' => 'ok', 'response' => false], 200);

        } else {

            $delete = DB::table('counter_certificate')
                ->where('Number_cetificate_idNumber_cetificate', $idNumber_cetificate)
                ->delete();

            $deletecert = DB::table('Number_cetificate')
                ->where('idNumber_cetificate', $idNumber_cetificate)
                ->delete();
            return response()->json(['status' => 'ok', 'response' => false], 200);
        }

    }
}
