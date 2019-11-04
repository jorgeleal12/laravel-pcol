<?php

namespace App\ClassPhp;

use Illuminate\Support\Facades\DB;

/**
 *
 */
class UpdateInventary
{

// funcion para consultar el inventario
    public function total($id_company, $id_cellar, $cod_materia)
    {

        $inventario_cellar = DB::table('inventario_cellar')

            ->where('id_cellar', $id_cellar)
            ->where('id_material', $cod_materia)
            ->select('inventario_cellar.*')
            ->first();

// si no existe el material creado en esa bodega lo crea para despues atualizarlo
        if (!$inventario_cellar) {

            $insertCod = DB::table('inventario_cellar')
                ->insert([

                    'id_cellar'   => $id_cellar,
                    'id_material' => $cod_materia,

                ]);

            $cantidad = 0;

        } else {

            $cantidad = $inventario_cellar->inventary_quantity;

        }

        return $cantidad;

        //return $cantidad;

    }

// funcion para atualizar el inventario
    public function update($id_company, $id_cellar, $cod_materia, $quantity)
    {
        $inventario_cellar = DB::table('inventario_cellar')

            ->where('id_cellar', $id_cellar)
            ->where('id_material', $cod_materia)
            ->update([

                'inventary_quantity' => $quantity,

            ]);
    }

    // funcion que ejecuta una atualizaicon positiva en el inventario
    public function AddInventary($id_company, $id_cellar, $cod_materia, $cantidad, $idrefund_masive, $Consec, $tipo)
    {

        $pos_quantity = UpdateInventary::total($id_company, $id_cellar, $cod_materia);

        $new_quantity = (FLOAT) $pos_quantity + $cantidad;

        $Update = UpdateInventary::update($id_company, $id_cellar, $cod_materia, $new_quantity);

        $historico = UpdateInventary::historico($Consec, $tipo, $pos_quantity, $cantidad, $new_quantity, $id_cellar, $id_company, $cod_materia);

    }

    // funcion que ejecuta una atualizaicon negativa en el inventario
    public function subtract($id_company, $id_cellar, $cod_materia, $cantidad, $idrefund_masive, $Consec, $tipo)
    {
        $pos_quantity = UpdateInventary::total($id_company, $id_cellar, $cod_materia);

        $new_quantity = (FLOAT) $pos_quantity - $cantidad;

        $Update = UpdateInventary::update($id_company, $id_cellar, $cod_materia, $new_quantity);

        $historico = UpdateInventary::historico($Consec, $tipo, $pos_quantity, -$cantidad, $new_quantity, $id_cellar, $id_company, $cod_materia);
    }

    // funcion para guardar el historico de los movimientos
    public function historico($conse, $tipo, $inventarioActual, $cantidades, $resultado, $cellar, $idcompany, $cod_mater, $usuario)
    {

        $hoy        = date("Y-m-d H:i");
        $hisrorical = DB::table('historical_inventory')
            ->insert(['idcompany' => $idcompany,
                'cellar'              => $cellar,
                'conse'               => $conse,
                'tipo'                => $tipo,
                'inventarioActual'    => $inventarioActual,
                'cantidades'          => $cantidades,
                'resultado'           => $resultado,
                'id_code'             => $cod_mater,
                'date'                => $hoy,
                'user'                => $usuario,

            ]);

    }

    // nueva funcion que ejecuta una atualizaicon positiva en el inventario
    public function sumarinventario($id, $cellar, $consecutivo, $material, $cantidad, $tipo, $company, $usuario)
    {

        $acantidad = $this->inventario_atual($cellar, $material, $cantidad);

        $cantia = $acantidad + $cantidad;
        $update = DB::table('inventario_cellar')
            ->where('id_cellar', $cellar)
            ->where('id_material', $material)
            ->update([
                'inventary_quantity' => $cantia,
            ]);

        $this->historico($consecutivo, $tipo, $acantidad, $cantidad, $cantia, $cellar, $company, $material, $usuario);

    }

    public function restarinventario($id, $cellar, $consecutivo, $material, $cantidad, $tipo, $company, $usuario)
    {

        $acantidad = $this->inventario_atual($cellar, $material, $cantidad);

        $cantia = $acantidad - $cantidad;
        $update = DB::table('inventario_cellar')
            ->where('id_cellar', $cellar)
            ->where('id_material', $material)
            ->update([
                'inventary_quantity' => $cantia,
            ]);

        $this->historico($consecutivo, $tipo, $acantidad, -$cantidad, $cantia, $cellar, $company, $material, $usuario);

    }

    public function inventario_atual($cellar, $material, $cantidad)
    {

        $search = DB::table('inventario_cellar')
            ->where('id_cellar', $cellar)
            ->where('id_material', $material)
            ->first();

        if (!$search) {

            $insert = DB::table('inventario_cellar')
                ->insert([
                    'id_cellar'          => $cellar,
                    'id_material'        => $material,
                    'inventary_quantity' => 0,
                ]);

            $rcantidad = 0;

        } else {

            $rcantidad = $search->inventary_quantity;

        }
        return $rcantidad;
    }

    public function inventario_atual_edit($cellar, $material)
    {

        $search = DB::table('inventario_cellar')
            ->where('id_cellar', $cellar)
            ->where('id_material', $material)
            ->first();

        return $search->inventary_quantity;
    }

    public function cancular_cantidades($id, $cellar, $consecutivo, $material, $cantidadant, $cantidadact, $tipo, $company)
    {

        $cantidad = 0;
        $r_saldo  = false;

        if ($cantidadact > $cantidadant) {

            $cantidad = $cantidadact - $cantidadant;

            $this->sumarinventario($id, $cellar, $consecutivo, $material, $cantidad, $tipo, $company);
            return $r_saldo;
        }

        if ($cantidadant > $cantidadact) {

            $cantidad = $cantidadant - $cantidadact;

            //$this->restarinventario($id, $cellar, $consecutivo, $material, $cantidad, $tipo, $company);

            $saldo = $this->inventario_atual($cellar, $material, $cantidadact);

            if ($cantidadact > $saldo) {

                $r_saldo = true;

                return $r_saldo;

            } else {

                $this->restarinventario($id, $cellar, $consecutivo, $material, $cantidad, $tipo, $company);
                return $r_saldo;

            }

        }
    }
}
