<?php

namespace App\Http\Controllers\Cambiode;
use Illuminate\Support\Facades\DB;
use File;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class imagenes extends Controller
{
    public function imagenes()
    {

        $consecutivos = array(
            '1',
            '2'

        );
      
   

        foreach ($consecutivos as $key => $conse) {
//echo  $conse;

            $search = DB::table('images_oym')
                ->where('id_oym', $conse)
                ->first();
                 $carpeta  = public_path('public/');
                  $carpeta1  = public_path('public'.$search->url);

                  $old = str_replace("\\","/",public_path('public'));
                  echo $carpeta  = $old .$search->url;
                  echo  $carpeta1  = $old .'/acta1';

            
                if (!File::exists($carpeta1)) {

                    //File::makeDirectory($carpeta1, 0777, true);

                  
                }

                move_uploaded_file($carpeta.'/oym/images/CONSORCIOC&G/C0-2018-043/1/CEx9Q8621EblVOg.jpg' ,$carpeta.'acta/oym/images/CONSORCIOC&G/C0-2018-043/1/CEx9Q8621EblVOg.jpg'); 
         
        }

      //  $filetopath=$public_dir.'/'.$zipFileName;
        //

        //  $carpeta  = public_path('public/oym/images/');
        //  $archivo='CONSORCIOCYG/C0-2018-043/1/';
        //  $new_path='CONSORCIOCYG/copia';
        //  $move = File::move($carpeta.$archivo , $carpeta.$new_path);

    }
}
