<?php

namespace App\Http\Controllers\Importobr;

use App\Http\Controllers\Controller;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jcf\Geocode\Geocode;

class ImportObrController extends Controller
{
    public $barrios;

    public function check(Request $request)
    {

        $date    = $request->input("date");
        $company = $request->input("company");
        $contrat = $request->input("contrat");
        $data    = $request->input("data");

        $response = ImportObrController::check_data($date, $company, $contrat, $data);

        return $response;
    }

    public function check_data($date, $company, $contrat, $data)
    {

        $data1             = [];
        $numero_municipios = 0;
        $numero_typeobr    = 0;
        for ($i = 0; $i < count($data); $i++) {

            $codigo       = isset($data[$i]['C']) ? $data[$i]["C"] : null;
            $dcliente     = isset($data[$i]['F']) ? $data[$i]["F"] : null;
            $direccion    = isset($data[$i]['D']) ? $data[$i]["D"] : null;
            $estrato      = isset($data[$i]['E']) ? $data[$i]["E"] : null;
            $item1        = isset($data[$i]['M']) ? $data[$i]["M"] : null;
            $item2        = isset($data[$i]['N']) ? $data[$i]["N"] : null;
            $item3        = isset($data[$i]['O']) ? $data[$i]["O"] : null;
            $item4        = isset($data[$i]['P']) ? $data[$i]["P"] : null;
            $item5        = isset($data[$i]['Q']) ? $data[$i]["Q"] : null;
            $municipio    = isset($data[$i]['J']) ? $data[$i]["J"] : null;
            $ncliente     = isset($data[$i]['G']) ? $data[$i]["G"] : null;
            $obs          = isset($data[$i]['L']) ? $data[$i]["L"] : null;
            $ot           = isset($data[$i]['B']) ? $data[$i]["B"] : null;
            $pedido       = isset($data[$i]['A']) ? $data[$i]["A"] : null;
            $sub_t_obr    = isset($data[$i]['R']) ? $data[$i]["R"] : null;
            $tel_cliente  = isset($data[$i]['H']) ? $data[$i]["H"] : null;
            $tel_contacto = isset($data[$i]['I']) ? $data[$i]["I"] : null;
            $valvula      = isset($data[$i]['K']) ? $data[$i]["K"] : null;
            $zona         = isset($data[$i]['S']) ? $data[$i]["S"] : null;
            $x            = isset($data[$i]["T"]) ? $data[$i]["T"] : null;
            $y            = isset($data[$i]["U"]) ? $data[$i]["U"] : null;
            $lat          = isset($data[$i]["lat"]) ? $data[$i]["lat"] : null;
            $lng          = isset($data[$i]["lng"]) ? $data[$i]["lng"] : null;

            $search_municipio = DB::table('municipality')
                ->where('id_dane', '=', $municipio)
                ->first();

            $search_idobr = DB::table('subtipo_obr_internas')
                ->where('idsubtipo_obr_internas', '=', $sub_t_obr)
                ->first();

            $obr = '';
            if (!$search_municipio) {
                $numero_municipios += 1;
                $municipio = 'municipios';
                array_push($data1, array('codigo' => $codigo, 'pedido' => $pedido, 'ot' => $ot, 'municipio' => $municipio, 'obr' => $obr));
            }

            $municipio = '';
            if (!$search_idobr) {
                $numero_typeobr += 1;
                $obr = 'Sub tipo de obra';
                array_push($data1, array('codigo' => $codigo, 'pedido' => $pedido, 'ot' => $ot, 'municipio' => $municipio, 'obr' => $obr));
            }

        }

        return response()->json(['status' => 'ok', 'numero_typeobr' => $numero_typeobr, 'numero_municipios' => $numero_municipios, 'data' => $data1], 200);

    }

    public function data(Request $request)
    {

        $date    = $request->input("date");
        $company = $request->input("company");
        $contrat = $request->input("contrat");
        $data    = $request->input("data");

        ImportObrController::import($date, $company, $contrat, $data);
    }

