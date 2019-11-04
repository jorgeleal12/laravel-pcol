<?php

namespace App\Http\Controllers\SysInventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SysInventoryController extends Controller
{

    public function create(Request $request)
    {

        $type       = $request->input("data.type");
        $code       = $request->input("data.code");
        $user       = $request->input("data.user");
        $device     = $request->input("data.device");
        $brand      = $request->input("data.brand");
        $serial     = $request->input("data.serial");
        $procesador = $request->input("data.procesador");
        $ram        = $request->input("data.ram");
        $disk       = $request->input("data.disk");
        $os         = $request->input("data.os");
        $office     = $request->input("data.office");
        $license    = $request->input("data.license");
        $assignment = $request->input("data.assignment");
        $sede       = $request->input("data.sede");
        $inches     = $request->input("data.inches");
        $area       = $request->input("data.area");
        $extension  = $request->input("data.extension");
        $model      = $request->input("data.model");

        $create = DB::table('sys_inventory')
            ->insert([
                'type'            => $type,
                'code'            => $code,
                'user'            => $user,
                'name_device'     => $device,
                'brand'           => $brand,
                'serial'          => $serial,
                'procesador'      => $procesador,
                'ram'             => $ram,
                'disk'            => $disk,
                'os'              => $os,
                'office'          => $office,
                'license'         => $license,
                'assignment_date' => $assignment,
                'sede'            => $sede,
                'inches'          => $inches,
                'area'            => $area,
                'extension'       => $extension,
                'model'           => $model,
            ]);

        return response()->json(['status' => 'ok'], 200);
    }

    public function search(Request $request)
    {
        $code = $request->input("data.code");

        $search = DB::table('sys_inventory')
            ->where('code', $code)
            ->first();
        $search_history = DB::table('sysinventoryhistory')
            ->where('code', $code)
            ->get();

        return response()->json(['status' => 'ok', 'search' => $search, 'search_history' => $search_history], 200);
    }

    public function update(Request $request)
    {
        $type       = $request->input("data.type");
        $code       = $request->input("data.code");
        $user       = $request->input("data.user");
        $device     = $request->input("data.device");
        $brand      = $request->input("data.brand");
        $serial     = $request->input("data.serial");
        $procesador = $request->input("data.procesador");
        $ram        = $request->input("data.ram");
        $disk       = $request->input("data.disk");
        $os         = $request->input("data.os");
        $office     = $request->input("data.office");
        $license    = $request->input("data.license");
        $assignment = $request->input("data.assignment");
        $sede       = $request->input("data.sede");
        $inches     = $request->input("data.inches");
        $area       = $request->input("data.area");
        $extension  = $request->input("data.extension");
        $model      = $request->input("data.model");

        $update = DB::table('sys_inventory')
            ->where('code', $code)
            ->update([
                'type'            => $type,
                'code'            => $code,
                'user'            => $user,
                'name_device'     => $device,
                'brand'           => $brand,
                'serial'          => $serial,
                'procesador'      => $procesador,
                'ram'             => $ram,
                'disk'            => $disk,
                'os'              => $os,
                'office'          => $office,
                'license'         => $license,
                'assignment_date' => $assignment,
                'sede'            => $sede,
                'inches'          => $inches,
                'area'            => $area,
                'extension'       => $extension,
                'model'           => $model,
            ]);

        return response()->json(['status' => 'ok'], 200);
    }

    public function subir(Request $request)
    {

        $data = $request->input("data");

        for ($i = 0; $i < count($data); $i++) {

            $code       = isset($data[$i]["A"]) ? $data[$i]["A"] : null;
            $type       = isset($data[$i]["B"]) ? $data[$i]["B"] : null;
            $device     = isset($data[$i]["C"]) ? $data[$i]["C"] : null;
            $user       = isset($data[$i]["D"]) ? $data[$i]["D"] : null;
            $brand      = isset($data[$i]["E"]) ? $data[$i]["E"] : null;
            $serial     = isset($data[$i]["F"]) ? $data[$i]["F"] : null;
            $procesador = isset($data[$i]["G"]) ? $data[$i]["G"] : null;
            $ram        = isset($data[$i]["H"]) ? $data[$i]["H"] : null;
            $disk       = isset($data[$i]["I"]) ? $data[$i]["I"] : null;
            $os         = isset($data[$i]["J"]) ? $data[$i]["J"] : null;
            $office     = isset($data[$i]["K"]) ? $data[$i]["K"] : null;
            $license    = isset($data[$i]["L"]) ? $data[$i]["L"] : null;
            $assignment = isset($data[$i]["M"]) ? $data[$i]["M"] : null;
            $sede       = isset($data[$i]["N"]) ? $data[$i]["N"] : null;
            $inches     = isset($data[$i]["O"]) ? $data[$i]["O"] : null;
            $area       = isset($data[$i]["P"]) ? $data[$i]["P"] : null;
            $extension  = isset($data[$i]["Q"]) ? $data[$i]["Q"] : null;
            $model      = isset($data[$i]["R"]) ? $data[$i]["R"] : null;

            $search = DB::table('sys_inventory')
                ->where('code', $code)
                ->first();

            if (!$search) {

                $create = DB::table('sys_inventory')
                    ->insert([
                        'type'            => $type,
                        'code'            => $code,
                        'user'            => $user,
                        'name_device'     => $device,
                        'brand'           => $brand,
                        'serial'          => $serial,
                        'procesador'      => $procesador,
                        'ram'             => $ram,
                        'disk'            => $disk,
                        'os'              => $os,
                        'office'          => $office,
                        'license'         => $license,
                        'assignment_date' => $assignment,
                        'sede'            => $sede,
                        'inches'          => $inches,
                        'area'            => $area,
                        'extension'       => $extension,
                        'model'           => $model,
                    ]);
            } else {

            }

        }
        return response()->json(['status' => 'ok'], 200);
    }

    public function saveHistory(Request $request)
    {
        $stateDevice        = $request->input("data.stateDevice");
        $codeDevice         = $request->input("data.codeDevice");
        $userDevice         = $request->input("data.userDevice");
        $assignmentDevice   = $request->input("data.assignmentDevice");
        $deliveryDevice     = $request->input("data.deliveryDevice");
        $observationsDevice = $request->input("data.observationsDevice");

        $create = DB::table('sysinventoryhistory')
            ->insert([
                'state'        => $stateDevice,
                'code'         => $codeDevice,
                'user'         => $userDevice,
                'assignment'   => $assignmentDevice,
                'delivery'     => $deliveryDevice,
                'observations' => $observationsDevice,
            ]);

        return response()->json(['status' => 'ok'], 200);
    }

    public function updateHistory(Request $request)
    {

        $codeDevice     = $request->input("row.code");
        $deliveryDevice = $request->input("row.delivery");

        $update = DB::table('sysinventoryhistory')
            ->where('code', $codeDevice)
            ->update([
                'delivery' => $deliveryDevice,
            ]);

        return response()->json(['status' => 'ok'], 200);
    }

    public function search_inventory(Request $request)
    {
        $search = DB::table('sys_inventory')
            ->get();
        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

}
