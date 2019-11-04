<?php

namespace App;

use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\DB;

class pdf_pay extends Fpdf
{

    public function setNumber($id_pay, $idemployee, $name)
    {

        $this->id_pay     = $id_pay;
        $this->idemployee = $idemployee;
        $this->name       = $name;

    }
    public function Header()
    {

        $header = ['Content-Type' => 'application/pdf'];

        $search_pay = DB::table('pay_activity')
            ->where('id_employee', '=', $this->idemployee)
            ->where('idpay_activity', '=', $this->id_pay)
            ->select('pay_activity.*', 'pay_activity.vpunto as vpuntos', 'pay_activity.prestamos as presta', 'pay_activity.date_pay as datepay'

                , 'pay_activity.idpay_activity as idpay', 'pay_activity.obs as obs')
            ->first();

        $this->Image('../public/imagenes/documentos/activity.jpg', '2', '2', '205', '280', 'JPG');

        $this->Ln(1);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(12, 10, '', 0, 0);
        $this->Cell(70, 10, '', 0, 0);
        $this->Cell(30, 10, '', 0, 0, 'C');
        $this->Ln(4);
        $this->SetFont('Arial', '', 9);
        $this->Cell(12, 10, '', 0, 0);
        $this->Cell(70, 10, '', 0, 0);
        $this->Cell(30, 10, '', 0, 0, 'C');
        $this->Ln(6);
        $this->SetFont('Arial', '', 9);
        $this->Cell(12, 10, '', 0, 0);
        $this->Cell(21, 10, '', 0, 0);
        $this->Cell(30, 10, $this->name, 0, 0, 'C');
        $this->Ln(7.5);
        $this->SetFont('Arial', '', 9);
        $this->Cell(5, 10, '', 0, 0);
        $this->Cell(50, 10, $search_pay->total, 0, 0, 'C');
        $this->Cell(48, 10, $search_pay->vpunto, 0, 0, 'C');
        $this->Cell(60, 10, $search_pay->opay, 0, 0, 'C');
        $this->Cell(30, 10, $search_pay->datepay, 0, 0, 'C');
        $this->Ln(7.5);
        $this->SetFont('Arial', '', 9);
        $this->Cell(5, 10, '', 0, 0);
        $this->Cell(50, 10, $search_pay->meta, 0, 0, 'C');
        $this->Cell(48, 10, $search_pay->saldo, 0, 0, 'C');
        $this->Cell(60, 10, $search_pay->odesc, 0, 0, 'C');
        $this->Ln(7.5);
        $this->SetFont('Arial', '', 9);
        $this->Cell(5, 10, '', 0, 0);
        $this->Cell(50, 10, '', 0, 0, 'C');
        $this->Cell(48, 10, $search_pay->tpay, 0, 0, 'C');
        $this->Cell(60, 10, $search_pay->prestamos, 0, 0, 'C');
        $this->Ln(20);
        $this->obs = $search_pay->obs;

    }
    public function Footer()
    {
        $this->SetY(-48);
        $this->SetFont('Arial', '', 9);
        $this->Cell(3, 8, '', 0, 0);
        $this->MultiCell(85, 4, utf8_decode($this->obs), 0, 2);

        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        $this->SetFont('Arial', '', 9);

        // Número de página
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}