    public function import($date, $company, $contrat, $data)
    {

        $envio = ImportObrController::envio($company, 'ENV');

        $data1 = [];
        for ($i = 0; $i < count($data); $i++) {

            $codigo       = isset($data[$i]['C']) ? $data[$i]["C"] : null;
            $dcliente     = isset($data[$i]['F']) ? $data[$i]["F"] : null;
            $direccion    = isset($data[$i]['D']) ? $data[$i]["D"] : null;
            $estrato      = isset($data[$i]['E']) ? $data[$i]["E"] : null;
            $item1        = isset($data[$i]['M']) ? $data[$i]["M"] : null;
            $item2        = isset($data[$i]['N']) ? $data[$i]["N"] : null;
            $item3        = isset($data[$i]['O']) ? $data[$i]["O"] : null;
            $item4        = isset($data[$i]['P']) ? $data[$i]["P"] : null;
            $item5        = isset($data[$i]['Q']) ? $data[$i]["Q"] : null;
            $municipio    = isset($data[$i]['J']) ? $data[$i]["J"] : null;
            $ncliente     = isset($data[$i]['G']) ? $data[$i]["G"] : null;
            $obs          = isset($data[$i]['L']) ? $data[$i]["L"] : null;
            $ot           = isset($data[$i]['B']) ? $data[$i]["B"] : null;
            $pedido       = isset($data[$i]['A']) ? $data[$i]["A"] : null;
            $sub_t_obr    = isset($data[$i]['R']) ? $data[$i]["R"] : null;
            $tel_cliente  = isset($data[$i]['H']) ? $data[$i]["H"] : null;
            $tel_contacto = isset($data[$i]['I']) ? $data[$i]["I"] : null;
            $valvula      = isset($data[$i]['K']) ? $data[$i]["K"] : null;
            $zona         = isset($data[$i]['S']) ? $data[$i]["S"] : null;
            $x            = isset($data[$i]["T"]) ? $data[$i]["T"] : null;
            $y            = isset($data[$i]["U"]) ? $data[$i]["U"] : null;
            $lat          = isset($data[$i]["lat"]) ? $data[$i]["lat"] : null;
            $lng          = isset($data[$i]["lng"]) ? $data[$i]["lng"] : null;
            $items        = [];

            if ($pedido != null) {

                array_push($items, array('item' => $item1, 'company' => $company, 'contrat' => $contrat));
                array_push($items, array('item' => $item2, 'company' => $company, 'contrat' => $contrat));
                array_push($items, array('item' => $item3, 'company' => $company, 'contrat' => $contrat));
                array_push($items, array('item' => $item4, 'company' => $company, 'contrat' => $contrat));
                array_push($items, array('item' => $item5, 'company' => $company, 'contrat' => $contrat));

                $response_obr = ImportObrController::search_obr($pedido, $codigo, $company, $contrat);

                $state = isset($response_obr->worki_state) ? $response_obr->worki_state : 0;

                $response_TypeObr = ImportObrController::TypeObr($sub_t_obr);

                //id de la obra
                $id_obr = isset($response_obr->idworkI) ? $response_obr->idworkI : '';

                // fecha de vencimiento
                $date_expiration = date("Y-m-d", strtotime('+' . $response_TypeObr->ans . ' day', strtotime($date)));

                //sub estado de la ot
                $sub_state = $response_TypeObr->sub_state;

                //tipo de obra del pedido
                $type_obr = $response_TypeObr->id_tipo;

                //prioridad del pedido para montarlo al sistema
                $priority = $response_TypeObr->priority;

                if ($state == 3) {

                    $update = ImportObrController::update_obr($company, $contrat, $codigo, $dcliente, $direccion, $estrato, $municipio, $ncliente, $obs, $pedido, $sub_t_obr, $tel_cliente, $tel_contacto, $valvula, $type_obr, $date, $date_expiration, $ot, $sub_state, $id_obr, $envio, $x, $y);

                    $sub_state = 17;
                    ImportObrController::UpdateOt($id_obr, $ot, $sub_t_obr, $date, $sub_state, $envio, $items);
                    continue 1;
                }

                if ($state != 0) {

                    $search_ot = ImportObrController::SearchOt($id_obr, $ot);

                    $sub_estado = isset($search_ot->sub_estado) ? $search_ot->sub_estado : 0;

                    if ($sub_estado == 7 or $sub_estado == 8) {

                        $sub_state = 17;
                        ImportObrController::UpdateOt($id_obr, $ot, $sub_t_obr, $date, $sub_state, $envio, $items);
                        continue 1;
                    }

                }

                //si el tipo de obra tiene prioridad 1
                if ($priority == 1) {

                    $InserObr = ImportObrController::InsertObr($company, $contrat, $codigo, $dcliente, $direccion, $estrato, $municipio, $ncliente, $obs, $pedido, $sub_t_obr, $tel_cliente, $tel_contacto, $valvula, $type_obr, $date, $date_expiration, $ot, $sub_state, $envio, $items, $zona, $x, $y, $lng, $lat);
                }

                if ($priority == 2 and $state != 0) {

                    $update = ImportObrController::update_obr($company, $contrat, $codigo, $dcliente, $direccion, $estrato, $municipio, $ncliente, $obs, $pedido, $sub_t_obr, $tel_cliente, $tel_contacto, $valvula, $type_obr, $date, $date_expiration, $ot, $sub_state, $id_obr, $envio, $x, $y);

                    ImportObrController::InsertOt($id_obr, $ot, $sub_t_obr, $date, $sub_state, $envio, $items);
                }

                if ($priority != 1 && $state == 0) {

                    array_push($data1, array(
                        'C'   => $codigo,
                        'F'   => $dcliente,
                        'D'   => $direccion,
                        'E'   => $estrato,
                        'M'   => $item1,
                        'N'   => $item2,
                        'O'   => $item3,
                        'P'   => $item4,
                        'Q'   => $item5,
                        'J'   => $municipio,
                        'G'   => $ncliente,
                        'L'   => $obs,
                        'B'   => $ot,
                        'A'   => $pedido,
                        'R'   => $sub_t_obr,
                        'H'   => $tel_cliente,
                        'I'   => $tel_contacto,
                        'K'   => $valvula,
                        'S'   => $zona,
                        'T'   => $x,
                        'U'   => $y,
                        'lng' => $lng,
                        'lat' => $lat,

                    ));
                }

            } else {
                $response_TypeObr = ImportObrController::TypeObr($sub_t_obr);
                $date_expiration  = date("Y-m-d", strtotime('+' . $response_TypeObr->ans . ' day', strtotime($date)));
                //sub estado de la ot
                $sub_state = $response_TypeObr->sub_state;

                //tipo de obra del pedido
                $type_obr = $response_TypeObr->id_tipo;

                //prioridad del pedido para montarlo al sistema
                $priority = $response_TypeObr->priority;

                $InserObr = ImportObrController::InsertObrper($company, $contrat, $codigo, $dcliente, $direccion, $estrato, $municipio, $ncliente, $obs, $pedido, $sub_t_obr, $tel_cliente, $tel_contacto, $valvula, $type_obr, $date, $date_expiration, $ot, $sub_state, $envio, $zona, $x, $y, $lng, $lat);

            }
        }

        if (count($data1) > 1) {

            ImportObrController::import($date, $company, $contrat, $data1);

        } else {

            ImportObrController::update_envio($company, 'ENV', $envio);
            $loader        = true;
            $result        = ImportObrController::search_result($envio);
            echo $response = json_encode(['status' => 'ok', 'loader' => $loader, 'result' => $result], 200);

        }

    }

// funcion para saber la prioridad y tomar una decicion si se crea un nuevo consecutivo o se atualiza uno existente
    public function TypeObr($sub_t_obr)
    {
        $serch = DB::table('subtipo_obr_internas')
            ->where('idsubtipo_obr_internas', '=', $sub_t_obr)
            ->select('subtipo_obr_internas.priority', 'subtipo_obr_internas.ans', 'subtipo_obr_internas.id_tipo', 'subtipo_obr_internas.sub_state')
            ->first();
        return $serch;
    }

// funcion para buscar en que estado esta la obra y definir paso a seguir
    public function search_obr($pedido, $codigo, $company, $contrat)
    {

        $search = DB::table('worki')
            ->where('Pedido', '=', $pedido)
            ->where('Instalacion', '=', $codigo)
            ->where('id_company', '=', $company)
            ->where('idcontrato', '=', $contrat)
            ->select('worki.worki_state', 'worki.idworkI', 'worki.worki_type_obr')
            ->first();

        return $search;
    }

// funcion para crear una obra nueva
    public function InsertObr($company, $contrat, $codigo, $dcliente, $direccion, $estrato, $municipio, $ncliente, $obs, $pedido, $sub_t_obr, $tel_cliente, $tel_contacto, $valvula, $id_tipo, $date, $date_expiration, $ot, $sub_state, $envio, $items, $zona, $x, $y, $lng, $lat)
    {

        $documento   = Config::get('Config.internas.documento');
        $consec      = ImportObrController::consec($company, $documento);
        $consecutive = $consec->consecutive;
        $Cocineta    = 0;

        if ($estrato <= 3) {

            $Cocineta = 1;
        }
/*
while ($municipio == 5001) {
echo $coor = $this->adress_medellin($direccion);

$str1 = explode(",", $coor);

$lat = $str1[1];
$lng = $str1[0];

$number  = 2152;
$numberi = 0;
$sum     = $number + $numberi;

$this->barrios = DB::table('barrios')
->where('id_barrio', $sum)
->first();

$onlyconsonants = str_replace("-", ',-', $this->barrios->poligono);

// $onlyconsonants = str_replace(' ",', '",', $onlyconsonants);

// $onlyconsonants = $onlyconsonants;
$name     = substr($onlyconsonants, 1);
$explode5 = explode(",", $name);

$coor          = str_replace(",", " ", $coor);
$pointLocation = $this->pointInPolygon($coor, $explode5);

if ($pointLocation == 'outside') {

continue;
} else {

$zona1 = $selects->barrio;
break;
}

}*/

        $name_municipios = $this->municipio($municipio);

        if ($municipio == 5001) {
            $barrio = null;
            $findme = 'RURAL';

            $pos = strpos($direccion, $findme);

            if ($pos === false) {

                $coor = $this->adress_medellin($direccion);
                $str1 = explode(",", $coor);

                $lat = $str1[1];
                $lng = $str1[0];

                $point = str_replace(',', ' ', $coor);

                if ($lat != 0) {

                    if (!$this->barrios) {

                        $this->barrios = DB::table('barrios')
                            ->get();

                    }

                    foreach ($this->barrios as $selects) {

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

                            $barrio = $selects->barrio;
                            $zona   = $selects->zona;

                            //return response()->json(['status' => 'ok', 'pointLocation' => $pointLocation, 'barrio' => $selects->barrio], 200);
                            break;
                        }

                    }

                } else {
                    $zona   = $name_municipios;
                    $barrio = $name_municipios;
                }
            } else {

                $zona   = $name_municipios;
                $barrio = $name_municipios;

            }
        } else {

            $barrio = $zona;
        }

