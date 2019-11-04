<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Facades\App\ClassPhp\UpdateInventary;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdministratorController extends Controller
{
    //Methods Line

    public function createLine(Request $request)
    {

        $name_line = $request->input("nline");

        $create = DB::table('line')
            ->insert([
                'name_line' => $name_line,
            ]);
    }

    public function searchLine(Request $request)
    {
        $id_line = (int)$request->input("id_line");

        $search = DB::table('line')

            ->get();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function deleteLine(Request $request)
    {

        $id_line   = $request->input("row.Id_line");
        $name_line = $request->input("row.name_line");

        $delete = DB::table('line')
            ->where('Id_line', $id_line)
            ->delete();

        return response()->json(['status' => 'ok'], 200);
    }

    public function updateLine(Request $request)
    {

        $id_line   = $request->input("row.Id_line");
        $name_line = $request->input("row.name_line");

        $update = DB::table('line')
            ->where('Id_line', $id_line)
            ->update(['name_line' => $name_line]);

        return response()->json(['status' => 'ok'], 200);
    }

    //Subline Methods

    public function createSubline(Request $request)
    {

        $line         = $request->input("line");
        $name_subline = $request->input("nsubline");

        $create = DB::table('subline')
            ->insert([
                'name_subline' => $name_subline,
                'id_line'      => $line,
            ]);
    }

    public function searchSubline(Request $request)
    {
        $id_subline = (int)$request->input("id_subline");

        $search = DB::table('subline')

            ->get();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function deleteSubline(Request $request)
    {

        $id_subline   = $request->input("row.id_subline");
        $name_subline = $request->input("row.name_subline");

        $delete = DB::table('subline')
            ->where('id_subline', $id_subline)
            ->delete();

        return response()->json(['status' => 'ok'], 200);
    }

    public function updateSubline(Request $request)
    {

        $id_subline   = $request->input("row.id_subline");
        $name_subline = $request->input("row.name_subline");

        $update = DB::table('subline')
            ->where('id_subline', $id_subline)
            ->update(['name_subline' => $name_subline]);

        return response()->json(['status' => 'ok'], 200);

    }
    public function cargarLineSubline(Request $request)
    {
        $id_line = (int)$request->input("id_line");

        $cargarLine = DB::table('line')
            ->get();
        return response()->json(['status' => 'ok', 'search' => $cargarLine], 200);
    }

    //Dispatch Destination Methods

    public function createDesDis(Request $request)
    {

        $name_desdis = $request->input("ndesdis");

        $create = DB::table('destination_dispatches')
            ->insert([
                'destination_name' => $name_desdis,
            ]);
    }

    public function searchDesDis(Request $request)
    {
        $id_desdis = (int)$request->input("iddestination_dispatches");

        $search = DB::table('destination_dispatches')

            ->get();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function deleteDesDis(Request $request)
    {

        $id_desdis   = $request->input("row.iddestination_dispatches");
        $name_desdis = $request->input("row.destination_name");

        $delete = DB::table('destination_dispatches')
            ->where('iddestination_dispatches', $id_desdis)
            ->delete();

        return response()->json(['status' => 'ok'], 200);
    }

    public function updateDesDis(Request $request)
    {

        $id_desdis   = $request->input("row.iddestination_dispatches");
        $name_desdis = $request->input("row.destination_name");

        $update = DB::table('destination_dispatches')
            ->where('iddestination_dispatches', $id_desdis)
            ->update(['destination_name' => $name_desdis]);

        return response()->json(['status' => 'ok'], 200);
    }

    //Gang Methods

    public function createGang(Request $request)
    {

        $name_gang = $request->input("data.ngang");
        $company   = $request->input("company");

        $create = DB::table('gangs')
            ->insert([
                'gangs_name' => $name_gang,
                'company'    => $company,
            ]);
    }

    public function searchGang(Request $request)
    {
        $id_gang = (int)$request->input("idgangs");
        $company = $request->input("company");

        $search = DB::table('gangs')
            ->where('company', '=', $company)

            ->get();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function deleteGang(Request $request)
    {

        $id_gang   = $request->input("row.idgangs");
        $name_gang = $request->input("row.gangs_name");

        $delete = DB::table('gangs')
            ->where('idgangs', $id_gang)
            ->delete();

        return response()->json(['status' => 'ok'], 200);
    }

    public function updateGang(Request $request)
    {

        $id_gang   = $request->input("row.idgangs");
        $name_gang = $request->input("row.gangs_name");

        $update = DB::table('gangs')
            ->where('idgangs', $id_gang)
            ->update(['gangs_name' => $name_gang]);

        return response()->json(['status' => 'ok'], 200);
    }

    //Methods for work type

    public function createWorkType(Request $request)
    {

        $name_toin = $request->input("toin");

        $create = DB::table('tipos_obr_internas')
            ->insert([
                'tipos_obr_internas_name' => $name_toin,
            ]);
    }

    public function searchWorkType(Request $request)
    {
        $id_toin = (int)$request->input("idtipos_obr_internas");

        $search = DB::table('tipos_obr_internas')

            ->get();

        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function deleteWorkType(Request $request)
    {

        $id_toin   = $request->input("row.idtipos_obr_internas");
        $name_toin = $request->input("row.tipos_obr_internas_name");

        $delete = DB::table('tipos_obr_internas')
            ->where('idtipos_obr_internas', $id_toin)
            ->delete();

        return response()->json(['status' => 'ok'], 200);
    }

    public function updateWorkType(Request $request)
    {

        $id_toin   = $request->input("row.idtipos_obr_internas");
        $name_toin = $request->input("row.tipos_obr_internas_name");

        $update = DB::table('tipos_obr_internas')
            ->where('idtipos_obr_internas', $id_toin)
            ->update(['tipos_obr_internas_name' => $name_toin]);

        return response()->json(['status' => 'ok'], 200);
    }

    //Subwork type Methods

    public function createSWT(Request $request)
    {

        $stoin     = $request->input("stoin");
        $id_tipo   = $request->input("id_tipo");
        $priority  = $request->input("priority");
        $sub_state = $request->input("sub_state");
        $ans       = $request->input("ans");

        $create = DB::table('subtipo_obr_internas')
            ->insert([

                'subtipo_obr_internas_name' => $stoin,
                'id_tipo'                   => $id_tipo,
                'priority'                  => $priority,
                'sub_state'                 => $sub_state,
                'ans'                       => $ans,
            ]);
    }

    public function searchSWT(Request $request)
    {
        $id_swt = (int)$request->input("idsubtipo_obr_internas");

        $search = DB::table('subtipo_obr_internas')
            ->leftjoin('tipos_obr_internas', 'subtipo_obr_internas.id_tipo', 'tipos_obr_internas.idtipos_obr_internas')

            ->get();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function deleteSWT(Request $request)
    {

        $id_stoi      = $request->input("row.idsubtipo_obr_internas");
        $name_subline = $request->input("row.name_subline");

        $delete = DB::table('subtipo_obr_internas')
            ->where('idsubtipo_obr_internas', $id_stoi)
            ->delete();

        return response()->json(['status' => 'ok'], 200);
    }

    public function updateSWT(Request $request)
    {

        $id_stoi   = $request->input("row.idsubtipo_obr_internas");
        $stoin     = $request->input("row.subtipo_obr_internas_name");
        $priority  = $request->input("row.priority");
        $ans       = $request->input("row.ans");
        $sub_state = $request->input("row.sub_state");
        $id_tipo   = $request->input("row.id_tipo");

        $update = DB::table('subtipo_obr_internas')
            ->where('idsubtipo_obr_internas', $id_stoi)
            ->update([
                'subtipo_obr_internas_name' => $stoin,
                'priority'                  => $priority,
                'ans'                       => $ans,
                'sub_state'                 => $sub_state,
                'id_tipo'                   => $id_tipo,
            ]);

        return response()->json(['status' => 'ok'], 200);

    }

    public function cargarSWT(Request $request)
    {
        $id_wt = (int)$request->input("idsub_state");

        $cargarType = DB::table('tipos_obr_internas')
            ->get();
        return response()->json(['status' => 'ok', 'search' => $cargarType], 200);

    }

    public function cargarsSWT(Request $request)
    {
        $id_wt = (int)$request->input("idsub_state");

        $cargarType = DB::table('sub_state')
            ->get();
        return response()->json(['status' => 'ok', 'search' => $cargarType], 200);

    }

    //Update work Methods

    public function searchUpdateW(Request $request)
    {
        $consecutivo = $request->input("consecutivo");
        $company     = $request->input("company");

        $search = DB::table('worki')
            ->where('consecutive', $consecutivo)
            ->where('id_company', $company)
            ->get();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function deleteUpdateW(Request $request)
    {

        $id_worki = $request->input("id_worki");

        $delete = DB::table('worki')
            ->where('idworkI', $id_worki)
            ->delete();

        return response()->json(['status' => 'ok'], 200);
    }

    public function updateUpdateW(Request $request)
    {
        $id_worki       = $request->input("id_worki");
        $consecutive    = $request->input("consecutive");
        $Pedido         = $request->input("Pedido");
        $Municipio      = $request->input("municipi");
        $Instalacion    = $request->input("Instalacion");
        $worki_type_obr = $request->input("tipo");
        $idcontrato     = $request->input("contrato");

        $update = DB::table('worki')
            ->where('idworkI', $id_worki)
            ->update([
                'Municipio'      => $Municipio,
                'worki_type_obr' => $worki_type_obr,
                'idcontrato'     => $idcontrato,
            ]);

        return response()->json(['status' => 'ok'], 200);

    }

    public function cargarUpdateW(Request $request)
    {
        $id_wt = (int)$request->input("idtipos_obr_internas");

        $cargarType = DB::table('tipos_obr_internas')
            ->get();
        return response()->json(['status' => 'ok', 'search' => $cargarType], 200);

    }

    public function cargarSubstados(Request $request)
    {
        $id_ss = (int)$request->input("idsub_state");

        $cargarsubstate = DB::table('sub_state')

            ->get();
        return response()->json(['status' => 'ok', 'search' => $cargarsubstate], 200);

    }

    public function cargarSubtipos(Request $request)
    {
        $id_st = (int)$request->input("idsubtipo_obr_internas");

        $cargarsubtipo = DB::table('subtipo_obr_internas')

            ->get();
        return response()->json(['status' => 'ok', 'search' => $cargarsubtipo], 200);

    }

    public function cargarM(Request $request)
    {
        $id_wt = (int)$request->input("id_dane");

        $cargarM = DB::table('municipality')

            ->get();
        return response()->json(['status' => 'ok', 'search' => $cargarM], 200);

    }

    public function searchUpdateWot(Request $request)
    {
        $id_worki = $request->input("idworki");

        $search = DB::table('ot')
            ->where('id_obr', $id_worki)
            ->get();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function updateUpdateWot(Request $request)
    {
        $id_ot    = $request->input("id_ot");
        $substado = $request->input("substado");
        $subtipo  = $request->input("subtipo");
        var_dump($id_ot);

        $update = DB::table('ot')
            ->where('idOT', $id_ot)
            ->update([
                'sub_estado' => $substado,
                'sub_tipo'   => $subtipo,
            ]);

        return response()->json(['status' => 'ok'], 200);

    }

    //Dispatch Movements Methods

    public function createDispatchMov(Request $request)
    {

        $name_move = $request->input("nmove");

        $create = DB::table('dispatches_move')
            ->insert([
                'dispatches_move_name' => $name_move,
            ]);
    }

    public function searchDispatchMov(Request $request)
    {

        $search = DB::table('dispatches_move')

            ->get();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function deleteDispatchMov(Request $request)
    {

        $iddispatches_move    = $request->input("row.iddispatches_move");
        $dispatches_move_name = $request->input("row.dispatches_move_name");

        $delete = DB::table('dispatches_move')
            ->where('iddispatches_move', $iddispatches_move)
            ->delete();

        return response()->json(['status' => 'ok'], 200);
    }

    public function updateDispatchMov(Request $request)
    {
        $iddispatches_move    = $request->input("row.iddispatches_move");
        $dispatches_move_name = $request->input("row.dispatches_move_name");

        $update = DB::table('dispatches_move')
            ->where('iddispatches_move', $iddispatches_move)
            ->update(['dispatches_move_name' => $dispatches_move_name]);

        return response()->json(['status' => 'ok'], 200);
    }

    //Units Methods

    public function createUnits(Request $request)
    {

        $nunit = $request->input("nunity");

        $create = DB::table('unity')
            ->insert([
                'name_Unity' => $nunit,
            ]);
    }

    public function searchUnits(Request $request)
    {

        $search = DB::table('unity')

            ->get();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function deleteUnits(Request $request)
    {

        $idUnity    = $request->input("row.idUnity");
        $name_Unity = $request->input("row.name_Unity");

        $delete = DB::table('unity')
            ->where('idUnity', $idUnity)
            ->delete();

        return response()->json(['status' => 'ok'], 200);
    }

    public function updateUnits(Request $request)
    {

        $idUnity    = $request->input("row.idUnity");
        $name_Unity = $request->input("row.name_Unity");

        $update = DB::table('unity')
            ->where('idUnity', $idUnity)
            ->update(['name_Unity' => $name_Unity]);

        return response()->json(['status' => 'ok'], 200);
    }

    //input type Methods

    public function createInputType(Request $request)
    {

        $ninput = $request->input("ninput");

        $create = DB::table('type_input')
            ->insert([
                'name_Type' => $ninput,
            ]);
    }

    public function searchInputType(Request $request)
    {

        $search = DB::table('type_input')

            ->get();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function deleteInputType(Request $request)
    {

        $idType_input = $request->input("row.idType_input");
        $name_Type    = $request->input("row.name_Type");

        $delete = DB::table('type_input')
            ->where('idType_input', $idType_input)
            ->delete();

        return response()->json(['status' => 'ok'], 200);
    }

    public function updateInputType(Request $request)
    {
        $idType_input = $request->input("row.idType_input");
        $name_Type    = $request->input("row.name_Type");

        $update = DB::table('type_input')
            ->where('idType_input', $idType_input)
            ->update(['name_Type' => $name_Type]);

        return response()->json(['status' => 'ok'], 200);
    }

//Vehicles Methods

    public function createVehicles(Request $request)
    {

        $namev  = $request->input("namev");
        $nplaca = $request->input("nplaca");

        $create = DB::table('employees')
            ->insert([
                'name'                    => $namev,
                'Users_id_identification' => $nplaca,
                'id_clasificacion'        => 3,
                'last_name'               => "",
            ]);
    }

    public function searchVehicles(Request $request)
    {

        $search = DB::table('employees')
            ->where('id_clasificacion', 3)
            ->get();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function deleteVehicles(Request $request)
    {

        $idemployees = $request->input("row.idemployees");

        $delete = DB::table('employees')
            ->where('idemployees', $idemployees)
            ->delete();

        return response()->json(['status' => 'ok'], 200);
    }

    public function updateVehicles(Request $request)
    {
        $idemployees             = $request->input("row.idemployees");
        $name                    = $request->input("row.name");
        $Users_id_identification = $request->input("row.Users_id_identification");

        $update = DB::table('employees')
            ->where('idemployees', $idemployees)
            ->update([
                'name'                    => $name,
                'Users_id_identification' => $Users_id_identification,
            ]);

        return response()->json(['status' => 'ok'], 200);
    }

//Order status Methods

    public function createOrdSt(Request $request)
    {

        $name = $request->input("nestado");

        $create = DB::table('state_moves')
            ->insert([
                'name_moves' => $name,
            ]);
    }

    public function searchOrdSt(Request $request)
    {

        $search = DB::table('state_moves')

            ->get();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function deleteOrdSt(Request $request)
    {

        $idstate_moves = $request->input("row.idstate_moves");

        $delete = DB::table('state_moves')
            ->where('idstate_moves', $idstate_moves)
            ->delete();

        return response()->json(['status' => 'ok'], 200);
    }

    public function updateOrdSt(Request $request)
    {
        $idstate_moves = $request->input("row.idstate_moves");
        $name          = $request->input("row.name_moves");

        $update = DB::table('state_moves')
            ->where('idstate_moves', $idstate_moves)
            ->update([
                'name_moves' => $name,
            ]);

        return response()->json(['status' => 'ok'], 200);
    }

    //dac Methods
    public function subir(Request $request)
    {

        $data     = $request->input("data");
        $company  = $request->input("company");
        $contract = $request->input("contract");

        for ($i = 0; $i < count($data); $i++) {

            $dac_idoym = isset($data[$i]["A"]) ? $data[$i]["A"] : null;
            $date      = isset($data[$i]["D"]) ? $data[$i]["D"] : null;
            $id_mot    = isset($data[$i]["C"]) ? $data[$i]["C"] : null;
            $id_clasif = isset($data[$i]["B"]) ? $data[$i]["B"] : null;
            $obs       = isset($data[$i]["E"]) ? $data[$i]["E"] : null;

            if ($dac_idoym != null) {

                $insert = DB::table('dac_oym')
                    ->insert([
                        'dac_idoym'   => $dac_idoym,
                        'date'        => $date,
                        'id_mot'      => $id_mot,
                        'id_clasif'   => $date,
                        'obs'         => $obs,
                        'dac_company' => $company,
                        'idcontrato'  => $contract,

                    ]);
            } else {

            }

        }

    }

    //Meters Methods

    public function search(Request $request)
    {
        $nro = $request->input("nro");

        $search = DB::table('series')
            ->where('serie_nro_serie', $nro)

            ->get();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function update(Request $request)
    {
        $state_serie = $request->input("state_serie");
        $nro         = $request->input("nro");

        $search = DB::table('series')
            ->where('serie_nro_serie', $nro)
            ->first();

        $update_des = DB::table('detail_dispatches')
            ->where('idseries', $search->idseries)
            ->where('dispatches', $search->serie_iddepacho)
            ->update([
                'idseries' => null,
            ]);

        $update = DB::table('series')
            ->where('serie_nro_serie', $nro)
            ->update([
                'serie_estado'    => $state_serie,
                'idobr'           => null,
                'serie_iddepacho' => null,
                'serie_despacho'  => null,
            ]);

        return response()->json(['status' => 'ok'], 200);

    }

    //O y M Records Methods
    public function subirOymRecords(Request $request)
    {

        $consecutivo = $request->input("consectivo");
        $acta        = $request->input("acta");
        $carpeta     = public_path('/public/actas/' . $acta . '/');
        $number      = 0;
        for ($i = 0; $i < count($consecutivo); $i++) {

            $consectivo = isset($consecutivo[$i]["A"]) ? $consecutivo[$i]["A"] : 0;

            $search = DB::table('images_oym')
                ->where('id_oym', $consectivo)

                ->get();

            $search_oym = DB::table('oym')
                ->where('consecutive', $consectivo)
                ->first();

            $activity = DB::table('list_activity_oym')
                ->where('id_activity', $search_oym->activity)
                ->first();

            $path = $carpeta;

            $newfolder = $search_oym->cod_instalacion . '-' . $activity->name_activity;

            if (!File::exists($carpeta)) {

                File::makeDirectory($carpeta, 0777, true);

                if (!File::exists($path . $newfolder)) {

                    File::makeDirectory($path . $newfolder, 0777, true);
                }

                // Add File in ZipArchive
                $number = 1;
                foreach ($search as $search) {
                    $image = explode('.', $search->name_image);

                    if ($image[1] == 'jpg') {

                        $name_image = 'AGR-HDR-' . $search_oym->pedido . '-257-' . $number . '.jpg';

                        $path1 = public_path('/public' . $search->url . $search->name_image);

                        $path3 = public_path('/public' . $search->url . $search->name_image);

                        if (!File::exists($path3)) {

                        } else {
                            File::copy($path3, $path . $newfolder . '/' . $name_image);
                        }

                        $number++;
                    }

                    if ($image[1] == 'pdf') {
                        $name_image = 'AGR-HDR-' . $search_oym->pedido . '-201-' . $number . '.pdf';

                        $path1 = public_path('/public' . $search->url . $search->name_image);

                        $path3 = public_path('/public' . $search->url . $search->name_image);

                        if (!File::exists($path3)) {

                        } else {
                            File::copy($path3, $path . $newfolder . '/' . $name_image);
                        }

                        $number++;
                    }

                }

            } else {

                if (!File::exists($path . $newfolder)) {

                    File::makeDirectory($path . $newfolder, 0777, true);
                } else {

                }

                // Add File in ZipArchive

                $number = 1;
                foreach ($search as $search) {

                    $image = explode('.', $search->name_image);

                    if ($image[1] == 'jpg') {

                        $name_image = 'AGR-HDR-' . $search_oym->pedido . '-257-' . $number . '.jpg';

                        $path1 = public_path('/public' . $search->url . $search->name_image);

                        $path3 = public_path('/public' . $search->url . $search->name_image);

                        if (!File::exists($path3)) {

                        } else {

                            File::copy($path3, $path . $newfolder . '/' . $name_image);
                        }

                        $number++;
                    }
                    if ($image[1] == 'pdf') {
                        $name_image = 'AGR-HDR-' . $search_oym->pedido . '-201-' . $number . '.pdf';

                        $path1 = public_path('/public' . $search->url . $search->name_image);

                        $path3 = public_path('/public' . $search->url . $search->name_image);

                        if (!File::exists($path3)) {

                        } else {
                            File::copy($path3, $path . $newfolder . '/' . $name_image);
                        }

                        $number++;
                    }

                }

            }
        }

        return response()->json(['status' => 'ok', 'search' => true], 200);
    }

    //Charge Methods
    public function createCharge(Request $request)
    {

        $ncharge = $request->input("ncharge");
        $idclass = $request->input("idclass");

        $create = DB::table('charges')
            ->insert([
                'name_charges' => $ncharge,
                'id_class'     => $idclass,
            ]);
    }

    public function searchCharge(Request $request)
    {

        $search = DB::table('charges')

            ->get();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function deleteCharge(Request $request)
    {

        $idcharges = $request->input("row.idcharges");

        $delete = DB::table('charges')
            ->where('idcharges', $idcharges)
            ->delete();

        return response()->json(['status' => 'ok'], 200);
    }

    public function updateCharge(Request $request)
    {

        $idcharges    = $request->input("row.idcharges");
        $name_charges = $request->input("row.name_charges");
        $id_class     = $request->input("row.id_class");

        $update = DB::table('charges')
            ->where('idcharges', $idcharges)
            ->update([
                'name_charges' => $name_charges,
                'id_class'     => $id_class,
            ]);

        return response()->json(['status' => 'ok'], 200);
    }

    public function cargarclasschange(Request $request)
    {

        $cargarClass = DB::table('clas_charger')
            ->get();
        return response()->json(['status' => 'ok', 'search' => $cargarClass], 200);
    }

    public function cargarContrato(Request $request)
    {

        $cargarC = DB::table('contract')

            ->get();
        return response()->json(['status' => 'ok', 'search' => $cargarC], 200);
    }
    //SubCharge Methods

    public function createSubCharge(Request $request)
    {

        $charge         = $request->input("charge");
        $name_subcharge = $request->input("nsubcharge");

        $create = DB::table('sub_charge')
            ->insert([
                'sub_charge_name' => $name_subcharge,
                'id_charge'       => $charge,
            ]);
    }

    public function searchSubCharge(Request $request)
    {

        $search = DB::table('sub_charge')
            ->leftjoin('charges', 'sub_charge.id_charge', 'charges.idcharges')
            ->get();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function deleteSubCharge(Request $request)
    {

        $id_subcharge = $request->input("row.idsub_charge");

        $delete = DB::table('sub_charge')
            ->where('idsub_charge', $id_subcharge)
            ->delete();

        return response()->json(['status' => 'ok'], 200);
    }

    public function updateSubCharge(Request $request)
    {

        $id_subcharge   = $request->input("row.idsub_charge");
        $name_subcharge = $request->input("row.sub_charge_name");

        $update = DB::table('sub_charge')
            ->where('idsub_charge', $id_subcharge)
            ->update(['sub_charge_name' => $name_subcharge]);

        return response()->json(['status' => 'ok'], 200);

    }

    public function cargarChargeSubCharge(Request $request)
    {

        $cargarCharge = DB::table('charges')
            ->get();
        return response()->json(['status' => 'ok', 'search' => $cargarCharge], 200);
    }

    public function createDocuments(Request $request)
    {

        $doc         = $request->input("doc");
        $consecutive = $request->input("consecutive");
        $company     = $request->input("company");
        $contract    = $request->input("contract");

        $create = DB::table('consecutive')
            ->insert([
                'doc'         => $doc,
                'consecutive' => $consecutive,
                'id_company'  => $company,
                'idcontrac'   => $contract,
            ]);
        return response()->json(['status' => 'ok'], 200);
    }

    public function searchDocuments(Request $request)
    {

        $doc      = $request->input("doc");
        $company  = $request->input("company");
        $contract = $request->input("contract");

        $search = DB::table('consecutive')
            ->where('id_company', $company)
            ->where('doc', $doc)

            ->first();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    //act images Methods

    public function searchActImages(Request $request)
    {
        $acta    = $request->input("actanum");
        $company = $request->input("company");

        $search = DB::table('actasvecinda')
            ->where('acta', $acta)
            ->where('idcompany', $company)
            ->get();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function deleteActImages(Request $request)
    {
        $id = $request->input("idact");

        $delete = DB::table('actasvecinda')
            ->where('id_actav', $id)
            ->delete();

        return response()->json(['status' => 'ok'], 200);
    }

    public function updateActImages(Request $request)
    {
        $name = $request->input("row.acta");
        $id   = $request->input("row.id_actav");

        $update = DB::table('actasvecinda')
            ->where('id_actav', $id)
            ->update([
                'acta' => $name,
            ]);

        return response()->json(['status' => 'ok'], 200);

    }

    public function searchImagesActImages(Request $request)
    {
        $idSearch = $request->input("idSearch");

        $search = DB::table('image_actas')
            ->where('id_acta', $idSearch)
            ->get();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function deleteallActImages(Request $request)
    {
        $id = $request->input("params");

        $search_image = DB::table('image_actas')
            ->where('id_acta', $id)
            ->get();

        foreach ($search_image as $search) {

            $image = public_path('/public' . $search->url . $search->name_image);

            File::delete($image);

            if (!File::exists($image)) {

                $delete = DB::table('image_actas')
                    ->where('id_image', $search->id_image)
                    ->delete();
            } else {

            }
        }

        return response()->json(['status' => 'ok'], 200);
    }

    public function deleteoneActImages(Request $request)
    {
        $id = $request->input("params");

        $search_image = DB::table('image_actas')
            ->where('id_image', $id)
            ->first();

        $image = public_path('/public' . $search_image->url . $search_image->name_image);
        File::delete($image);

        if (!File::exists($image)) {
            $delete = DB::table('image_actas')
                ->where('id_image', $id)
                ->delete();
        } else {

        }

        return response()->json(['status' => 'ok'], 200);
    }

    public function copyImages(Request $request)
    {
        $company  = $request->input("company");
        $contract = $request->input("contract");

        $search = DB::table('actasvecinda')
            ->where('idcompany', $company)
            ->where('acta', '>', 1100)
            ->where('cierre', 1)
            ->get();

        $carpeta = public_path('/public/actas/domiciliarias/');

        if (!File::exists($carpeta)) {

            File::makeDirectory($carpeta, 0777, true);

        }

        foreach ($search as $search) {

            $id_actav = $search->id_actav;

            $search_images = DB::table('image_actas')
                ->where('id_acta', $id_actav)
                ->get();

            $acta   = $search->acta;
            $number = 0;

            foreach ($search_images as $search_images) {

                $path1 = public_path('/public' . $search_images->url . $search_images->name_image);

                if (!File::exists($path1)) {

                } else {

                    if (!File::exists($carpeta . $acta)) {

                        File::makeDirectory($carpeta . $acta, 0777, true);

                        File::copy($path1, $carpeta . $acta . '/' . 'IMAGEN_' . $number . '.jpg');
                    } else {
                        File::copy($path1, $carpeta . $acta . '/' . 'IMAGEN_' . $number . '.jpg');
                    }

                }

                $number++;
            }
        }
    }

    //Rename Files Methods
    public function renameFiles(Request $request)
    {

        $data       = $request->input("data");
        $nameFolder = $request->input("nameFolder");
        $from       = public_path('/public/CT - 105/');
        $to         = public_path('/public/');
        $ad         = '-3-1-';

        if (!File::exists($to . $nameFolder)) {

            File::makeDirectory($to . $nameFolder, 0777, true);

        } else {

        }

        for ($i = 0; $i < count($data); $i++) {

            $consecutivo = isset($data[$i]["A"]) ? $data[$i]["A"] : null;

            $pagina = isset($data[$i]["B"]) ? $data[$i]["B"] : null;

            $fecha = isset($data[$i]["C"]) ? $data[$i]["C"] : null;

            $date = explode("/", $fecha);

            $path3 = public_path('/public/CT - 105/' . $consecutivo . '.pdf');

            if (!File::exists($path3)) {

                $create = DB::table('renameFile')
                    ->insert([
                        'consecutivo' => $consecutivo,
                    ]);

            } else {

                File::copy($path3, $to . $nameFolder . '/' . $pagina . $ad . $date[0] . $date[1] . $date[2] . '.pdf');
            }

        }
        return response()->json(['status' => 'ok'], 200);
    }

    public function uploadUpdateDispatches(Request $request)
    {

        $data = $request->input("data");

        for ($i = 0; $i < count($data); $i++) {

            $ID_dispachet        = isset($data[$i]["A"]) ? $data[$i]["A"] : null;
            $consec              = isset($data[$i]["B"]) ? $data[$i]["B"] : null;
            $iddetail_dispatches = isset($data[$i]["C"]) ? $data[$i]["C"] : null;
            $cod_mater           = isset($data[$i]["D"]) ? $data[$i]["D"] : null;
            $cantidad            = isset($data[$i]["E"]) ? $data[$i]["E"] : null;
            $company             = 5;

            $search = DB::table('dispatches')
                ->where('iddispatches', $ID_dispachet)
                ->first();

            $cellar = $search->dispatches_cellar;

            $search = DB::table('detail_dispatches')
                ->where('iddetail_dispatches', $iddetail_dispatches)
                ->where('cod_mater', $cod_mater)
                ->first();

            $cant_anterior = $search->quantity;
            $tipo          = 'ATUALIZO DESPACHO';

            if ($cant_anterior >= $cantidad) {

                $cantidad_actual = $cant_anterior - $cantidad;

                UpdateInventary::AddInventary($company, $cellar, $cod_mater, $cantidad, $ID_dispachet, $consec, $tipo);
            }

            // UpdateInventary::cancular_cantidades($income, $income_cellar, $income_conse, $cod_mater, $cantidadan, $ceceived_amount, 'ATUALIZO INGRESO', $company);

            $update = DB::table('detail_dispatches')
                ->where('cod_mater', $cod_mater)
                ->where('iddetail_dispatches', $iddetail_dispatches)
                ->update([
                    'quantity' => $cantidad_actual,
                ]);

        }
        return response()->json(['status' => 'ok'], 200);
    }


}
