<?php

namespace App;

use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\DB;

class Pdf extends Fpdf
{
    public $totaliva = 0;
    public $subtotal = 0;
    public $consecutive_purc;
    public $id_company;
    public $contrato;
    public $observaciones;
    public function setNumber($idpurchases)
    {

        $this->idpurchases = $idpurchases;
    }
    public function Header()
    {

        $header = ['Content-Type' => 'application/pdf'];

        $purchases = DB::table('purchases')
            ->leftjoin('providers', 'purchases.provider', '=', 'providers.idproviders')
            ->leftjoin('contract', 'purchases.purchases_id_contract', '=', 'contract.idcontract')
            ->leftjoin('business', 'business.idbusiness', '=', 'contract.id_empresa')
            ->leftjoin('providers_info', 'purchases.provider', '=', 'providers_info.id_provider')
            ->where('idpurchases', $this->idpurchases)
            ->select('purchases.*', 'providers.*', 'business.*', 'providers_info.*', 'providers.providers_nit as pnit', 'business.nit as nit', 'business.address', 'business.logo', 'business.phone', 'contract.contract_name')
            ->first();

        $this->contracto     = $purchases->contract_name;
        $this->observaciones = $purchases->purchases_observations;
        $this->Image('../public/imagenes/documentos/com-1.jpg', '2', '2', '205', '280', 'JPG');
        $this->Image('../public/public/images/' . $purchases->logo, 10, 2, 50);
        $this->Ln(1);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(12, 10, '', 0, 0);
        $this->Cell(70, 10, '', 0, 0);
        $this->Cell(30, 10, $purchases->company_name, 0, 0, 'C');
        $this->Ln(4);
        $this->SetFont('Arial', '', 9);
        $this->Cell(12, 10, '', 0, 0);
        $this->Cell(70, 10, '', 0, 0);
        $this->Cell(30, 10, 'Nit: ' . $purchases->nit, 0, 0, 'C');
        $this->Ln(4);
        $this->SetFont('Arial', '', 9);
        $this->Cell(12, 10, '', 0, 0);
        $this->Cell(70, 10, '', 0, 0);
        $this->Cell(30, 10, 'correo: ', 0, 0, 'C');
        $this->Ln(4);

        $this->SetFont('Arial', '', 9);
        $this->Cell(12, 10, '', 0, 0);
        $this->Cell(70, 10, '', 0, 0);
        $this->Cell(30, 10, 'Tel: ' . $purchases->phone, 0, 0, 'C');
        $this->Ln(4);
        $this->SetFont('Arial', '', 9);
        $this->Cell(12, 10, '', 0, 0);
        $this->Cell(70, 10, '', 0, 0);
        $this->Cell(30, 10, $purchases->address, 0, 0, 'C');

        $this->Ln(1);
        $this->SetFont('Arial', 'b', 12);
        $this->Cell(120, 0, '', 0, 0);
        $this->Cell(42, 0, '', 0, 0);
        $this->Cell(30, -7, '' . $purchases->consecutive_purc, 0, 0);

        $this->Ln(30);
        $this->SetFont('Arial', '', 9);
        $this->Cell(39, 10, '', 0, 0);
        $this->Cell(75, -27, $purchases->providers_name, 0, 0);
        $this->Cell(40, 10, '', 0, 0);
        $this->Cell(30, -29, $purchases->purchases_date, 0, 0);
        $this->Ln(5);

        $this->SetFont('Arial', '', 9);
        $this->Cell(39, 10, '', 0, 0);
        $this->Cell(105, -28, $purchases->pnit, 0, 0);
        $this->Cell(10, 10, '', 0, 0);
        $this->Cell(10, -29, $purchases->purchases_deliver_date, 0, 0);
        $this->Ln(4);

        $this->SetFont('Arial', '', 9);
        $this->Cell(39, 10, '', 0, 0);
        $this->Cell(84, -28, $purchases->providers_addres, 0, 0);
        //$this->Cell(36, 10, '', 0, 0);
        $this->Cell(10, -29, 'OBRA ' . $this->contracto, 0, 0);

        $this->Ln(5);
        $this->SetFont('Arial', '', 9);
        $this->Cell(39, 10, '', 0, 0);
        $this->Cell(120, -28, $purchases->phone_provider, 0, 0);
        $this->Cell(36, 10, '', 0, 0);
        $this->Cell(30, 10, '', 0, 0);
        $this->Ln(10);

    }

    public function Footer()
    {
        $totaliva = 0;
        $subtotal = 0;

        $detail_purchases = DB::table('detail_purchases')
            ->where('id_purchases', $this->idpurchases)

            ->select('detail_purchases.*')
            ->get();
        $data = array();

        foreach ($detail_purchases as $detail_purchase) {
            $output[] = array(
                $requested_amount = $detail_purchase->requested_amount,
                $unit_value = $detail_purchase->unit_value,
                $discount = $detail_purchase->discount,
                $iva = $detail_purchase->iva,

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
        $this->MultiCell(85, 4, '', 0, 2);
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

        $this->SetY(-66.5);
        $this->SetFont('Arial', '', 9);
        $this->Cell(18, 8, '', 0, 0);
        $this->MultiCell(89, 4, $this->contracto, 0, 2);

        $this->SetY(-52);
        $this->SetFont('Arial', '', 9);
        $this->Cell(3, 8, '', 0, 0);
        $this->MultiCell(85, 4, utf8_decode($this->observaciones), 0, 2);
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        $this->SetFont('Arial', '', 9);

        // Número de página
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}
