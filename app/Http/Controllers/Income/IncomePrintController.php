<?php

namespace App\Http\Controllers\Income;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomePrintController extends Controller
{
    protected $pdf;

    public function __construct(\App\IncomePdf $pdf)
    {
        $this->pdf = $pdf;

    }

    function print(Request $request) {

        $this->idincome = (int) $request->input("idincome");

        $this->pdf->setNumber($this->idincome);
        $this->pdf->AliasNbPages();
        $this->pdf->AddPage();
        $this->pdf->SetFont('Times', '', 12);
        $this->pdf->SetAutoPageBreak(true, 80);

        $income = DB::table('income_details')

            ->leftjoin('income', 'income_details.idincome', '=', 'income.idincome')
            ->leftjoin('materiales', 'income_details.cod_mater', '=', 'materiales.idmateriales')
            ->leftjoin('unity', 'materiales.unity', '=', 'unity.idUnity')
            ->where('income.idincome', '=', $this->idincome)
            ->select('income_details.*', 'unity.*', 'materiales.*', 'income_details.iva as iva2')
            ->get();

        foreach ($income as $t) {

            $descuento  = (float) $t->unit_value * $t->discount / 100;
            $Vdescuento = (float) $t->unit_value - $descuento;
            $iva1       = (float) $Vdescuento * $t->iva2 / 100;
            $total      = (float) $Vdescuento + $iva1;
            $total2     = (float) $total * $t->requested_amount;

            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(2, 6, '', 0, 0);
            $this->pdf->Cell(15, 3.2, $t->code, 0, 0);
            $y = $this->pdf->GetY();
            $this->pdf->MultiCell(58, 4, $t->description, 0, 1);
            $y2 = $this->pdf->GetY();
            $this->pdf->SetXY(78, $y);
            $this->pdf->Cell(20, 6, $t->name_Unity, 0, 0, 'R');
            $this->pdf->Cell(10, 6, $t->requested_amount, 0, 0, 'R');
            $this->pdf->Cell(16, 6, $t->ceceived_amount, 0, 0, 'R');
            $this->pdf->Cell(1, 6, '', 0, 0);
            $this->pdf->Cell(20, 6, number_format($t->unit_value) . ',00', 0, 0, 'R');
            $this->pdf->Cell(14, 6, $t->discount . '%', 0, 0, 'R');
            $this->pdf->Cell(15, 6, $t->iva2 . '%', 0, 0, 'R');
            $this->pdf->Cell(16, 6, number_format($total2) . ',00', 0, 1, 'R');

            $space = (isset($y2) ? $y2 - $y : 0) - 5;
            $this->pdf->Ln($space);
        }

        $header = ['Content-Type' => 'application/pdf'];

        return response($this->pdf->Output(), 200, $header);

    }
}
