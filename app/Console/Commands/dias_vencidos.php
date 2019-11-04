<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class dias_vencidos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_day:dias_habiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza los dias habiles de operacion y mantenimiento ';

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
        //
        $search = DB::table('oym')
            //->where('consecutive', '=', 11835)
            ->get();

        foreach ($search as $key => $data) {

            $activity = DB::table('list_activity_oym')
                ->where('id_activity', '=', $data->activity)
                ->first();

            $id_oym          = $data->id_oym;
            $date_assignment = $data->date_assignment;
            $closing_date    = $data->closing_date;
            $day_ans         = $activity->ans;
            $day_habiles     = $activity->a_c;

            $Standby = DB::table('histo_oym')
                ->where('id_obr', $id_oym)
                ->where('histo_obr_state', 6)
                ->get();
               


            $day = date('Y-m-d', time());

            $datef;
            $numberdias  = 0;
            $diasnumber1 = 0;

             $number1234=count($Standby);

            if ($number1234>0) { 

                foreach ($Standby as $key => $datas) {

                    if ($datas->date_clase != null) {

                        echo $datef = $datas->date_clase;
                    } else {

                        $datef = $day;
                    }

                    $date_ini1   = Carbon::parse($datas->histo_obr_date);
                    $datef       = Carbon::parse($datef);
                    $diasStandby = $date_ini1->diffInDays($datef);

                    $numberdias += $diasStandby;
                }

                if ($day_habiles == 1) {
                    $datas->histo_obr_date;
                    $diasnumber1 = $this->feriado($datas->histo_obr_date, $numberdias);
                } else {

                    $diasnumber1 = $numberdias;
                }
              
            } else{
                
            }

            echo  $diasnumber1;
              
            if ($closing_date == null) {

                $hoy = date("Y-m-d");

            } else {

                $hoy = $closing_date;
            }

            if ($day_habiles == 1) {

                   $dias = $this->feriado($date_assignment, $day_ans);

                  $ans  = $day_ans+$dias;

            } else {

                  $ans = $day_ans;
            }

            $dias1 = $ans + $diasnumber1;

             $date_expiration = date("Y-m-d", strtotime('+' . $dias1 . ' day', strtotime($date_assignment)));

            $dias12 = $this->feriado_vence($date_expiration, 1);
            //var_dump($dias);
            

              $date_expiration1 = date("Y-m-d", strtotime('+' . $dias12 . ' day', strtotime($date_expiration)));

            $fechaEmision    = Carbon::parse($date_assignment);
            $fechaExpiracion = Carbon::parse($hoy);

             $diasDiferencia = $fechaExpiracion->diffInDays($fechaEmision);

            echo $dias_vencidos = $diasDiferencia- $day_ans;

            $update = DB::table('oym')
                ->where('id_oym', $id_oym)
                ->update([
                    'date_expiration' => $date_expiration1,
                    'day_vencidos'    => $dias_vencidos,
                    'day_standby'     => $diasnumber1,
                ]);

        }
    }

    public function feriado($date_ini, $dias_counter)
    {

        $fecha_noti = date('Y-m-d', strtotime($date_ini));
        // $num_accion = $date_end;

        //2018-08-08
        //2018-08-15

        //Arreglo con todos los feriados
        $feriados = array(
            '2018-08-20',
            '2018-10-15',
            '2018-11-05',
            '2018-11-12',
            '2018-12-08',
            '2018-12-25',
            '2019-01-01',
            '2019-03-25',
            '2019-04-18',
            '2019-04-19',
            '2019-05-01',
            '2019-06-03',
            '2019-06-24',
            '2019-07-01',
            '2019-07-20',
            '2019-08-07',
            '2019-08-19',
            '2019-10-14',
            '2019-11-04',
            '2019-11-11',
            '2019-12-25',
        );
        //Timestamp De Fecha De Comienzo
        $comienzo = strtotime($fecha_noti);

        //Inicializo la Fecha Final
        $fecha_venci_noti = $comienzo;
        //Inicializo El Contador
        //$i = 0; while ($i < 7)
        $dias   = 0;
        $prueba = 0;
        for ($i = 0; $i < $dias_counter; $i++) {
            //Le Sumo un Dia a La Fecha Final (86400 Segundos)
            $fecha_venci_noti += 86400;
            //Inicializo a FALSE La Variable Para Saber Si Es Feriado
            $es_feriado = false;
            //Recorro Todos Los Feriados
            foreach ($feriados as $key => $feriado) {

                //Verifico Si La Fecha Final Actual Es Feriado O No
                if (date("Y-m-d", $fecha_venci_noti) === date("Y-m-d", strtotime($feriado))) {
                    //En Caso de Ser feriado Cambio Mi variable A TRUE
                    $es_feriado = true;

                }
            }
            //Verifico Que No Sea Un Sabado, Domingo O Feriado
            if (!(date("w", $fecha_venci_noti) == 0 || $es_feriado)) {
                //var_dump(date("w", $fecha_venci_noti));
                //En Caso De No Ser Sabado, Domingo O Feriado Aumentamos Nuestro contador
                $prueba++;
            } else {
                //var_dump(date("w", $fecha_venci_noti));
                //var_dump($es_feriado);
                $dias++;
                //var_dump(date("w", $fecha_venci_noti).'festivo');
            }

        }
        //var_dump($prueba, $dias);
        return $dias;

    }

    public function feriado_vence($date_ini, $dias_counter)
    {

        $fecha_noti = date('Y-m-d', strtotime($date_ini));
        // $num_accion = $date_end;

        //2018-08-08
        //2018-08-15

        //Arreglo con todos los feriados
        $feriados = array('2018-08-20',
            '2018-10-15',
            '2018-11-05',
            '2018-11-12',
            '2018-12-08',
            '2018-12-25',
            '2019-01-01',
            '2019-03-25',
            '2019-04-18',
            '2019-04-19',
            '2019-05-01',
            '2019-06-03',
            '2019-06-24',
            '2019-07-01',
            '2019-07-20',
            '2019-08-07',
            '2019-08-19',
            '2019-10-14',
            '2019-11-04',
            '2019-11-11',
            '2019-12-25',
        );
        //Timestamp De Fecha De Comienzo
        $comienzo = strtotime($fecha_noti);

        //Inicializo la Fecha Final
        $fecha_venci_noti = $comienzo;
        //Inicializo El Contador
        //$i = 0; while ($i < 7)
        $dias   = 0;
        $prueba = 0;
        for ($i = 0; $i < $dias_counter; $i++) {
            //Le Sumo un Dia a La Fecha Final (86400 Segundos)
            //$fecha_venci_noti += 86400;
            //Inicializo a FALSE La Variable Para Saber Si Es Feriado
            $es_feriado = false;
            //Recorro Todos Los Feriados
            foreach ($feriados as $key => $feriado) {

                //Verifico Si La Fecha Final Actual Es Feriado O No
                if (date("Y-m-d", $fecha_venci_noti) === date("Y-m-d", strtotime($feriado))) {
                    //En Caso de Ser feriado Cambio Mi variable A TRUE
                    $es_feriado = true;

                }
            }
            //Verifico Que No Sea Un Sabado, Domingo O Feriado
            if (!(date("w", $fecha_venci_noti) == 0 || $es_feriado)) {
                //var_dump(date("w", $fecha_venci_noti));
                //En Caso De No Ser Sabado, Domingo O Feriado Aumentamos Nuestro contador
                $prueba++;
            } else {
                //var_dump(date("w", $fecha_venci_noti));
                //var_dump($es_feriado);
                $dias++;
                //var_dump(date("w", $fecha_venci_noti).'festivo');
            }

        }
        //var_dump($prueba, $dias);
        return $dias;

    }
}