        $insert = DB::table('worki')
            ->insertGetId([
                'Pedido'         => $pedido,
                'Instalacion'    => $codigo,
                'consecutive'    => $consecutive,
                'Direccion'      => $direccion,
                'Solicitante'    => $ncliente,
                'Cedula'         => $dcliente,
                'Telefono'       => $tel_cliente,
                'Tel_Contacto'   => $tel_contacto,
                'Municipio'      => $municipio,
                'idcontrato'     => $contrat,
                'id_company'     => $company,
                'worki_type_obr' => $id_tipo,
                'worki_state'    => 7,
                'Estrato'        => $estrato,
                'Vencimiento'    => $date_expiration,
                'Barrio'         => $barrio,
                'Zona'           => $zona,
                'Atualizacion'   => $date,
                'Cocineta'       => $Cocineta,
                'Obs_Pedido'     => $obs,
                'Fecha_Estado'   => $date,
                'envio'          => $envio,
                'x'              => $x,
                'y'              => $y,
                'lng'            => $lng,
                'lat'            => $lat,
                'sub_t_obr'      => $sub_t_obr,

            ]);

        ImportObrController::update_consec($consecutive, $company, $documento);

        ImportObrController::InsertOt($insert, $ot, $sub_t_obr, $date, $sub_state, $envio, $items);
    }

