<?php

namespace App;

use Codedge\Fpdf\Fpdf\Fpdf;

class renameoym extends Fpdf
{
    public function setNumber($select)
    {

        foreach ($select as $valor) {
            # code...
            //$this->AddPage();
            // $this->SetFont('Arial', '', 15);
            // $this->Cell(40, 20);
            //  $this->Write(5, 'A continuación mostramos una imagen ');
            //  $this->Image('../public/imagenes/documentos/com-1.jpg', 80, 22, 35, 38, 'JPG', 'http://www.desarrolloweb.com');
            //  $name = rand(200, 2000);
            //  $this->state;
            //  $this->Output("../public/images/series-" . $name . ".pdf", "F");
        }

    }

}
