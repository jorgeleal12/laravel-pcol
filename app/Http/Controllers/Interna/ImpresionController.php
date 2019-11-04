<?php

namespace App\Http\Controllers\interna;

use App\Http\Controllers\Controller;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImpresionController extends Controller
{

    protected $pdf;

    public function __construct(\App\liquidacionPDF $pdf)
    {
        $this->pdf = $pdf;

    }

    public function search(Request $request)
    {
        $date      = $request->input("date");
        $documento = $request->input("documento");
        $company   = $request->input("company");

        $search = DB::table('worki')
            ->leftjoin('ot', 'worki.idworkI', '=', 'ot.id_obr')
            ->leftjoin('employees', 'employees.idemployees', '=', 'worki.programado_A')
            ->Where('ot.sub_estado', '=', '16')
            ->Where('ot.fprogra', '=', $date)
            ->Where('worki.id_company', '=', $company)
            ->whereIn('sub_tipo', [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 16, 17, 18, 23, 24, 25, 26])
        // ->groupBy('ot.id_obr')
            ->groupBy('worki.programado_A')
            ->select('worki.programado_A', DB::raw('count(worki.idworkI) as total'), DB::raw("CONCAT(name,' ',last_name) AS nameprogramado"))
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function search_progra(Request $request)
    {
        $date       = $request->input("date");
        $idemployee = $request->input("idemployee");
        $company    = $request->input("company");

        $search = DB::table('worki')
            ->leftjoin('ot', 'worki.idworkI', '=', 'ot.id_obr')
            ->leftjoin('employees', 'employees.idemployees', '=', 'worki.programado_A')

            ->leftjoin('tipos_obr_internas', 'tipos_obr_internas.idtipos_obr_internas', '=', 'worki.worki_type_obr')
            ->leftjoin('state_obr', 'state_obr.idstate_obr', '=', 'worki.worki_state')

            ->leftjoin('municipality', 'municipality.id_dane', '=', 'worki.Municipio')
            ->Where('ot.sub_estado', '=', '16')
            ->Where('ot.fprogra', '=', $date)
            ->Where('worki.id_company', '=', $company)
            ->Where('worki.programado_A', '=', $idemployee)
            ->whereIn('sub_tipo', [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 16, 17, 18, 23, 24, 25, 26])
            ->groupBy('ot.id_obr')
        // ->groupBy('worki.programado_A')
            ->select('worki.programado_A', 'worki.consecutive', 'worki.Atualizacion', 'worki.Pedido', 'worki.Instalacion', 'worki.Direccion', 'worki.Solicitante', 'worki.Cedula', 'tipos_obr_internas.tipos_obr_internas_name', 'state_obr.state_obr_name', 'municipality.name_municipality', 'worki.idworkI',

                DB::raw('count(worki.programado_A) as total'), DB::raw("CONCAT(name,' ',last_name) AS nameprogramado"))
            ->get();

        return response()->json(['status' => 'ok', 'response' => $search], 200);
    }

    public function printliquidacion(Request $request)
    {

        $idobr   = json_decode($request->input("idobr"));
        $company = $request->input("company");

        $lados = Config::get('Config.' . $company . '.lados');

        $lado1 = Config::get('Config.' . $company . '.Lcyc1');
        $lado2 = Config::get('Config.' . $company . '.Lcyc2');

        $y_axis_initial = 25;
        $row_height     = 0;
        $y_axis         = 0;
        $y_axis         = 0;
//print column titles

        $y_axis = $y_axis + $row_height;

        $i = 0;

//Set maximum rows per page
        $max = 0;

//Set Row Height
        $row_height = 6;

        $this->pdf->SetAutoPageBreak(false);

        foreach ($idobr as $t) {

            $search = DB::table('worki')
                ->leftjoin('municipality', 'municipality.id_dane', '=', 'worki.Municipio')
                ->leftjoin('contract', 'contract.idcontract', '=', 'worki.idcontrato')
                ->leftjoin('business', 'business.idbusiness', '=', 'worki.id_company')
                ->where('idworkI', '=', $t)
                ->get();

            foreach ($search as $a) {

                $this->pdf->AddPage();
                $this->pdf->Image('../public/imagenes/documentos/' . $lado1, '2', '2', '207', '290', 'JPG');
                $this->pdf->Ln(2);
                $this->pdf->SetFont('Arial', '', 6.7);
                $this->pdf->Cell(23, 3, '', 0);
                $this->pdf->Cell(63, 5, '', 0);
                $this->pdf->Cell(47, 5, '', 0);
                $this->pdf->Cell(49, 5, '', 0);
                $this->pdf->Cell(20, 5, $a->consecutive, 0);

                $this->pdf->Ln(5.5);
                $this->pdf->SetFont('Arial', '', 6.7);
                $this->pdf->Cell(20, 3, '', 0);
                $this->pdf->Cell(67, 3, utf8_decode($a->Solicitante), 0);
                $this->pdf->Cell(58, 3, $a->Cedula, 0);
                $this->pdf->Cell(41, 3, $a->Pedido, 0);
                $this->pdf->Cell(20, 3, $a->Nro_Anillo, 0);

                $this->pdf->Ln(2.6);
                $this->pdf->SetFont('Arial', '', 6.7);
                $this->pdf->Cell(20, 3, '', 0);
                $this->pdf->Cell(67, 3, $a->Direccion, 0);
                $this->pdf->Cell(58, 3, '', 0);
                $this->pdf->Cell(40, 3, $a->Telefono, 0);
                $this->pdf->Cell(20, 3, '', 0);

                $this->pdf->Ln(2.5);
                $this->pdf->SetFont('Arial', '', 6.7);
                $this->pdf->Cell(20, 3, '', 0);
                $this->pdf->Cell(80, 3, utf8_decode($a->name_municipality), 0);
                $this->pdf->Cell(45, 3, $a->Estrato, 0);
                $this->pdf->Cell(20, 3, $a->Tel_Contacto, 0);
                $this->pdf->Cell(20, 3, '', 0);

                $this->pdf->Ln(2.6);
                $this->pdf->SetFont('Arial', '', 6.7);
                $this->pdf->Cell(20, 3, '', 0);
                $this->pdf->Cell(63, 3, $a->Instalacion, 0);
                $this->pdf->Cell(50, 3, '', 0);
                $this->pdf->Cell(40, 3, $a->Zona, 0);
                $this->pdf->Cell(20, 3, '', 0);

                $this->pdf->Ln(5.8);
                $this->pdf->SetFont('Arial', '', 6);
                $this->pdf->Cell(23, 3, '', 0);
                $this->pdf->Cell(63, 3, '', 0);
                $this->pdf->Cell(50, 3, '', 0);
                $this->pdf->Cell(40, 3, $a->company_name, 0);
                $this->pdf->Cell(20, 3, '', 0);

                $searchot = DB::table('ot')
                    ->where('id_obr', '=', $t)
                    ->get();

                $number = 0;
                foreach ($searchot as $e) {

                    if ($number <= 1) {
                        $this->pdf->Ln(2.6);
                        $this->pdf->SetFont('Arial', '', 6);
                        $this->pdf->Cell(20, 3, '', 0);
                        $this->pdf->Cell(63, 3, $e->OT, 0);
                        $this->pdf->Cell(50, 3, '', 0);
                        $this->pdf->Cell(40, 3, '', 0);
                        $this->pdf->Cell(20, 3, '', 0);
                    }

                    $number++;
                }

                $this->pdf->Ln(-3);
                $this->pdf->SetFont('Arial', '', 6);
                $this->pdf->Cell(23, 3, '', 0);
                $this->pdf->Cell(63, 3, '', 0);
                $this->pdf->Cell(50, 3, '', 0);
                $this->pdf->Cell(40, 3, $a->nit, 0);
                $this->pdf->Cell(20, 3, '', 0);

                $this->pdf->Ln(2.6);
                $this->pdf->SetFont('Arial', '', 6);
                $this->pdf->Cell(23, 3, '', 0);
                $this->pdf->Cell(63, 3, '', 0);
                $this->pdf->Cell(50, 3, '', 0);
                $this->pdf->Cell(40, 3, $a->contract_name, 0);
                $this->pdf->Cell(20, 3, '', 0);

                $this->pdf->Ln(220);
                $this->pdf->SetFont('Arial', '', 5.5);
                $this->pdf->Cell(26, 5, '', 0);
                $this->pdf->Cell(60, 5, '', 0);
                $this->pdf->Cell(50, 5, '', 0);
                $this->pdf->Cell(17, 5, '', 0);
                $this->pdf->Cell(20, 5, '', 0);

                $y_axis = $y_axis + $row_height;

                //Set $i variable to 0 (first row)
                $i = 0;

                if ($lados == 2) {
                    $y_axis_initial = 25;
                    $this->pdf->AddPage();
                    $this->pdf->Image('../public/imagenes/documentos/' . $lado2, '2', '2', '200', '280', 'JPG');
                    $this->pdf->Ln(1);
                    $this->pdf->SetFont('Arial', '', 6.7);
                    $this->pdf->Cell(26, 5, '', 0);
                    $this->pdf->Cell(63, 5, '', 0);
                    $this->pdf->Cell(47, 5, '', 0);
                    $this->pdf->Cell(41, 5, '', 0);
                    $this->pdf->Cell(20, 5, $a->consecutive, 0);

                    $this->pdf->Ln(7);
                    $this->pdf->SetFont('Arial', '', 6.7);
                    $this->pdf->Cell(26, 3, '', 0);
                    $this->pdf->Cell(67, 3, utf8_decode($a->Solicitante), 0);
                    $this->pdf->Cell(43, 3, $a->Cedula, 0);
                    $this->pdf->Cell(43, 3, $a->Pedido, 0);
                    $this->pdf->Cell(20, 3, $a->Nro_Anillo, 0);

                    $this->pdf->Ln(3.3);
                    $this->pdf->SetFont('Arial', '', 6.7);
                    $this->pdf->Cell(26, 3, '', 0);
                    $this->pdf->Cell(63, 3, $a->Direccion, 0);
                    $this->pdf->Cell(47, 3, '', 0);
                    $this->pdf->Cell(40, 3, $a->Telefono, 0);
                    $this->pdf->Cell(20, 3, '', 0);

                    $this->pdf->Ln(3.5);
                    $this->pdf->SetFont('Arial', '', 6.7);
                    $this->pdf->Cell(26, 3, '', 0);
                    $this->pdf->Cell(80, 3, utf8_decode($a->name_municipality), 0);
                    $this->pdf->Cell(30, 3, $a->Estrato, 0);
                    $this->pdf->Cell(20, 3, $a->Tel_Contacto, 0);
                    $this->pdf->Cell(20, 3, '', 0);

                    $this->pdf->Ln(3.5);
                    $this->pdf->SetFont('Arial', '', 6.7);
                    $this->pdf->Cell(26, 3, '', 0);
                    $this->pdf->Cell(63, 3, $a->Instalacion, 0);
                    $this->pdf->Cell(50, 3, '', 0);
                    $this->pdf->Cell(40, 3, $a->Zona, 0);
                    $this->pdf->Cell(20, 3, '', 0);

                    $this->pdf->Ln(6.5);
                    $this->pdf->SetFont('Arial', '', 6.7);
                    $this->pdf->Cell(23, 3, '', 0);
                    $this->pdf->Cell(63, 3, '', 0);
                    $this->pdf->Cell(45, 3, '', 0);
                    $this->pdf->Cell(40, 3, $a->company_name, 0);
                    $this->pdf->Cell(20, 3, '', 0);

                    $this->pdf->Ln(3.5);
                    $this->pdf->SetFont('Arial', '', 6.7);
                    $this->pdf->Cell(23, 3, '', 0);
                    $this->pdf->Cell(63, 3, '', 0);
                    $this->pdf->Cell(45, 3, '', 0);
                    $this->pdf->Cell(40, 3, $a->nit, 0);
                    $this->pdf->Cell(20, 3, '', 0);

                    $this->pdf->Ln(3.3);
                    $this->pdf->SetFont('Arial', '', 6.7);
                    $this->pdf->Cell(23, 3, '', 0);
                    $this->pdf->Cell(63, 3, '', 0);
                    $this->pdf->Cell(45, 3, '', 0);
                    $this->pdf->Cell(40, 3, $a->contract_name, 0);
                    $this->pdf->Cell(20, 3, '', 0);

                    $this->pdf->Ln(198);
                    $this->pdf->SetFont('Arial', '', 5.5);
                    $this->pdf->Cell(26, 5, '', 0);
                    $this->pdf->Cell(60, 5, '', 0);
                    $this->pdf->Cell(50, 5, '', 0);
                    $this->pdf->Cell(17, 5, '', 0);
                    //  $this->pdf->Cell(20, 5, utf8_decode('Descripción de items aplicables'), 0);

                    $searchitems = DB::table('items_aplicables')
                        ->where('id_obr', '=', $t)
                        ->get();
                    $this->pdf->Ln(2);
                    foreach ($searchitems as $f) {
                        $this->pdf->Ln(3.3);
                        $this->pdf->SetFont('Arial', '', 6);
                        $this->pdf->Cell(20, 2, '', 0);
                        $this->pdf->Cell(63, 2, '', 0);
                        $this->pdf->Cell(70, 2, '', 0);
                        $this->pdf->Cell(40, 2, utf8_decode($f->items_name), 0);
                        $this->pdf->Cell(20, 2, '', 0);

                    }

                }

                $this->pdf->SetY($y_axis);

                $this->pdf->Ln(0);
                $this->pdf->SetFont('Arial', 'B', 8);

                //Go to next row
                $y_axis = $y_axis + $row_height;
                $i      = $i + 1;
                $max    = $i + 1;
            }
        }
        $header = ['Content-Type' => 'application/pdf'];

        return response($this->pdf->Output(), 200, $header);
    }

    public function printfc(Request $request)
    {

        $idobr   = json_decode($request->input("idobr"));
        $company = $request->input("company");

        $lados = Config::get('Config.' . $company . '.ladosfc');
        $lado1 = Config::get('Config.' . $company . '.fc');
        $lado2 = Config::get('Config.' . $company . '.fc1');

        $y_axis_initial = 25;
        $row_height     = 0;
        $y_axis         = 0;
        $y_axis         = 0;
//print column titles

        $y_axis = $y_axis + $row_height;

        $i = 0;

//Set maximum rows per page
        $max = 0;

//Set Row Height
        $row_height = 6;

        $this->pdf->SetAutoPageBreak(false);

        foreach ($idobr as $t) {

            $search = DB::table('worki')
                ->leftjoin('municipality', 'municipality.id_dane', '=', 'worki.Municipio')
                ->leftjoin('contract', 'contract.idcontract', '=', 'worki.idcontrato')
                ->leftjoin('business', 'business.idbusiness', '=', 'worki.id_company')
                ->where('idworkI', '=', $t)
                ->get();

            foreach ($search as $a) {

                $this->pdf->AddPage();
                $this->pdf->Image('../public/imagenes/documentos/' . $lado1, '4', '2', '200', '280', 'JPG');

                $this->pdf->Ln(8);
                $this->pdf->SetFont('Arial', '', 10);
                $this->pdf->Cell(23, 5, '', 0);
                $this->pdf->Cell(63, 5, '', 0);
                $this->pdf->Cell(70, 5, '', 0);
                $this->pdf->Cell(10, 5, 'CSC:', 0);
                $this->pdf->Cell(20, 5, $a->consecutive);

                $this->pdf->Ln(0);
                $this->pdf->SetFont('Arial', '', 10);
                $this->pdf->Cell(23, 5, '', 0);
                $this->pdf->Cell(63, 5, '', 0);
                $this->pdf->Cell(47, 5, '', 0);
                $this->pdf->Cell(41, 5, '', 0);
                $this->pdf->Cell(20, 5, '', 0);

                $this->pdf->Ln(8);
                $this->pdf->SetFont('Arial', '', 8);
                $this->pdf->Cell(5, 8, '', 0);
                $this->pdf->Cell(98, 5, utf8_decode($a->Solicitante), 0);
                $this->pdf->Cell(35, 5, $a->Telefono, 0);
                $this->pdf->Cell(25, 5, '', 0);
                $this->pdf->Cell(20, 5, $a->Pedido, 0);

                $this->pdf->Ln(8);
                $this->pdf->SetFont('Arial', '', 8);
                $this->pdf->Cell(01, 8, '', 0);
                $this->pdf->Cell(70, 8, $a->Direccion, 0);
                $this->pdf->Cell(40, 8, utf8_decode($a->name_municipality), 0);
                $this->pdf->Cell(40, 8, $a->Instalacion, 0);
                $this->pdf->Cell(20, 8, '', 0);

                $this->pdf->Ln(192.5);
                $this->pdf->SetFont('Arial', '', 9);
                $this->pdf->Cell(22, 5, '', 0);
                $this->pdf->Cell(84, 5, utf8_decode($a->Solicitante), 0);
                $this->pdf->Cell(31, 5, $a->Cedula, 0);
                $this->pdf->Cell(20, 5, '', 0);
                $this->pdf->Cell(20, 5, '', 0);

                $this->pdf->Ln(16);
                $this->pdf->SetFont('Arial', '', 7.5);
                $this->pdf->Cell(37, 5, '', 0);
                $this->pdf->Cell(71, 5, $a->company_name, 0);
                $this->pdf->Cell(52, 5, $a->nit, 0);
                $this->pdf->Cell(20, 5, $a->contract_name, 0);
                $this->pdf->Cell(20, 5, '', 0);

                $y_axis = $y_axis + $row_height;

                //Set $i variable to 0 (first row)
                $i = 0;

                if ($lados == 2) {
                    $y_axis_initial = 25;
                    $this->pdf->AddPage();
                    $this->pdf->Image('../public/imagenes/documentos/' . $lado2, '2', '2', '200', '280', 'JPG');

                }

                $this->pdf->SetY($y_axis);

                $this->pdf->Ln(0);
                $this->pdf->SetFont('Arial', 'B', 8);

                //Go to next row
                $y_axis = $y_axis + $row_height;
                $i      = $i + 1;
                $max    = $i + 1;
            }
        }
        $header = ['Content-Type' => 'application/pdf'];

        return response($this->pdf->Output(), 200, $header);
    }
}
