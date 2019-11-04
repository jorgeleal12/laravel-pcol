<?php

namespace App\ClassPhp;

use Illuminate\Support\Facades\DB;

/**
 *
 */
class Inventary
{

    public function total($id_company, $id_cellar, $cod_materia)
    {

        $inventario_cellar = DB::table('inventario_cellar')
            ->where('id_cellar', $id_cellar)
            ->where('cod_materia', $cod_materia)
            ->select('inventario_cellar.*')
            ->first();

        if (!$inventario_cellar) {

            $cantidad = 0;

        } else {

            $cantidad = $inventario_cellar->inventary_quantity;

        }
        return $cantidad;

    }

    public function insert($id_company, $id_cellar, $cod_materia, $quantity)
    {
        $inventario_cellar = DB::table('inventario_cellar')->insert([
            'id_cellar' => $id_cellar, 'cod_materia' => $cod_materia, 'inventary_quantity' => $quantity,

        ]);

    }

    public function update($id_company, $id_cellar, $cod_materia, $quantity)
    {
        $inventario_cellar = DB::table('inventario_cellar')

            ->where('id_cellar', $id_cellar)
            ->where('cod_materia', $cod_materia)
            ->update([

                'inventary_quantity' => $quantity,

            ]);
    }

    public function historico($conse, $tipo, $inventarioActual, $cantidades, $resultado, $cellar, $idcompany, $cod_mater)
    {
        try {
            $hoy        = date("Y-m-d H:i");
            $hisrorical = DB::table('historical_inventory')
                ->insert(['idcompany' => $idcompany,
                    'cellar'              => $cellar,
                    'conse'               => $conse,
                    'tipo'                => $tipo,
                    'inventarioActual'    => $inventarioActual,
                    'cantidades'          => $cantidades,
                    'resultado'           => $resultado,
                    'cod_mater'           => $cod_mater,
                    'date'                => $hoy,

                ]);

        } catch (Exception $e) {

        }

    }

}
