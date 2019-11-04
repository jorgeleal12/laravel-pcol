<?php

namespace App\Http\Controllers\purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchasePrintController extends Controller
{

    protected $pdf;

    public function __construct(\App\Pdf $pdf)
    {
        $this->pdf = $pdf;
    }
    //
    public $consecutive_purc;
    public $id_company;
    public function print1(Request $request)
    {

    }
    function print(Request $request) {

        $this->idpurchases = (int) $request->input("idpurchases");

        $this->pdf->setNumber($this->idpurchases);
        $this->pdf->AliasNbPages();
        $this->pdf->AddPage();
        $this->pdf->SetFont('Times', '', 12);
        $this->pdf->SetAutoPageBreak(true, 85);

        $purchase = DB::table('detail_purchases')

            ->leftjoin('purchases', 'detail_purchases.id_purchases', '=', 'purchases.idpurchases')
            ->leftjoin('materiales', 'detail_purchases.cod_material', '=', 'materiales.idmateriales')
            ->leftjoin('unity', 'materiales.unity', '=', 'unity.idUnity')
            ->where('purchases.idpurchases', '=', $this->idpurchases)
            ->select('detail_purchases.*', 'unity.*', 'materiales.*', 'detail_purchases.iva as iva2')
            ->get();

        foreach ($purchase as $t) {

            $descuento  = (float) $t->unit_value * $t->discount / 100;
            $Vdescuento = (float) $t->unit_value - $descuento;
            $iva1       = (float) $Vdescuento * $t->iva2 / 100;
            $total      = (float) $Vdescuento + $iva1;
            $total2     = (float) $total * $t->requested_amount;

            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(2, 6, '', 0, 0);
            $this->pdf->Cell(23, 3.2, $t->code, 0, 0);
            $y = $this->pdf->GetY();
            $this->pdf->MultiCell(62, 4, $t->description, 0, 1);
            $y2 = $this->pdf->GetY();
            $this->pdf->SetXY(90, $y);
            $this->pdf->Cell(20, 6, $t->name_Unity, 0, 0, 'R');
            $this->pdf->Cell(16, 6, $t->requested_amount, 0, 0, 'R');
            $this->pdf->Cell(1, 6, '', 0, 0);
            $this->pdf->Cell(24, 6, number_format($t->unit_value) . ',00', 0, 0, 'R');
            $this->pdf->Cell(14, 6, $t->discount . '%', 0, 0, 'R');
            $this->pdf->Cell(11, 6, $t->iva2 . '%', 0, 0, 'R');
            $this->pdf->Cell(21, 6, number_format($total2) . ',00', 0, 1, 'R');

            $space = (isset($y2) ? $y2 - $y : 0) - 5;
            $this->pdf->Ln($space);
        }

        $header = ['Content-Type' => 'application/pdf'];

        return response($this->pdf->Output(), 200, $header);

    }

}
