<?php

namespace App\ClassPhp;

use Illuminate\Support\Facades\DB;

class Consecutive
{
    private $id_company;
    private $doc;

    public function query_consecutive($id_company, $doc)
    {

        $consecutive = DB::table('consecutive')
            ->where('id_company', $id_company)
            ->where('doc', $doc)
            ->select('consecutive', 'idconsecutive')
            ->first();

        return $consecutive;

    }

    public function query_consecutive_ext($id_company, $doc, $contract)
    {

        $consecutive = DB::table('consecutive')
            ->where('id_company', $id_company)
            ->where('doc', $doc)
            ->where('idcontrac', $contract)
            ->select('consecutive', 'idconsecutive')
            ->first();

        return $consecutive;

    }
    public function Updateconsecutive_ext($id_company, $doc, $consec, $contract)
    {
        $update = DB::table('consecutive')
            ->where('id_company', $id_company)
            ->where('doc', $doc)
            ->where('idcontrac', $contract)
            ->update(['consecutive' => $consec]);

        return $update;

    }

    public function Updateconsecutive($id_company, $doc, $consec)
    {
        $update = DB::table('consecutive')
            ->where('id_company', $id_company)
            ->where('doc', $doc)
            ->update(['consecutive' => $consec]);

        return $update;

    }
    public function Updateconsecutive_inventario($id_company, $doc, $consec)
    {

        $conseAtual = $consec + 1;
        $update     = DB::table('consecutive')
            ->where('id_company', $id_company)
            ->where('doc', $doc)
            ->update(['consecutive' => $conseAtual]);

        return $update;

    }
}
