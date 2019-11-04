<?php

namespace App;

use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\DB;

class IncomePdf extends Fpdf
{
    public $totaliva = 0;
    public $subtotal = 0;
    public $consecutive_purc;
    public $id_company;

    public function setNumber($idincome)
    {

        $this->idincome = $idincome;
    }
    public function Header()
    {

        $header = ['Content-Type' => 'application/pdf'];

        $income = DB::table('income')
            ->leftjoin('providers', 'income.income_idprovider', '=', 'providers.idproviders')
            ->leftjoin('contract', 'income.income_idcontract', '=', 'contract.idcontract')
            ->leftjoin('business', 'business.idbusiness', '=', 'contract.id_empresa')
            ->leftjoin('providers_info', 'income.income_idprovider', '=', 'providers_info.id_provider')
            ->where('idincome', $this->idincome)
            ->select('income.*', 'providers.*', 'business.*', 'providers_info.*', 'providers.providers_nit as pnit', 'business.nit as nit', 'business.address', 'business.logo', 'business.phone')
            ->first();

        $this->Image('../public/imagenes/documentos/ingresos.jpg', '2', '2', '205', '280', 'JPG');
        $this->Image('../public/public/images/' . $income->logo, 10, 2, 50);
        $this->Ln(1);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(12, 10, '', 0, 0);
        $this->Cell(70, 10, '', 0, 0);
        $this->Cell(30, 10, $income->company_name, 0, 0, 'C');
        $this->Ln(4);
        $this->SetFont('Arial', '', 9);
        $this->Cell(12, 10, '', 0, 0);
        $this->Cell(70, 10, '', 0, 0);
        $this->Cell(30, 10, 'Nit: ' . $income->nit, 0, 0, 'C');
        $this->Ln(4);
        $this->SetFont('Arial', '', 9);
        $this->Cell(12, 10, '', 0, 0);
        $this->Cell(70, 10, '', 0, 0);
        $this->Cell(30, 10, 'correo: ', 0, 0, 'C');
        $this->Ln(4);

        $this->SetFont('Arial', '', 9);
        $this->Cell(12, 10, '', 0, 0);
        $this->Cell(70, 10, '', 0, 0);
        $this->Cell(30, 10, 'Tel: ' . $income->phone, 0, 0, 'C');
        $this->Ln(4);
        $this->SetFont('Arial', '', 9);
        $this->Cell(12, 10, '', 0, 0);
        $this->Cell(70, 10, '', 0, 0);
        $this->Cell(30, 10, $income->address, 0, 0, 'C');

        $this->Ln(1);
        $this->SetFont('Arial', 'b', 12);
        $this->Cell(120, 0, '', 0, 0);
        $this->Cell(42, 0, '', 0, 0);
        $this->Cell(30, -7, '' . $income->income_conse, 0, 0);

        $this->Ln(30);
        $this->SetFont('Arial', '', 9);
        $this->Cell(39, 10, '', 0, 0);
        $this->Cell(75, -27, $income->providers_name, 0, 0);
        $this->Cell(40, 10, '', 0, 0);
        $this->Cell(30, -29, $income->date, 0, 0);
        $this->Ln(5);

        $this->SetFont('Arial', '', 9);
        $this->Cell(39, 10, '', 0, 0);
        $this->Cell(105, -28, $income->pnit, 0, 0);
        $this->Cell(10, 10, '', 0, 0);
        $this->Cell(10, -29, $income->income_date_delivery, 0, 0);
        $this->Ln(4);

        $this->SetFont('Arial', '', 9);
        $this->Cell(39, 10, '', 0, 0);
        $this->Cell(120, -28, 'direccion', 0, 0);
        $this->Cell(36, 10, '', 0, 0);
        $this->Cell(30, 10, '', 0, 0);
        $this->Ln(5);
        $this->SetFont('Arial', '', 9);
        $this->Cell(39, 10, '', 0, 0);
        $this->Cell(120, -28, $income->phone_provider, 0, 0);
        $this->Cell(36, 10, '', 0, 0);
        $this->Cell(30, 10, '', 0, 0);
        $this->Ln(10);

    }

    public function Footer()
    {
        $totaliva = 0;
        $subtotal = 0;

        $income_details = DB::table('income_details')
            ->where('idincome', $this->idincome)

            ->select('income_details.*')
            ->get();
        $data = array();

        foreach ($income_details as $income_details) {
            $output[] = array(

                $requested_amount = $income_details->requested_amount,
                $unit_value = $income_details->unit_value,
                $discount = $income_details->discount,
                $iva = $income_details->iva,

                $descuento = (float) $unit_value * $discount / 100,
                $vlr_descuento = (float) $unit_value - $descuento,
                $formula_iva = (float) $vlr_descuento * $iva / 100,

                $vlr_subtotal = (float) $requested_amount * $vlr_descuento,
                $vlr_iva = (float) $requested_amount * $formula_iva,

                $totaliva += $vlr_iva,
                $subtotal += $vlr_subtotal,

            );
        }

        $total = (float) $subtotal + $totaliva;

        $this->SetY(-79);
        $this->SetFont('Arial', '', 9);
        $this->Cell(15, 8, '', 0, 0);
        $this->Cell(56, 8, '', 0, 0);
        $this->Cell(22, 8, '', 0, 0);
        $this->Cell(18, 8, '', 0, 0);
        $this->Cell(12, 8, '', 0, 0);
        $this->Cell(20, 8, '', 0, 0);
        $this->Cell(22, 8, '', 0, 0);
        $this->Cell(22, 10, number_format($subtotal, 2, ',', '.'), 0, 0, 'R');

        $this->SetY(-74);
        $this->SetFont('Arial', '', 9);
        $this->Cell(15, 8, '', 0, 0);
        $this->Cell(56, 8, '', 0, 0);
        $this->Cell(22, 8, '', 0, 0);
        $this->Cell(18, 8, '', 0, 0);
        $this->Cell(12, 8, '', 0, 0);
        $this->Cell(20, 8, '', 0, 0);
        $this->Cell(22, 8, '', 0, 0);
        $this->Cell(22, 10, number_format($totaliva, 2, ',', '.'), 0, 0, 'R');

        $this->SetY(-52);
        $this->SetFont('Arial', '', 9);
        $this->Cell(3, 8, '', 0, 0);
        $this->MultiCell(85, 4, 'observaciones', 0, 2);
        $this->Cell(1, 8, '', 0, 0);
        $this->Cell(56, 8, '', 0, 0);
        $this->Cell(22, 8, '', 0, 0);
        $this->Cell(18, 8, '', 0, 0);
        $this->Cell(12, 8, '', 0, 0);
        $this->Cell(20, 8, '', 0, 0);
        $this->Cell(25, 8, '', 0, 0);

        $this->SetY(-69);
        $this->SetFont('Arial', '', 9);
        $this->Cell(15, 8, '', 0, 0);
        $this->Cell(56, 8, '', 0, 0);
        $this->Cell(22, 8, '', 0, 0);
        $this->Cell(18, 8, '', 0, 0);
        $this->Cell(12, 8, '', 0, 0);
        $this->Cell(20, 8, '', 0, 0);
        $this->Cell(22, 8, '', 0, 0);
        $this->Cell(22, 10, number_format($total, 2, ',', '.'), 0, 0, 'R');
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        $this->SetFont('Arial', '', 9);

        // Número de página
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}
