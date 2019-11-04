<?php

namespace App\Http\Controllers\Importoym;

use App\Http\Controllers\Controller;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportoymController extends Controller
{
    public function check(Request $request)
    {

        $date    = $request->input("date");
        $company = $request->input("company");
        $contrat = $request->input("contrat");
        $data    = $request->input("data");

        $response = ImportoymController::check_data($date, $company, $contrat, $data);

        return $response;
    }

    public function check_data($date, $company, $contrat, $data)
    {

        $data1         = [];
        $servicio_num  = 0;
        $Tipo_num      = 0;
        $actividad_num = 0;
        $municipio_num = 0;

        for ($i = 0; $i < count($data); $i++) {

            $pedido       = isset($data[$i]['A']) ? $data[$i]["A"] : null;
            $servicio     = isset($data[$i]['B']) ? $data[$i]["B"] : null;
            $tipo_trabajo = isset($data[$i]['C']) ? $data[$i]["C"] : null;
            $actividad    = isset($data[$i]['D']) ? $data[$i]["D"] : null;
            $municipio    = isset($data[$i]['E']) ? $data[$i]["E"] : null;
            $cod          = isset($data[$i]['F']) ? $data[$i]["F"] : null;
            $obs          = isset($data[$i]['G']) ? $data[$i]["G"] : null;

            $search_municipio = DB::table('municipality')
                ->where('name_municipality', '=', $municipio)
                ->first();

            $class_oym = DB::table('class_oym')
                ->where('name_class', '=', $servicio)
                ->first();

            $job = DB::table('job_oym')
                ->where('name_job', '=', $tipo_trabajo)
                ->first();

            $activity = DB::table('list_activity_oym')
                ->where('name_activity', '=', $actividad)
                ->first();

            if (!$search_municipio) {
                $municipio_num += 1;
                array_push($data1, array('codigo' => $cod, 'pedido' => $pedido, 'Resultado' => $municipio));
            }

            if (!$class_oym) {
                $servicio_num += 1;
                array_push($data1, array('codigo' => $cod, 'pedido' => $pedido, 'Resultado' => $servicio));
            }

            if (!$job) {
                $Tipo_num += 1;
                array_push($data1, array('codigo' => $cod, 'pedido' => $pedido, 'Resultado' => $tipo_trabajo));
            }

            if (!$activity) {
                $actividad_num += 1;
                array_push($data1, array('codigo' => $cod, 'pedido' => $pedido, 'Resultado' => $actividad));
            }

        }

        return response()->json(['status' => 'ok', 'data' => $data1], 200);

    }

    public function data(Request $request)
    {

        $date    = $request->input("date");
        $company = $request->input("company");
        $contrat = $request->input("contrat");
        $data    = $request->input("data");

        ImportoymController::import($date, $company, $contrat, $data);
    }

    public function import($date, $company, $contrat, $data)
    {

        // $envio = ImportoymController::envio($company, 'ENV');

        $data1 = [];
        $num   = 0;
        for ($i = 0; $i < count($data); $i++) {

            $pedido       = isset($data[$i]['A']) ? $data[$i]["A"] : null;
            $servicio     = isset($data[$i]['B']) ? $data[$i]["B"] : null;
            $tipo_trabajo = isset($data[$i]['C']) ? $data[$i]["C"] : null;
            $actividad    = isset($data[$i]['D']) ? $data[$i]["D"] : null;
            $municipio    = isset($data[$i]['E']) ? $data[$i]["E"] : null;
            $cod          = isset($data[$i]['F']) ? $data[$i]["F"] : null;
            $obs          = isset($data[$i]['G']) ? $data[$i]["G"] : null;
            $phone        = isset($data[$i]['I']) ? $data[$i]["I"] : null;
            $contac       = isset($data[$i]['H']) ? $data[$i]["H"] : null;

            $search_municipio = DB::table('municipality')
                ->where('name_municipality', '=', $municipio)
                ->first();

            $class_oym = DB::table('class_oym')
                ->where('name_class', '=', $servicio)
                ->first();

            $job = DB::table('job_oym')
                ->where('name_job', '=', $tipo_trabajo)
                ->first();

            $activity = DB::table('list_activity_oym')
                ->where('name_activity', '=', $actividad)
                ->first();

            $dia_ans = $activity->ans;

            $id_activity = $activity->id_activity;
            $id_classoym = $class_oym->id_classoym;
            $id_job      = $job->id_job;
            $id_dane     = $search_municipio->id_dane;

            if ($activity->a_c == 1) {

                $dias = $this->feriado($date, $dia_ans);

                $ans = $dias + $dia_ans;

                $date_expiration = date("Y-m-d", strtotime('+' . $ans . ' day', strtotime($date)));

                $dias1 = $this->feriado_vence($date_expiration, 1);

            } else {

                $dias1           = $activity->ans;
                $date_expiration = $date;
            }

            //var_dump($dias);

            $date_expiration1 = date("Y-m-d", strtotime('+' . $dias1 . ' day', strtotime($date_expiration)));

            $documento   = Config::get('Config.oym.documento');
            $consec      = ImportoymController::consec($company, $documento);
            $consecutive = $consec->consecutive;

            $inset = DB::table('oym')
                ->insert([
                    'consecutive'     => $consecutive,
                    'pedido'          => $pedido,
                    'type'            => $id_classoym,
                    'cod_instalacion' => $cod,
                    'user'            => $contac,
                    //'address'            => $contac,
                    'phone'           => $phone,
                    'date_expiration' => $date_expiration1,
                    'date_assignment' => $date,
                    'state'           => 1,
                    'type_job'        => $id_job,
                    'obs'             => $obs,
                    'company'         => $company,
                    'idcontract'      => $contrat,
                    'municipio'       => $id_dane,
                    'activity'        => $id_activity,
                ]);

            ImportoymController::update_consec($consecutive, $company, $documento);
            $num += 1;
        }

        $response = ['status' => 'ok', 'total' => $num];
        echo json_encode($response);
    }

    public function consec($company, $documento)
    {
        $search = DB::table('consecutive')
            ->where('doc', '=', $documento)
            ->where('id_company', '=', $company)
            ->first();

        return $search;
    }

    // funcion para atualizar el consecutivo
    public function update_consec($consecutive, $company, $documento)
    {
        $new_consec = (INT) $consecutive + 1;
        $search     = DB::table('consecutive')
            ->where('doc', '=', $documento)
            ->where('id_company', '=', $company)
            ->update([
                'consecutive' => $new_consec,
            ]);
    }

    public function feriado($date_ini, $dias_counter)
    {

        $fecha_noti = date('Y-m-d', strtotime($date_ini));
        //$num_accion = $date_end;

        //2018-08-08
        //2018-08-15

        //Arreglo con todos los feriados
        $feriados = array('2018-08-20',
            '2018-10-15',
            '2018-11-05',
            '2018-11-12',
            '2018-12-08',
            '2018-12-25',
            '2019-01-01',
            '2019-03-25',
            '2019-04-18',
            '2019-04-19',
            '2019-05-01',
            '2019-06-03',
            '2019-06-24',
            '2019-07-01',
            '2019-07-20',
            '2019-08-07',
            '2019-08-19',
            '2019-10-14',
            '2019-11-04',
            '2019-11-11',
            '2019-12-25',
        );
        //Timestamp De Fecha De Comienzo
        $comienzo = strtotime($fecha_noti);

        //Inicializo la Fecha Final
        $fecha_venci_noti = $comienzo;
        //Inicializo El Contador
        //$i = 0; while ($i < 7)
        $dias = 0;

        for ($i = 0; $i < $dias_counter; $i++) {
            //Le Sumo un Dia a La Fecha Final (86400 Segundos)
            $fecha_venci_noti += 86400;
            //Inicializo a FALSE La Variable Para Saber Si Es Feriado
            $es_feriado = false;
            //Recorro Todos Los Feriados
            foreach ($feriados as $key => $feriado) {

                //Verifico Si La Fecha Final Actual Es Feriado O No
                if (date("Y-m-d", $fecha_venci_noti) === date("Y-m-d", strtotime($feriado))) {
                    //En Caso de Ser feriado Cambio Mi variable A TRUE
                    $es_feriado = true;
                }
            }
            //Verifico Que No Sea Un Sabado, Domingo O Feriado
            if (!(date("w", $fecha_venci_noti) == 0 || $es_feriado)) {

                //En Caso De No Ser Sabado, Domingo O Feriado Aumentamos Nuestro contador

            } else {

                $dias++;
                //var_dump(date("w", $fecha_venci_noti).'festivo');
            }

        }

        return $dias;

    }

    public function feriado_vence($date_ini, $dias_counter)
    {

        $fecha_noti = date('Y-m-d', strtotime($date_ini));
        // $num_accion = $date_end;

        //2018-08-08
        //2018-08-15

        //Arreglo con todos los feriados
        $feriados = array('2018-08-20',
            '2018-10-15',
            '2018-11-05',
            '2018-11-12',
            '2018-12-08',
            '2018-12-25',
            '2019-01-01',
            '2019-03-25',
            '2019-04-18',
            '2019-04-19',
            '2019-05-01',
            '2019-06-03',
            '2019-06-24',
            '2019-07-01',
            '2019-07-20',
            '2019-08-07',
            '2019-08-19',
            '2019-10-14',
            '2019-11-04',
            '2019-11-11',
            '2019-12-25',
        );
        //Timestamp De Fecha De Comienzo
        $comienzo = strtotime($fecha_noti);

        //Inicializo la Fecha Final
        $fecha_venci_noti = $comienzo;
        //Inicializo El Contador
        //$i = 0; while ($i < 7)
        $dias   = 0;
        $prueba = 0;
        for ($i = 0; $i < $dias_counter; $i++) {
            //Le Sumo un Dia a La Fecha Final (86400 Segundos)
            //$fecha_venci_noti += 86400;
            //Inicializo a FALSE La Variable Para Saber Si Es Feriado
            $es_feriado = false;
            //Recorro Todos Los Feriados
            foreach ($feriados as $key => $feriado) {

                //Verifico Si La Fecha Final Actual Es Feriado O No
                if (date("Y-m-d", $fecha_venci_noti) === date("Y-m-d", strtotime($feriado))) {
                    //En Caso de Ser feriado Cambio Mi variable A TRUE
                    $es_feriado = true;

                }
            }
            //Verifico Que No Sea Un Sabado, Domingo O Feriado
            if (!(date("w", $fecha_venci_noti) == 0 || $es_feriado)) {
                //var_dump(date("w", $fecha_venci_noti));
                //En Caso De No Ser Sabado, Domingo O Feriado Aumentamos Nuestro contador
                $prueba++;
            } else {
                //var_dump(date("w", $fecha_venci_noti));
                //var_dump($es_feriado);
                $dias++;
                //var_dump(date("w", $fecha_venci_noti).'festivo');
            }

        }
        //var_dump($prueba, $dias);
        return $dias;

    }

    public function xml()
    {
        $xml = simplexml_load_file("Nomenclatura_Domiciliaria.xml");

        // var_dump($xml);
        foreach ($xml->Document->Folder->Placemark as $nodo) {
            // var_dump($nodo);
            echo $id    = $nodo->ExtendedData->SchemaData->SimpleData[0];
            echo $via   = $nodo->ExtendedData->SchemaData->SimpleData[3];
            echo $placa = $nodo->ExtendedData->SchemaData->SimpleData[4];

            echo $poin = $nodo->Point->coordinates;

            $isert = DB::table('direcciones_medellin')
                ->insert([
                    'via'   => $via,
                    'placa' => $placa,
                    'coor'  => $poin,
                ]);
        }
    }

    public function municipios(Request $request)
    {

        /*      $barrio    = $request->input("barrio");
        $poligono  = $request->input("poligono");
        $poligono1 = $poligono["coordinates"];

        $select = DB::table('barrios')
        ->where('barrio', $barrio)
        ->first();

        if (!$select) {

        $onlyconsonants = str_replace(",", " ", $poligono1);
        $insert         = DB::table('barrios')
        ->insert([
        'poligono' => $onlyconsonants,
        'barrio'   => $barrio,

        ]);
        } else {

        echo 'si';

        }*/

        $point = $request->input("point");

        $select = DB::table('barrios')
            ->get();

        foreach ($select as $selects) {

            $onlyconsonants = str_replace("-", ',-', $selects->poligono);

// $onlyconsonants = str_replace(' ",', '",', $onlyconsonants);

// $onlyconsonants = $onlyconsonants;
            $name     = substr($onlyconsonants, 1);
            $explode5 = explode(",", $name);
//var_dump($explode5);

//$polygon = $selects->poligono;
            // var_dump($explode5);
            $pointLocation = $this->pointInPolygon($point, $explode5);

            if ($pointLocation == 'outside') {

                //  echo 'outside';

            } else {

                return response()->json(['status' => 'ok', 'pointLocation' => $pointLocation, 'barrio' => $selects->barrio], 200);
                break;
            }

        }

        echo 1;

    }
    public function pointInPolygon($point, $polygon, $pointOnVertex = true)
    {
        $this->pointOnVertex = $pointOnVertex;

        // Transformar la cadena de coordenadas en matrices con valores "x" e "y"
        $point    = $this->pointStringToCoordinates($point);
        $vertices = array();

        foreach ($polygon as $vertex) {
            $vertex = trim($vertex);
            //  var_dump($vertex);
            $vertices[] = $this->pointStringToCoordinates($vertex);
        }

        // Checar si el punto se encuentra exactamente en un vértice
        if ($this->pointOnVertex == true and $this->pointOnVertex($point, $vertices) == true) {
            return "vertex";
        }

        // Checar si el punto está adentro del poligono o en el borde
        $intersections  = 0;
        $vertices_count = count($vertices);

        for ($i = 1; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i - 1];
            $vertex2 = $vertices[$i];
            //  var_dump($vertex2);
            if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) {
                // Checar si el punto está en un segmento horizontal

                return "boundary";
            }
            if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) {
                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x'];
                if ($xinters == $point['x']) {
                    // Checar si el punto está en un segmento (otro que horizontal)

                    return "boundary";
                }
                if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {

                    $intersections++;
                }
            }
        }
        // Si el número de intersecciones es impar, el punto está dentro del poligono.
        if ($intersections % 2 != 0) {
            $intersections;
            return "inside";
        } else {
            return "outside";
        }
    }

    public function pointOnVertex($point, $vertices)
    {
        foreach ($vertices as $vertex) {

            if ($point == $vertex) {

                return true;
            }
        }

    }

    public function pointStringToCoordinates($pointString)
    {

        $coordinates = explode(" ", $pointString);

        return array("x" => $coordinates[0], "y" => $coordinates[1]);
    }

}
