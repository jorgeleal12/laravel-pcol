<?php

namespace App\Http\Controllers\Acta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class ActaController extends Controller
{
    //
    public function validate_acta(Request $request)
    {
        $data     = $request->input("data");
        $contract = $request->input("contract");
        $reponse  = [];

        array_push($reponse, array('ESTADO', 'PEDIDO', 'CODIGO DE INSTALACION', 'OT', 'ITEM', 'CANTIDAD', 'CONSECUTIVO'));

        for ($i = 0; $i < count($data); $i++) {

            $A = isset($data[$i]["A"]) ? $data[$i]["A"] : ''; // pedido
            $B = isset($data[$i]["B"]) ? $data[$i]["B"] : ''; // codigo de instalacion
            $C = isset($data[$i]["C"]) ? $data[$i]["C"] : ''; // OT
            $D = isset($data[$i]["D"]) ? $data[$i]["D"] : ''; // items
            $E = isset($data[$i]["E"]) ? $data[$i]["E"] : ''; // cantidad

// consulta para optener el item
            $search_item = DB::table('item_cobro')
                ->where('item_cobro_code', '=', $D)
                ->where('item_cobro_contract', '=', $contract)
                ->first();
    
            $id_items = $search_item->iditem_cobro;

            $search_obr = DB::table('worki')
                ->where('Pedido', '=', $A)
                ->where('Instalacion', '=', $B)
                ->leftjoin('ot', 'worki.idworkI', '=', 'ot.id_obr')
                ->select('worki.consecutive', 'worki.Pedido', 'worki.Instalacion', 'worki.idworkI', 'ot.sub_tipo', 'ot.OT', 'worki.worki_state')
                ->get();

            // var_dump($search_obr);
            $ot_inixistente = 0;

            foreach ($search_obr as $t) {

                $idworkI     = $t->idworkI;
                $sub_tipo    = $t->sub_tipo;
                $OT          = $t->OT;
                $state_obr   = $t->worki_state;
                $consecutive = $t->consecutive;

                if ($OT == $C and $state_obr != 3 and $state_obr != 1) {

                    $search_item_internas = DB::table('items_internas')
                        ->where('id_obr', '=', $idworkI)
                        ->where('id_items', '=', $id_items)
                        ->where('preacta', '=', null)
                        ->first();

                    if (count($search_item_internas) == 0) {

                        $ot_inixistente++;

                        array_push($reponse, array('INESISTENTE', $A, $B, $C, $D, $E, $consecutive));

                        continue 2;

                    } else {

                        $state = ActaController::search_itemsAp($search_item_internas, $E);

                        $ot_inixistente++;

                        array_push($reponse, array($state, $A, $B, $C, $D, $E, $consecutive));

                        continue 2;
                    }

                }

            }

            if ($ot_inixistente == 0) {

                $state = 'OT NO EXISTE';

                array_push($reponse, array($state, $A, $B, $C, $D, $E, '', '', $id_items));

                continue 1;
            }

        }

        $clear_items = DB::table('items_internas')
            ->where('preacta', '=', 1)
            ->update([
                'preacta' => null]);

        return response()->json(['status' => 'ok', 'reponse' => $reponse], 200);

    }

    public function search_itemsAp($items_internas, $E)
    {

        //var_dump($items_internas)
        $quantity1        = $items_internas->quantity;
        $id_items         = $items_internas->id_items;
        $iditems_internas = $items_internas->iditems_internas;

        if ($quantity1 == $E) {

            $state = 'PAGADO';

            $update_item = DB::table('items_internas')
                ->where('iditems_internas', '=', $iditems_internas)
                ->update([
                    'preacta' => 1,
                ]);

            return $state;
        }

        if ($quantity1 > $E) {

            $state = 'PAGO INFERIOR';

            $update_item = DB::table('items_internas')
                ->where('iditems_internas', '=', $iditems_internas)
                ->update([
                    'preacta' => 1,
                ]);

            return $state;
        }
        if ($quantity1 < $E) {

            $state = 'PAGO SUPERIOR';

            $update_item = DB::table('items_internas')
                ->where('iditems_internas', '=', $iditems_internas)
                ->update([
                    'preacta' => 1,
                ]);
            return $state;
        }

    }
//

    //
    ///

    //SUBIR ACTA AL SISTEMAS

