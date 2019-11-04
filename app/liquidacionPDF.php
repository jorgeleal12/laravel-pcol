<?php

namespace App;

use Codedge\Fpdf\Fpdf\Fpdf;

class liquidacionPDF extends Fpdf
{
    public $totaliva = 0;
    public $subtotal = 0;
    public $consecutive_purc;
    public $id_company;

}
