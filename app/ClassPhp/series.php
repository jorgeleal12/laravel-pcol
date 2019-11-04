<?php

namespace App\ClassPhp;

use Illuminate\Support\Facades\DB;

class series
{

    public function insert_series($idcompany, $serie, $dispatches, $inser)
    {

        $consecutive = DB::table('series')
            ->insert(['series'   => $serie,
                'iddispatche'        => $dispatches,
                'id_detaildispatche' => $inser,
                'id_company'         => $idcompany,

            ]);

        return $consecutive;

    }

    public function update_series($idcompany, $serie, $dispatches, $inser)
    {

        $update = DB::table('series')
            ->where('iddispatche', $dispatches)
            ->where('id_detaildispatche', $inser)
            ->update(['series' => $serie,

            ]);
        return $update;

    }

    public function delete($company, $series, $iddetail_dispatches)
    {

        //$series    = series::delete($company, $series);

        $delete = DB::table('series')
            ->where('series', '=', $series)
            ->where('id_company', '=', $company)
            ->where('id_detaildispatche', '=', $iddetail_dispatches)
            ->delete();

        return $delete;

    }
}