// funcion para crear una obra nueva
    public function InsertObrper($company, $contrat, $codigo, $dcliente, $direccion, $estrato, $municipio, $ncliente, $obs, $pedido, $sub_t_obr, $tel_cliente, $tel_contacto, $valvula, $id_tipo, $date, $date_expiration, $ot, $sub_state, $envio, $zona, $x, $y, $lng, $lat)
    {

        $documento   = Config::get('Config.internas.documento');
        $consec      = ImportObrController::consec($company, $documento);
        $consecutive = $consec->consecutive;
        $Cocineta    = 0;

        if ($estrato <= 3) {

            $Cocineta = 1;
        }
/*
while ($municipio == 5001) {
echo $coor = $this->adress_medellin($direccion);

$str1 = explode(",", $coor);

$lat = $str1[1];
$lng = $str1[0];

$number  = 2152;
$numberi = 0;
$sum     = $number + $numberi;

$this->barrios = DB::table('barrios')
->where('id_barrio', $sum)
->first();

$onlyconsonants = str_replace("-", ',-', $this->barrios->poligono);

// $onlyconsonants = str_replace(' ",', '",', $onlyconsonants);

// $onlyconsonants = $onlyconsonants;
$name     = substr($onlyconsonants, 1);
$explode5 = explode(",", $name);

$coor          = str_replace(",", " ", $coor);
$pointLocation = $this->pointInPolygon($coor, $explode5);

if ($pointLocation == 'outside') {

continue;
} else {

$zona1 = $selects->barrio;
break;
}

}*/

        $name_municipios = $this->municipio($municipio);

        if ($municipio == 5001) {
            $barrio = null;
            $findme = 'RURAL';

            $pos = strpos($direccion, $findme);

            if ($pos === false) {

                $coor = $this->adress_medellin($direccion);
                $str1 = explode(",", $coor);

                $lat = $str1[1];
                $lng = $str1[0];

                $point = str_replace(',', ' ', $coor);

                if ($lat != 0) {

                    if (!$this->barrios) {

                        $this->barrios = DB::table('barrios')
                            ->get();

                    }

                    foreach ($this->barrios as $selects) {

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

                            $barrio = $selects->barrio;
                            $zona   = $selects->zona;

                            //return response()->json(['status' => 'ok', 'pointLocation' => $pointLocation, 'barrio' => $selects->barrio], 200);
                            break;
                        }

                    }

                } else {
                    $zona   = $name_municipios;
                    $barrio = $name_municipios;
                }
            } else {

                $zona   = $name_municipios;
                $barrio = $name_municipios;

            }
        } else {

            $barrio = $zona;
        }

        $insert = DB::table('worki')
            ->insertGetId([
                'Pedido'         => $pedido,
                'Instalacion'    => $codigo,
                'consecutive'    => $consecutive,
                'Direccion'      => $direccion,
                'Solicitante'    => $ncliente,
                'Cedula'         => $dcliente,
                'Telefono'       => $tel_cliente,
                'Tel_Contacto'   => $tel_contacto,
                'Municipio'      => $municipio,
                'idcontrato'     => $contrat,
                'id_company'     => $company,
                'worki_type_obr' => $id_tipo,
                'worki_state'    => 7,
                'Estrato'        => $estrato,
                'Vencimiento'    => $date_expiration,
                'Barrio'         => $barrio,
                'Zona'           => $zona,
                'Atualizacion'   => $date,
                'Cocineta'       => $Cocineta,
                'Obs_Pedido'     => $obs,
                'Fecha_Estado'   => $date,
                'envio'          => $envio,
                'x'              => $x,
                'y'              => $y,
                'lng'            => $lng,
                'lat'            => $lat,
                'sub_t_obr'      => $sub_t_obr,

            ]);

        ImportObrController::update_consec($consecutive, $company, $documento);

    }

    public function municipio($idmunicipio)
    {
        $seach = DB::table('municipality')
            ->where('id_dane', $idmunicipio)
            ->first();
        return $seach->name_municipality;
    }

    public function geodecoder($via, $placa, $nome, $str)
    {
        echo $address = $via . ' #' . $placa . ",+Medellin+Colombia";
        $address      = urlencode($address);

        $string  = '';
        $fullurl = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyA9y-39F5VPrGeRf5YI4-UIgTXUcrweETM";
        $string .= file_get_contents($fullurl); // get json content
        $json_a = json_decode($string, true); //json decoder

        //echo $json_a['results'][0]['geometry']['location']['lat']; // get lat for json
        //echo $json_a['results'][0]['geometry']['location']['lng']; // get ing for json
        var_dump($json_a);
        return $cor = $json_a['results'][0]['geometry']['location']['lng'] . ',' . $json_a['results'][0]['geometry']['location']['lat'];
    }

