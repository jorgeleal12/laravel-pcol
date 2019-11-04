<?php

namespace App\ClassPhp;

use Illuminate\Support\Facades\DB;

class log
{

    public function insert_log($userid, $action, $conse, $company)
    {
        $now  = new \DateTime();
        $date = $now->format('Y-m-d');

        $consecutive = DB::table('log')
            ->insert([
                'userid'  => $userid,
                'date'    => $date,
                'action'  => $action,
                'conse'   => $conse,
                'company' => $company,

            ]);

        return $consecutive;

    }

}
