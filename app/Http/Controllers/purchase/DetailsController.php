<?php

namespace App\Http\Controllers\purchase;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DetailsController extends Controller
{
    public function create(Request $request){

        
        $this->provider=$request->input("provider");

        $area=json_decode($this->provider);

        foreach ($area as $are)
        {
         print_r($are->ID); // this is your area from json response
         print_r($are->descripcion_compra); // this is your area from json response
        }


        $this->id_company=(int)$request->input("id_company");
        $this->requested_amount=(float)$request->input("requested_amount");
        $this->ceceived_amount=(float)$request->input("ceceived_amount");
        $this->unit_value=(float)$request->input("unit_value");
        $this->discount=(float)$request->input("discount");
        $this->iva=(float)$request->input("iva");



    }
}