// funcion para atualizar las obras secundarias
    public function update_obr($company, $contrat, $codigo, $dcliente, $direccion, $estrato, $municipio, $ncliente, $obs, $pedido, $sub_t_obr, $tel_cliente, $tel_contacto, $valvula, $type_obr, $date, $date_expiration, $ot, $sub_state, $id_obr, $envio, $x, $y)
    {

        if ($sub_t_obr == 26) {

            $update = DB::table('worki')
                ->where('idworkI', '=', $id_obr)
                ->update([
                    'worki_state'  => 7,
                    'Vencimiento'  => $date_expiration,
                    'Atualizacion' => $date,
                    'Obs_Pedido'   => $obs,
                    'Fecha_Estado' => $date,
                    'envio'        => $envio,
                ]);

            if ($obs != null or $obs != '' or $obs != 0) {

                $update = DB::table('worki')
                    ->where('idworkI', '=', $id_obr)
                    ->update([
                        'Obs_Pedido' => $obs,
                    ]);
            }

        } else {

            $update = DB::table('worki')
                ->where('idworkI', '=', $id_obr)
                ->update([
                    'worki_type_obr' => $type_obr,
                    'worki_state'    => 7,
                    'Vencimiento'    => $date_expiration,
                    'Atualizacion'   => $date,
                    'Fecha_Estado'   => $date,
                    'envio'          => $envio,
                ]);

            if ($obs != null or $obs != '' or $obs != 0) {

                $update = DB::table('worki')
                    ->where('idworkI', '=', $id_obr)
                    ->update([
                        'Obs_Pedido' => $obs,
                    ]);
            }

        }

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

//function para buscar las ot
    public function SearchOt($id_obr, $ot)
    {
        $update = DB::table('ot')
            ->where('id_obr', '=', $id_obr)
            ->where('OT', '=', $ot)
            ->select('ot.sub_estado', 'ot.idOT')
            ->first();

        return $update;
    }

// function para atualiza las ot
    public function UpdateOt($id_obr, $ot, $sub_t_obr, $date, $sub_state, $envio, $items)
    {
        $update = DB::table('ot')
            ->where('id_obr', '=', $id_obr)
            ->where('OT', '=', $ot)
            ->update([
                'sub_estado' => $sub_state,
                'sub_tipo'   => $sub_t_obr,
                'fprogra'    => $date,
                'fstate'     => $date,
                'envio_ot'   => $envio,
            ]);

        $search_ot = ImportObrController::SearchOt($id_obr, $ot);

        $idot = $search_ot->idOT;
        // ImportObrController::items_aplicable($id_obr, $items, $idot);
    }

// funcion para insertar las ot
    public function InsertOt($insert, $ot, $sub_t_obr, $date, $sub_state, $envio, $items)
    {
        $insert_ot = DB::table('ot')
            ->insertGetId([
                'OT'         => $ot,
                'sub_estado' => $sub_state,
                'sub_tipo'   => $sub_t_obr,
                'id_obr'     => $insert,
                'fprogra'    => $date,
                'fstate'     => $date,
                'envio_ot'   => $envio,
            ]);

        ImportObrController::items_aplicable($insert, $items, $insert_ot);
    }

// funcion para los items aplicables
    public function items_aplicable($id_obr, $items, $insert_ot)
    {

        for ($i = 0; $i < count($items); $i++) {
            $item    = $items[$i]['item'];
            $company = $items[$i]['company'];
            $contrat = $items[$i]['contrat'];

            if ($item != null) {

                $insert = DB::table('items_aplicables')
                    ->insert([
                        'items_name'          => $item,
                        'items_idcompnay'     => $company,
                        'items_idcontract'    => $contrat,
                        'items_aplicables_ot' => $insert_ot,
                        'id_obr'              => $id_obr,
                    ]);

            }

        }

    }

//  function para consultar el numero de envio
    public function envio($company, $doc)
    {

        $envio = DB::table('consecutive')
            ->where('id_company', '=', $company)
            ->where('doc', '=', $doc)
            ->first();

        return $envio->consecutive;

    }

// function update send consec
    public function update_envio($company, $doc, $envio)
    {
        $envio  = $envio + 1;
        $update = DB::table('consecutive')
            ->where('id_company', '=', $company)
            ->where('doc', '=', $doc)
            ->update([
                'consecutive' => $envio,
            ]);
    }

// funcion que devuelve el total de obra subida
    public function search_result($envio)
    {
        $response = DB::table('ot')
            ->join('subtipo_obr_internas', 'ot.sub_tipo', '=', 'subtipo_obr_internas.idsubtipo_obr_internas')
            ->where('envio_ot', '=', $envio)
            ->select('subtipo_obr_internas.subtipo_obr_internas_name', DB::raw('count(ot.sub_tipo) as cantidad'))
            ->groupBy('ot.sub_tipo')
            ->get();

        return $response;
    }

    public function adress_medellin(Request $request)
    {
        //$adress = $adress;
        $adress = $request->input("adress");
        $str    = str_replace(" ", "", $adress);
        $str    = str_replace("CL", " CL ", $str);
        $str    = str_replace("CR", " CR ", $str);
        $str    = str_replace("SUR", " SUR ", $str);
        $str    = str_replace("DIAG", " DG ", $str);
        $str    = str_replace("TRAN", " TV ", $str);
        $str    = str_replace("CIRC", " TV ", $str);
        //var_dump($dir);
        $CL   = 'CL';
        $CR   = 'CR';
        $DIAG = 'DG';
        $TV   = 'TV';

        $pos  = strpos($str, $CL);
        $pos1 = strpos($str, $CR);
        $pos2 = strpos($str, $DIAG);
        $pos3 = strpos($str, $TV);
        $str1;
        $str2;

        if ($pos !== false) {

            if ($pos == 1) {

                if ($pos1 > 1 && $pos1 !== false) {

                    $str1 = explode("CR", $str);
                    $str2 = explode("(", $str1[1]);
                    $nome = 'CR';
                }

                if ($pos2 > 1 && $pos2 !== false) {

                    $str1 = explode("DG", $str);
                    $str2 = explode("(", $str1[1]);
                    $nome = 'DG';
                }

            }
        }

        if ($pos1 !== false) {

            if ($pos1 == 1) {

                if ($pos3 !== false) {

                    if ($pos3 != 1) {
                        $str1 = explode("TV", $str);
                        $str2 = explode("(", $str1[1]);

                        $nome = 'TV';

                    }
                } else {
                    $str1 = explode("CL", $str);
                    $str2 = explode("(", $str1[1]);
                    $nome = 'CL';
                }
            }

        }

        if ($pos2 !== false) {

            if ($pos2 == 1) {

                if ($pos > 1 && $pos !== false) {

                    $str1 = explode("CL", $str);
                    $str2 = explode("(", $str1[1]);
                    $nome = 'CL';

                }

                if ($pos1 > 1 && $pos1 !== false) {

                    $str1 = explode("CR", $str);
                    $str2 = explode("(", $str1[1]);
                    $nome = 'CR';

                }

                if ($pos3 > 1 && $pos3 !== false) {

                    $str1 = explode("TV", $str);
                    $str2 = explode("(", $str1[1]);
                    $nome = 'TV';

                }

            }
        }

        if ($pos3 !== false) {

            if ($pos3 == 1) {

                if ($pos2 > 1 && $pos2 !== false) {

                    $str1 = explode("DG", $str);
                    $str2 = explode("(", $str1[1]);
                    $nome = 'DG';

                }

                if ($pos1 > 1 && $pos1 !== false) {

                    $str1 = explode("CR", $str);
                    $str2 = explode("(", $str1[1]);
                    $nome = 'CR';

                }

                if ($pos > 1 && $pos !== false) {

                    $str1 = explode("CL", $str);
                    $str2 = explode("(", $str1[1]);
                    $nome = 'CL';

                }

            }

        }
        echo $via = trim($str1[0]);

        echo $placa = trim($str2[0]);

        // var_dump($str2[0]);

        $response = $this->search_adress($via, $placa, $nome, $str);

        echo $point = str_replace(',', ' ', $response);

        if (!$this->barrios) {

            $this->barrios = DB::table('barrios')
                ->get();

        }

        foreach ($this->barrios as $selects) {

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

                echo 'outside';

            } else {

                echo $barrio = $selects->barrio;
                echo $zona   = $selects->zona;

                //return response()->json(['status' => 'ok', 'pointLocation' => $pointLocation, 'barrio' => $selects->barrio], 200);
                break;
            }

        }

    }

    public function search_adress($via, $placa, $nome, $str)
    {
        $address = DB::table('direcciones_medellin')
            ->where('via', '=', $via)
            ->where('placa', '=', $placa)
            ->first();

        if (!$address) {

            $str1 = explode("-", $placa);

            $address1 = DB::table('direcciones_medellin')
                ->where('via', '=', $via)
                ->where('placa', 'like', $str1[0] . '%')
                ->get();

            foreach ($address1 as $addr) {

                $str2 = explode("-", $addr->placa);

                if ($str2[0] == $str1[0]) {
                    //var_dump($addr);
                    return $addr->coor;
                }
            }
            // echo '1';

        } else {

            return $address->coor;
        }

        echo '12--12';
        $str1 = explode("-", $placa);
        $str2 = explode(" ", $via);

        $via1     = trim($nome . ' ' . $str1[0]);
        $address2 = DB::table('direcciones_medellin')
            ->where('via', '=', $via1)
            ->where('placa', 'like', $str2[1] . '%')
            ->get();

        $resul = count($address2);
        var_dump($address2);
        if ($resul == 0) {

            //$str2  = explode("(", $str);
            // $addre = $this->getGeocodeData($str2[0]);
            $cor = $this->geodecoder($via, $placa, $nome, $str);

            return $cor;

        } else {
            foreach ($address2 as $addr) {

                $str3 = explode("-", $addr->placa);

                if ($str3[0] == $str2[1]) {

                    return $addr->coor;
                }
            }
        }

    }

    public function getGeocodeData($address)
    {
        $address  = $address . ',+medellin,+Antioquia,+Colombia';
        $response = Geocode::make()->address($address);

        if ($response) {
            $response->latitude();
            $response->longitude();
            $response->formattedAddress();
            $response->locationType();
        }
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
