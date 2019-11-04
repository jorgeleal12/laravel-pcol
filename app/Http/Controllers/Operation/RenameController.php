<?php

namespace App\Http\Controllers\Operation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RenameController extends Controller
{

    public function __construct(\App\renameoym $pdf)
    {
        $this->pdf = $pdf;
    }

    public function consulta()
    {

        $select = DB::table('images_oym')
            ->get();

        //$select1 = response()->json($select);
        //$ruta = $this->pdf->setNumber($select);
        return $select;
        //var_dump($ruta);
    }

    public function generetepdf(Request $request)
    {

        $id_images  = $request->input('data.id_images');
        $id_oym     = $request->input('data.id_oym');
        $name_image = $request->input('data.name_image');
        $url        = $request->input('data.url');

    }
}
