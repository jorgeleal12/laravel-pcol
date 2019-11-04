<?php

namespace App\Http\Controllers\Lists;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MaterialListsController extends Controller
{

    public function query()
    {

        $Unity = DB::table('unity')
            ->select('unity.*')
            ->get();

        $Type_input = DB::table('type_input')
            ->select('type_input.*')
            ->get();

        $states = DB::table('states')
            ->select('states.*')
            ->get();

        return response()->json(['status' => 'ok', 'Type_input' => $Type_input, 'Unity' => $Unity, 'states' => $states], 200);

    }
}
