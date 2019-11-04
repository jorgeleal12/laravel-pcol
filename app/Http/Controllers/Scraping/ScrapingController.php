<?php

namespace App\Http\Controllers\Scraping;

use App\Http\Controllers\Controller;
use File;
use Goutte\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class ScrapingController extends Controller
{
    //

    public function loader(Client $client, Request $request)
    {

        $HTML    = $_FILES['file'];
        $name    = 'ordenes.html';
        $file    = $HTML['tmp_name'];
        $type    = $HTML['type'];
        $carpeta = public_path('/public/html/');

        if (!File::exists($carpeta)) {

            $path = public_path('/public/html');
            File::makeDirectory($path, 0777, true);

        }

        move_uploaded_file($file, $carpeta . $name);

        $crawler = $client->request('GET', 'http://192.168.1.126/sip/public/public/html/ordenes.html');
        $inline  = 'background-color: #DDEDC2';
        $crawler->filter("body")->each(function (Crawler $node) {
            $result         = $node->text();
            $onlyconsonants = explode("Documento sin título", $result);
            unset($onlyconsonants[0]);
            //var_dump($onlyconsonants);
            //var_dump($onlyconsonants);
            $this->extraer($onlyconsonants);
        });

    }

    public function extraer($extraer)
    {
        $reponse = array();

        $extraer = preg_replace("[\n|\r|\n\r]", "", $extraer);
        foreach ($extraer as $valor) {
            //unset($valor[0]);

            $onlyconsonants = str_replace("GASES DE OCCIDENTE S.A. ESP", "", $valor);
            $onlyconsonants = str_replace("  800167643-5", "", $onlyconsonants);
            $onlyconsonants = str_replace("ORDEN DE TRABAJO", "", $onlyconsonants);
            $onlyconsonants = str_replace("DATOS DE LA ORDEN", "", $onlyconsonants);
            $onlyconsonants = str_replace("Departamento", "", $onlyconsonants);

            $explode      = explode("Localidad", $onlyconsonants);
            $departamento = $explode[0];
            $departamento = str_replace("<!--", "", $departamento);
            $departamento = str_replace(".Estilo57 {", "", $departamento);
            $departamento = str_replace("font-family: Arial, Helvetica, sans-serif;", "", $departamento);
            $departamento = str_replace("font-size: 14px;", "", $departamento);
            $departamento = str_replace("}", "", $departamento);
            $departamento = str_replace("H1.SaltoDePagina", "", $departamento);
            $departamento = str_replace("{PAGE-BREAK-AFTER: always", "", $departamento);
            $departamento = str_replace(".Estilo67 {", "", $departamento);
            $departamento = str_replace(".Estilo69 {font-size: xx-small", "", $departamento);
            $departamento = str_replace(".Estilo70 {font-size: 10px", "", $departamento);
            $departamento = str_replace(".Estilo70 {font-size: 10px", "", $departamento);
            $departamento = str_replace(".Estilo71 {font-size: 9px; ", "", $departamento);
            $departamento = str_replace("-->", "", $departamento);
            $departamento = str_replace(" ", "", $departamento);

            $departamento = preg_replace("/[\r\|]+/", PHP_EOL, $departamento);
            $departamento = preg_replace("/[\n\|]+/", PHP_EOL, $departamento);
            $departamento = preg_replace("/[\r\r\n|]+/", PHP_EOL, $departamento);
            $departamento = preg_replace("/[\t\r\n|]+/", PHP_EOL, $departamento);
            $departamento = preg_replace("/[\r\n|]+/", PHP_EOL, $departamento);
            $departamento = preg_replace("/[\r\n]+/", PHP_EOL, $departamento);
            $departamento = str_replace('"', "", $departamento);

            $departamento = preg_replace("[\n|\r|\n\r]", "", $departamento);
            //$departamento = preg_replace("/[\t\|]+/", PHP_EOL, $departamento);

            $explode1 = explode("Sector Operativo", $explode[1]);

            $explode23  = explode("-", $explode1[0]);
            $nlocalidad = trim($explode23[0]);
            $localidad  = trim($explode23[1]);

            $explode2 = explode("Número de la Orden", $explode1[1]);
            $sectorop = trim($explode2[0]);
            $explode3 = explode("Unidad Operativa", $explode2[1]);

            $numeroOR       = trim($explode3[0]);
            $onlyconsonants = str_replace("386 - CAFEREDES INGENIERIA LTDA/CALI", "", $explode3[1]);
            $onlyconsonants = str_replace("Tipo de Instalación // Plan Com.", "", $onlyconsonants);
            $explode4       = explode("Tipo de Trabajo", $onlyconsonants);
            $tipoinst       = trim($explode4[0]);

            $explode5 = explode("Fecha de Asignación", $explode4[1]);

            $explode22 = explode("-", $explode5[0]);

            $ntipo_trabajo = trim($explode22[0]);
            $tipo_trabajo  = trim($explode22[1]);

            //var_dump($tipo_trabajo);
            $explode6         = explode("Vendedor", $explode5[1]);
            $fecha_asignacion = trim($explode6[0]);
            $explode7         = explode("Actividad", $explode6[1]);
            $vendedor         = trim($explode7[0]);

            $onlyconsonants = str_replace("DATOS DEL CLIENTE", "", $explode7[1]);
            $onlyconsonants = str_replace("Número de Solicitud", "", $onlyconsonants);

            $explode8     = explode("Fecha de Solicitud", $onlyconsonants);
            $numeroSolici = trim($explode8[0]);

            $explode9     = explode("Suscripción/Contrato", $explode8[1]);
            $fecha_solici = trim($explode9[0]);

            $explode10 = explode("NIT / CC", $explode9[1]);

            $explode21   = explode("-", $explode10[0]);
            $suscripcion = trim($explode21[1]);

            $ncontrato = trim($explode21[0]);

            $explode11      = explode("Dirección", $explode10[1]);
            $onlyconsonants = str_replace("Cédula", "", $explode11[0]);
            $onlyconsonants = str_replace(": >", "", $onlyconsonants);
            $cedula         = trim($onlyconsonants);

            $explode12 = explode("Teléfono", $explode11[1]);
            $direccion = trim($explode12[0]);

            $array = preg_split("/[0-9]+/", $direccion);

            $barrio = end($array);

            $explode13 = explode("Categoría", $explode12[1]);
            $categoria = trim($explode13[0]);

            $explode14 = explode("Categoría", $explode12[1]);

            $Telefono  = trim($explode14[0]);
            $explode15 = explode("Subcategoría", $explode14[1]);

            $residencial = trim($explode15[0]);

            $explode16 = explode("Estado de Producto", $explode15[1]);
            $explode17 = explode("-", $explode16[0]);

            $estrato   = trim($explode17[0]);
            $explode18 = explode("Tipo de inmueble", $explode16[1]);

            $estado    = trim($explode18[0]);
            $explode19 = explode("Producto", $explode18[1]);
            $tinmueble = trim($explode19[0]);

            $onlyconsonants = str_replace("Comentario de venta", "", $explode19[1]);

            $explode20 = explode("TECNICOS DE LA INSTALACION", $onlyconsonants);

            $onlyconsonants = str_replace("DATOS", "", $explode20[0]);

            $obser = trim($onlyconsonants);

            //$response['departamento'] = $departamento;
            //$response['localidad']    = $localidad;
            //array_push($reponse['departamento'] = $departamento);

            //  $this->array_push_assoc($reponse, array('departamento' => $departamento));
            //  $this->array_push_assoc($reponse, array('localidad' => $localidad));
            array_push($reponse, array(
                'departamento' => $departamento,
                'localidad'    => $localidad,
                'nlocalidad'   => $nlocalidad,
                'sector'       => $sectorop,
                'numeroor'     => $numeroOR,
                'tipoinst'     => $tipoinst,
                'tipotrabajo'  => $tipo_trabajo,
                'ntipotrabajo' => $ntipo_trabajo,
                'fechaasig'    => $fecha_asignacion,
                'vendedor'     => $vendedor,
                'numerosolic'  => $numeroSolici,
                'fechasolici'  => $fecha_solici,
                'ncontrato'    => $ncontrato,
                'suscrip'      => $suscripcion,
                'cedula'       => $cedula,
                'direccion'    => $direccion,
                'barrio'       => trim($barrio),
                'categoria'    => $categoria,
                'telefono'     => $Telefono,
                'residencial'  => $residencial,
                'estrato'      => $estrato,
                'estado'       => $estado,
                'tinmueble'    => $tinmueble,
                'obsr'         => $obser));

            //array_push($data, array($departamento, $explode1[0]));
            // echo $onlyconsonants = str_replace("Localidad", "", $explode[1]);
        }
        // dd($reponse);
        //dd($reponse[0][0]);
        echo $result = json_encode($reponse);
    }

}
