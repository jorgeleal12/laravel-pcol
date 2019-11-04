<?php
namespace App\ClassPhp;

namespace App\Models;

class PDF extends FPDF
{

    public function Header()
    {

        $this->Ln(1);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(12, 10, '', 0, 0);
        $this->Cell(70, 10, '', 0, 0);
        $this->Cell(30, 10, 'sdasdas', 0, 0, 'C');

    }

}
