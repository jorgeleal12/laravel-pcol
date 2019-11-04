<?php

namespace App\Http\Controllers\Receivedow;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReceivedowController extends Controller
{
    
	public function create (Request $request)
    {

			$income_move 			 = $request->input("data.income_move");
			$consecutive_purc 		 = $request->input("data.consecutive_purc");
			$purchases_cellar 		 = $request->input("data.");
			$providers_name 		 = $request->input("data.providers_name");
			$purchases_date 		 = $request->input("data.purchases_date");
			$purchases_deliver_date  = $request->input("data.purchases_deliver_date");
			$income_invoice 		 = $request->input("data.income_invoice");
			$income_conse 			 = $request->input("data.income_conse");
			$income_remission 		 = $request->input("data.income_remission");
			$purchases_state_purc 	 = $request->input("data.purchases_state_purc");
			$ordername		  		 = $request->input("data.nombredelpedido");
			$audit_order_date  		 = $request->input("data.fechapedidointerventoria");
			$ow_processing_date  	 = $request->input("data.fechadeelaboracionow");
			$ow_response_time  		 = $request->input("data.tiempoderespuestaow");
			$provider_response  	 = $request->input("data.respuestadelproveedor");
			$time_provider_response  = $request->input("data.tiempoderespuestadelproveedor");
			$authorization_date  	 = $request->input("data.fechadeautorizacion");
			$batch_number  	  		 = $request->input("data.numerodebatch"); 
			$response_time_epm  	 = $request->input("data.tiempoderespuestaepm");
			$income_observations 	 = $request->input("data.income_observations");




        $create = DB::table('receivedOw')
            ->insert([
               
            
				'income_move'				=>	$income_move,
				'consecutive_purc'			=>	$consecutive_purc,
				'purchases_cellar'			=>	$purchases_cellar,
				'providers_name'			=>	$providers_name,
				'purchases_date'			=>	$purchases_date,
				'purchases_deliver_date'	=>	$purchases_deliver_date,
				'income_invoice'			=>	$income_invoice,
				'income_conse'				=>	$income_conse,
				'income_remission'			=>	$income_remission,
				'purchases_state_purc'		=>	$purchases_state_purc,
                'ordername'					=>	$ordername,
                'audit_order_date'			=>	$audit_order_date,
                'ow_processing_date'		=>	$ow_processing_date,
                'ow_response_time'			=>	$ow_response_time,
                'provider_response'			=>	$provider_response,
                'time_provider_response'	=>	$time_provider_response,
                'authorization_date'		=>	$authorization_date,
                'batch_number'				=>	$batch_number,
                'response_time_epm'			=>	$response_time_epm,
                'income_observations'		=>	$income_observations,

            ]);
    }

}
