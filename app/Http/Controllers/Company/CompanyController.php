<?php

namespace App\Http\Controllers\Company;

use App\company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = company::all();
        return $company;
    }

    public function search(Request $request)
    {

        $idbusiness = (Int) $request->input("company");

        $company = DB::table('business')
            ->where('idbusiness', '=', $idbusiness)
            ->select('business.*')
            ->first();

        return response()->json(['status' => 'ok', 'company' => $company]);

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        //   $company_name       =  $request->input("data.company_name");
        // $nit                =  $request->input("data.nit");
        //$phone              =  $request->input("data.phone");
        //$address            =  $request->input("data.address");

        $company_name = $_POST['company_name'];
        $address      = $_POST['address'];
        $nit          = $_POST['nit'];
        $phone        = $_POST['phone'];

        $image = $_FILES;

        $name = $_FILES['image']['name'];
        $file = $_FILES['image']['tmp_name'];

        try {

            $create = DB::table('business')
                ->insert([
                    'company_name' => $company_name,
                    'address'      => $address,
                    'nit'          => $nit,
                    'phone'        => $phone,
                    'logo'         => $name,

                ]);

            \Storage::disk('imagen')->put($name, \File::get($file));

            $result = true;

        } catch (Exception $e) {
            $result = false;
        }

        return response()->json(['status' => 'ok', 'data' => $result], 200);
        //
    }

    public function update(Request $request)
    {

        //   $company_name       =  $request->input("data.company_name");
        // $nit                =  $request->input("data.nit");
        //$phone              =  $request->input("data.phone");
        //$address            =  $request->input("data.address");

        $company_name = $_POST['company_name'];
        $address      = $_POST['address'];
        $nit          = $_POST['nit'];
        $phone        = $_POST['phone'];
        $idbusiness   = $_POST['idbusiness'];
        $logo         = $_POST['logo'];

        $image = isset($_FILES) ? $_FILES : false;

        if ($image == false) {
            $name = $logo;
        } else {
            $name = $_FILES['image']['name'];
            $file = $_FILES['image']['tmp_name'];
            \Storage::disk('imagen')->put($name, \File::get($file));
        }

        try {

            $update = DB::table('business')
                ->where('idbusiness', '=', $idbusiness)
                ->update([
                    'company_name' => $company_name,
                    'address'      => $address,
                    'nit'          => $nit,
                    'phone'        => $phone,
                    'logo'         => $name,

                ]);

            $result = true;

        } catch (Exception $e) {
            $result = false;
        }

        return response()->json(['status' => 'ok', 'data' => $result, 'image' => $name], 200);
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
