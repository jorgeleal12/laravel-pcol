<?php

namespace App\Http\Controllers\ProgrammingOyM;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgrammingOyMController extends Controller
{
    
	  public function search (Request $request)
    {
     

        $search = DB::table('oym')
            ->leftjoin('state_oym', 'oym.state', 'state_oym.id_state')
            ->leftjoin('list_activity_oym', 'oym.activity', 'list_activity_oym.id_activity')
            ->leftjoin('municipality', 'oym.municipio', 'municipality.id_dane')
            ->where('state',1)
            ->get();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function programar (Request $request)
    {
    	$tabla   	 = $request->input("tabla");
        $fecha 		 = $request->input("fecha");
        $id_employee = $request->input("id_employee");

         for ($i = 0; $i < count($tabla); $i++) {

         	$checkbox = isset($tabla[$i]["checkbox"]) ? $tabla[$i]["checkbox"] : $checkbox = false;
         	$idworkI  = $tabla[$i]["id_oym"];

         	if ($checkbox == true) {

         	$update = DB::table('oym')
           	->where('id_oym',$idworkI)
           	->update([

            	'Fecha_Prog' 	=> $fecha,
            	'idprogramado' 	=> $id_employee,
            	'state' 		=> 3,

            ]);
      	 }
    }
	return response()->json(['status' => 'ok', 'response' => true], 200);
}


}
