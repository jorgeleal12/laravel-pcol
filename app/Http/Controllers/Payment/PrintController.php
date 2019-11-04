<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrintController extends Controller
{
    protected $pdf;

    public function __construct(\App\pdf_pay $pdf)
    {
        $this->pdf = $pdf;
    }

    function print(Request $request) {

        $this->id_pay     = $request->input("id_pay");
        $this->idemployee = $request->input("idemployee");
        $this->name       = $request->input("name");
        $this->company    = $request->input("company");

        $this->pdf->setNumber($this->id_pay, $this->idemployee, $this->name);
        $this->pdf->AliasNbPages();

        $this->pdf->AddPage();
        $this->pdf->SetFont('Times', '', 12);
        $this->pdf->SetAutoPageBreak(true, 60);

        $search = DB::table('worki')
            ->leftjoin('activity_internas', 'activity_internas.id_obr', '=', 'worki.idworkI')
            ->leftjoin('tipos_obr_internas', 'tipos_obr_internas.idtipos_obr_internas', '=', 'worki.worki_type_obr')
            ->leftjoin('state_obr', 'state_obr.idstate_obr', '=', 'worki.worki_state')
            ->leftjoin('employees', 'activity_internas.id_employe', '=', 'employees.idemployees')
            ->leftjoin('activities', 'activity_internas.idactivity', '=', 'activities.idactivities')

            ->leftjoin('state_activity', 'state_activity.idstate_activity', '=', 'activity_internas.id_state')
            ->where('activity_internas.id_employe', '=', $this->idemployee)
            ->where('worki.id_company', '=', $this->company)
            ->where('activity_internas.id_pay', $this->id_pay)
            ->orderBy('activity_internas.acti_date', 'ASC')
            ->select('employees.Users_id_identification', 'activity_internas.id_employe', 'activity_internas.quantity', 'activities.activities_value as valuei', 'activities.activities_name', 'worki.consecutive', 'activity_internas.acti_date', 'tipos_obr_internas.tipos_obr_internas_name',

                'state_obr.state_obr_name', 'state_activity.state_activity_name',
                DB::raw("(SELECT CONCAT(name,' ',last_name) FROM employees where employees.idemployees=activity_internas.id_employe) AS empleado"),
                DB::raw("ROUND(activity_internas.quantity * activities.activities_value, 2) as total")
            )
            ->get();

        foreach ($search as $t) {

            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(20, 8, '', 0, 0);
            $this->pdf->Cell(22, 3.2, $t->consecutive, 0, 0);
            $this->pdf->Cell(25, 3.2, $t->acti_date, 0, 0);

            $y = $this->pdf->GetY();
            $this->pdf->MultiCell(70, 4, $t->activities_name, 0, 1);
            $y2 = $this->pdf->GetY();
            $this->pdf->SetXY(130, $y);
            $this->pdf->Line(8, $y - 1, 198, $y - 1); //Horizontal
            $this->pdf->Cell(16, 6, $t->quantity, 0, 0, 'R');
            $this->pdf->Cell(1, 6, '', 0, 0);
            $this->pdf->Cell(24, 6, $t->valuei, 0, 0, 'R');

            $this->pdf->Cell(21, 6, $t->total, 0, 1, 'R');

            $space = (isset($y2) ? $y2 - $y : 0) - 3.9;
            $this->pdf->Ln($space);

        }

        $header = ['Content-Type' => 'application/pdf'];

        return response($this->pdf->Output(), 200, $header);
    }
}
