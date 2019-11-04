<?php

namespace App\Http\Controllers\Permit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Permicontroller extends Controller
{
    //
    public function save(Request $request)
    {

        $icode             = $request->input("data.icode");
        $imunicipio        = $request->input("data.imunicipio");
        $ipermissionStatus = $request->input("data.ipermissionStatus");
        $ientidad          = $request->input("data.ientidad");
        $idate             = $request->input("data.idate");
        $iradicado         = $request->input("data.iradicado");
        $iresolution       = $request->input("data.iresolution");
        $ifinaldate        = $request->input("data.ifinaldate");
        $istartdate        = $request->input("data.istartdate");
        $iduration         = $request->input("data.iduration");
        $istatus           = $request->input("data.istatus");
        $tcode             = $request->input("data.tcode");
        $tmunicipio        = $request->input("data.tmunicipio");
        $tpermissionStatus = $request->input("data.tpermissionStatus");
        $tentidad          = $request->input("data.tentidad");
        $tdate             = $request->input("data.tdate");
        $tradicado         = $request->input("data.tradicado");
        $tresolution       = $request->input("data.tresolution");
        $tfinaldate        = $request->input("data.tfinaldate");
        $tstartdate        = $request->input("data.tstartdate");
        $tduration         = $request->input("data.tduration");
        $tstatus           = $request->input("data.tstatus");

        $insert = DB::table('permit')

            ->insertGetId([

                'icode'             => $icode,
                'imunicipio'        => $imunicipio,
                'ipermissionStatus' => $ipermissionStatus,
                'ientidad'          => $ientidad,
                'idate'             => $idate,
                'iradicado'         => $iradicado,
                'iresolution'       => $iresolution,
                'ifinaldate'        => $ifinaldate,
                'istartdate'        => $istartdate,
                'iduration'         => $iduration,
                'istatus'           => $istatus,
                'tcode'             => $tcode,
                'tmunicipio'        => $tmunicipio,
                'tpermissionStatus' => $tpermissionStatus,
                'tentidad'          => $tentidad,
                'tdate'             => $tdate,
                'tradicado'         => $tradicado,
                'tresolution'       => $tresolution,
                'tfinaldate'        => $tfinaldate,
                'tstartdate'        => $tstartdate,
                'tduration'         => $tduration,
                'tstatus'           => $tstatus,

            ]);

        return response()->json(['status' => 'ok', 'result' => true], 200);
    }

}
