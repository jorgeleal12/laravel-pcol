<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class internas_day extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:ans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $search = DB::table('worki')
            ->where('worki_state', '!=',2)
        //->where('histo_obr_state', 6)
        ->select('worki.Atualizacion','worki.sub_t_obr','worki.Vencimiento','worki.idworkI','worki.idworkI','worki.Fecha_Svc')
            ->get();

        foreach ($search as $key => $data) {

            if ($data->Atualizacion == 0) {
                $sud_tipo = DB::table('subtipo_obr_internas')
                    ->where('idsubtipo_obr_internas', '=', $data->sub_t_obr)
                    ->first();
            } else {

                $Atualizacion = Carbon::parse($data->Atualizacion);
                $Vencimiento  = Carbon::parse($data->Vencimiento);
                $ans          = $Atualizacion->diffInDays($Vencimiento);
            }

            if ($data->Fecha_Svc == null) {

                $date = date('Y-m-d', time());

            } else {

                $date = $data->Fecha_Svc;

            }

            $Vencidos = Carbon::parse($date);
            $dias_v   = $Atualizacion->diffInDays($Vencidos);

            echo $dias_ven = $dias_v - $ans;
            $update        = DB::table('worki')
                ->where('idworkI', $data->idworkI)
                ->update([
                    'dias_vencidos' => $dias_ven,
                    'ans'           => $ans,
                ]);
        }
    }
}
