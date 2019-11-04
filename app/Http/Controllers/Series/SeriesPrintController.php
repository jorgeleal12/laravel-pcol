<?php

namespace App\Http\Controllers\Series;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeriesPrintController extends Controller
{
    protected $pdf;

    public function __construct(\App\Pdfs $pdf)
    {
        $this->pdf = $pdf;
    }

    public function sprint(Request $request)
    {

        $data    = $request->input("data");
        $company = $request->input("company");

        $companysearch = DB::table('business')
            ->where('idbusiness', '=', $company)
            ->select('business.company_name')
            ->first();

        $compa = $companysearch->company_name;

        $ruta = $this->pdf->setNumber($data, $compa);

        return response()->json(['status' => 'ok', 'data' => $ruta], 200);

    }
}
