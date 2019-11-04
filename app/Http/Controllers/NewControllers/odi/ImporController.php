<?php

namespace App\Http\Controllers\NewControllers\odi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImporController extends Controller
{
    //

    public function import(Request $request)
    {

        $data    = $request->input('data');
        $contrat = $request->input('contrat');

        for ($i = 0; $i < count($data); $i++) {

            $cliente       = $data[$i]['N'];
            $cedula        = $data[$i]['E'];
            $addres        = $data[$i]['L'];
            $telefono      = $data[$i]['O'];
            $departamento  = $data[$i]['A'];
            $municipio     = $data[$i]['B'];
            $barrio        = $data[$i]['D'];
            $zona          = $data[$i]['C'];
            $type_obr      = $data[$i]['G'];
            $ot            = $data[$i]['H'];
            $codigo        = $data[$i]['I'];
            $f_asig        = $data[$i]['J'];
            $f_instalacion = $data[$i]['K'];
            $categoria     = $data[$i]['M'];
            $f_vencimiento = $data[$i]['Q'];

            $depart = $this->searc_departamentos($departamento);
            $type   = $this->searc_type($type_obr);
        }
    }

    public function searc_departamentos($departamento)
    {

        $search = DB::table('departments')
            ->where('name_departments', $departamento)
            ->first();

        return $search->departments_dane;

    }

    public function searc_type($type_obr)
    {

        $search = DB::table('service_type')
            ->where('name_type', $type_obr)
            ->first();

        return $search->idservice_type;

    }

}