    public function upload_acta(Request $request)
    {
        $data     = $request->input("data");
        $contract = $request->input("contract");
        $date     = $request->input("date");
        $reponse  = [];

        array_push($reponse, array('ESTADO', 'PEDIDO', 'CODIGO DE INSTALACION', 'OT', 'ITEM', 'CANTIDAD', 'CONSECUTIVO', 'IDOBRA', 'IDITEM'));
        $envio = DB::table('consecutive')
            ->where('idcontrac', '=', $contract)
            ->where('doc', '=', 'ACTA')
            ->first();

        $NumberSend = $envio->consecutive;

        for ($i = 0; $i < count($data); $i++) {

            $A = isset($data[$i]["A"]) ? $data[$i]["A"] : ''; // pedido
            $B = isset($data[$i]["B"]) ? $data[$i]["B"] : ''; // codigo de instalacion
            $C = isset($data[$i]["C"]) ? $data[$i]["C"] : ''; // OT
            $D = isset($data[$i]["D"]) ? $data[$i]["D"] : ''; // items
            $E = isset($data[$i]["E"]) ? $data[$i]["E"] : ''; // cantidad

// consulta para optener el item
            $search_item = DB::table('item_cobro')
                ->where('item_cobro_code', '=', $D)
                ->where('item_cobro_contract', '=', $contract)
                ->first();

            $id_items = $search_item->iditem_cobro;

            $search_obr = DB::table('worki')
                ->where('Pedido', '=', $A)
                ->where('Instalacion', '=', $B)
                ->leftjoin('ot', 'worki.idworkI', '=', 'ot.id_obr')
                ->select('worki.consecutive', 'worki.Pedido', 'worki.Instalacion', 'worki.idworkI', 'ot.sub_tipo', 'ot.OT', 'worki.worki_state')
                ->get();

            // var_dump($search_obr);
            $ot_inixistente = 0;

            foreach ($search_obr as $t) {

                $idworkI     = $t->idworkI;
                $sub_tipo    = $t->sub_tipo;
                $OT          = $t->OT;
                $state_obr   = $t->worki_state;
                $consecutive = $t->consecutive;

                if ($OT == $C and $state_obr != 3) {

                    $search_item_internas = DB::table('items_internas')
                        ->where('id_obr', '=', $idworkI)
                        ->where('id_items', '=', $id_items)
                        ->where('preacta', '=', null)
                        ->first();

                    if (count($search_item_internas) == 0) {

                        array_push($reponse, array('INESISTENTE', $A, $B, $C, $D, $E, $consecutive, $idworkI, $id_items));

                        $ot_inixistente++;

              
                    }   
                    if (count($search_item_internas) > 0) {

                        $state = ActaController::Upload_items($search_item_internas, $E, $NumberSend, $date);

                        array_push($reponse, array($state, $A, $B, $C, $D, $E, $consecutive, $idworkI, $id_items));

                        $ot_inixistente++;

   
                    }

                }

            }

            if ($ot_inixistente == 0) {

                $state = 'OT NO EXISTE';

                array_push($reponse, array($state, $A, $B, $C, $D, $E, '', '', $id_items));

                continue 1;
            }

        }

        $clear_items = DB::table('items_internas')
            ->where('preacta', '=', 1)
            ->update([
                'preacta' => null]);

        DB::table('consecutive')
            ->where('idcontrac', '=', $contract)
            ->where('doc', '=', 'ACTA')
            ->update([
                'consecutive' => $NumberSend + 1]);

        return response()->json(['status' => 'ok', 'reponse' => $reponse], 200);

    }

    public function Upload_items($items_internas, $E, $NumberSend, $date)
    {

        //var_dump($items_internas)
        $quantity1        = $items_internas->quantity;
        $id_items         = $items_internas->id_items;
        $iditems_internas = $items_internas->iditems_internas;

        if ($quantity1 == $E) {

            $state = 'PAGADO';

            $update_item = DB::table('items_internas')
                ->where('iditems_internas', '=', $iditems_internas)
                ->update([
                    'preacta'      => 1,
                    'id_state'     => 2,
                    'quanity_acta' => $E,
                    'date_acta'    => $date,
                    'envio'        => $NumberSend]);

            return $state;
        }

        if ($quantity1 > $E) {

            $state = 'PAGO PARCIAL';

            $update_item = DB::table('items_internas')
                ->where('iditems_internas', '=', $iditems_internas)
                ->update([
                    'preacta'      => 1,
                    'id_state'     => 3,
                    'quanity_acta' => $E,
                    'date_acta'    => $date,
                    'envio'        => $NumberSend]);

            return $state;
        }
        if ($quantity1 < $E) {

            $state = 'PAGO SUPERIOR';

            $update_item = DB::table('items_internas')
                ->where('iditems_internas', '=', $iditems_internas)
                ->update([
                    'preacta'      => 1,
                    'id_state'     => 4,
                    'quanity_acta' => $E,
                    'date_acta'    => $date,
                    'envio'        => $NumberSend]);
            return $state;
        }

    }
    public function download(Request $request)
    {
        $idacta = $request->input("idacta");
        $consec = $request->input("consec");
        $oti    = $request->input("oti");
        $acta   = $request->input("acta");

        $search = DB::table('image_actas')
            ->where('id_acta', $idacta)
            ->get();

        $path = public_path('/public');

        $zipFileName = $acta . '.zip';
        $zip         = new ZipArchive;

        if ($zip->open($path . '/' . $zipFileName, ZipArchive::CREATE) === true) {
            // Add File in ZipArchive

            foreach ($search as $search) {
                $path1 = public_path('/public' . $search->url . $search->name_image);

                $zip->addFile($path1, $search->name_image);
            }
            $zip->close();
        }

        $headers = array(
            'Pragma'                    => 'public',
            'Expires'                   => '0',
            'Cache-Control'             => 'must-revalidate, post-check=0, pre-check=0',
            'Cache-Control'             => 'public',
            'Content-Description'       => 'File Transfer',
            'Content-type'              => 'application/octet-stream',
            'Content-Transfer-Encoding' => 'binary',

        );
        $filetopath = $path . '/' . $zipFileName;
        // Create Download Response
        if (file_exists($filetopath)) {

            return response()->download($filetopath, $zipFileName, $headers);
        }

    }
}
