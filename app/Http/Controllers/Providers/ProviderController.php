<?php

namespace App\Http\Controllers\Providers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProviderController extends Controller
{
    public $providers_name;

    public function autocomplete(Request $request)
    {

        $this->providers_name = $request->input("term");

        $providers = DB::table('providers')
            ->where('providers_name', 'like', $this->providers_name . '%')
            ->select('providers.*')
            ->orderBy('providers_name', 'ASC')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'providers' => $providers], 200);

    }

    public function autocomplete_code_provider(Request $request)
    {
        $this->code          = $request->input("term");
        $this->provider_list = $request->input("idlist");
        $provider            = $request->input("provider");

        $supply_providers = DB::table('supply_provider')
            ->join('materiales', 'supply_provider.supply_provider_code', '=', 'materiales.idmateriales')
            ->join('unity', 'materiales.unity', '=', 'unity.idUnity')
            ->where('supply_list', '=', $this->provider_list)
            ->where('supply_idprovider', '=', $provider)
            ->where('materiales.code', 'like', '%' . $this->code . '%')
            ->select('supply_provider.*', 'materiales.*', 'unity.name_Unity')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'supply_providers' => $supply_providers], 200);

    }

    public function autocomplete_description_provider(Request $request)
    {
        $this->code          = (String)$request->input("term");
        $this->provider_list = (INT)$request->input("idlist");
        $provider            = (INT)$request->input("provider");

        $supply_providers = DB::table('supply_provider')
            ->join('materiales', 'supply_provider.supply_provider_code', '=', 'materiales.idmateriales')
            ->join('unity', 'materiales.unity', '=', 'unity.idUnity')
            ->where('supply_list', '=', $this->provider_list)
            ->where('supply_idprovider', '=', $provider)
            ->where('materiales.description', 'like', '%' . $this->code . '%')
            ->select('supply_provider.*', 'materiales.*', 'unity.name_Unity')
            ->take(10)
            ->get();

        return response()->json(['status' => 'ok', 'supply_providers' => $supply_providers], 200);

    }

    public function insert_provider(Request $request)
    {
        $address     = (String)$request->input("data.address");
        $code        = (INT)$request->input("data.code");
        $contact     = mb_strtoupper($request->input("data.contact"));
        $date        = $request->input("data.date");
        $email       = (String)$request->input("data.email");
        $ext         = (String)$request->input("data.ext");
        $fax         = (String)$request->input("data.fax");
        $namep       = (String)$request->input("data.namep");
        $observation = mb_strtoupper($request->input("data.observation"));
        $phone       = (String)$request->input("data.phone");
        $providernit = (String)$request->input("data.providernit");
        $company     = (String)$request->input("company");
        $date        = $request->input("data.date");
        $digito      = (INT)$request->input("data.digito");

        $validate = ProviderController::exist($providernit);

        if (!$validate) {

            try {

                $insert_provider = DB::table('providers')

                    ->insertGetId([

                        'providers_nit'    => $providernit,
                        'providers_addres' => $address,
                        'providers_name'   => $namep,
                        'digito'           => $digito,
                        'date'             => $date,

                    ]);

                $list = ProviderController::GetList($company);

                $Insert_providerInfo = DB::table('providers_info')

                    ->insertgetid([

                        'id_provider'           => $insert_provider,
                        'phone_provider'        => $phone,
                        'fax_provider'          => $fax,
                        'mail_provider'         => $email,
                        'contact_provider'      => $contact,
                        'observations_provider' => $observation,
                        'ext__provider'         => $ext,
                        'list_provider'         => $list,
                        'providers_nit'         => $providernit,
                    ]);

                $result = true;
                $exist  = false;
            } catch (\Exception $e) {

                $result = false;
            }

            return response()->json(['status' => $result, 'data' => $insert_provider, 'exist' => $exist], 200);

        } else {

            $exist = true;
            return response()->json(['exist' => $exist], 200);
        }
    }

    public function GetList($company)
    {

        $list = DB::table('list_provider')->where('id_company', '=', $company)->select('List_provider.*')->first();
        return $list->idList_provider;

    }

    public function update_provider(Request $request)
    {

        $address     = (String)$request->input("data.address");
        $code        = $request->input("data.code");
        $contact     = mb_strtoupper($request->input("data.contact"));
        $date        = $request->input("data.date");
        $email       = (String)$request->input("data.email");
        $ext         = (String)$request->input("data.ext");
        $fax         = (String)$request->input("data.fax");
        $namep       = (String)$request->input("data.namep");
        $observation = mb_strtoupper($request->input("data.observation"));
        $phone       = (String)$request->input("data.phone");
        $providernit = (String)$request->input("data.providernit");
        $company     = (String)$request->input("company");
        $date        = $request->input("data.date");
        $digito      = $request->input("data.digito");
        $id_list     = $request->input("id_list");

        $update_provider = DB::table('providers')
            ->where('idproviders', '=', $code)
            ->update([
                'providers_name'   => $namep,
                'providers_addres' => $address,
                'digito'           => $digito,
            ]);

        $UpdateInfo = DB::table('providers_info')
            ->where('id_provider', '=', $code)
            ->where('list_provider', '=', $id_list)
            ->update([

                'phone_provider'        => $phone,
                'fax_provider'          => $fax,
                'mail_provider'         => $email,
                'observations_provider' => $observation,
                'ext__provider'         => $ext,
                'contact_provider'      => $contact,

            ]);

        if ($UpdateInfo) {

            $Insert_providerInfo = DB::table('providers_info')

                ->insertgetid([

                    'id_provider'           => $code,
                    'phone_provider'        => $phone,
                    'fax_provider'          => $fax,
                    'mail_provider'         => $email,
                    'contact_provider'      => $contact,
                    'observations_provider' => $observation,
                    'ext__provider'         => $ext,
                    'list_provider'         => $id_list,
                    'providers_nit'         => $providernit,
                ]);
        }

        return response()->json(['status' => true], 200);
    }

    public function search(Request $request)
    {

        $id     = (INT)$request->input("id");
        $idlist = (INT)$request->input("idlist");

        $result = DB::table('providers')
            ->where('idproviders', '=', $id)
            ->select('providers.*')
            ->first();

        $providers_info = DB::table('providers_info')
            ->where('providers_info.id_provider', '=', $id)
            ->where('list_provider.idList_provider', '=', $idlist)
            ->join('list_provider', 'providers_info.list_provider', '=', 'list_provider.idList_provider')
            ->select('providers_info.*')->first();

        $suply_provider = ProviderController::suply_provider($id, $idlist);

        return response()->json(['status' => 'ok', 'data' => $result, 'providers_info' => $providers_info, 'suply_provider' => $suply_provider], 200);

    }

    public function suply_provider($id, $idlist)
    {

        $suply_provider = DB::table('supply_provider')
            ->where('supply_idprovider', '=', $id)
            ->where('list_provider.idList_provider', '=', $idlist)
            ->leftjoin('materiales', 'supply_provider.supply_provider_code', '=', 'materiales.idmateriales')
            ->leftjoin('unity', 'materiales.unity', '=', 'unity.idUnity')
            ->leftjoin('list_provider', 'supply_provider.supply_list', '=', 'list_provider.idList_provider')
            ->select('supply_provider.*', 'materiales.description', 'unity.name_Unity', 'materiales.code')
            ->get();

        return $suply_provider;
    }

    public function exist($providernit)
    {

        $result1 = DB::table('providers')
            ->where('providers_nit', '=', $providernit)
            ->select('providers.*')
            ->first();

        return $result1;
    }

    public function query(Request $request)
    {
        $material = (INT)$request->input("material");

        $result = DB::table('supply_provider')
            ->where('idsupply_provider', '=', $material)
            ->join('materiales', 'supply_provider.supply_provider_code', '=', 'materiales.idmateriales')
            ->select('supply_provider.*', 'materiales.description', 'materiales.code')
            ->first();

        return response()->json(['status' => 'ok', 'result' => $result], 200);
    }

    public function edit_material(Request $request)
    {

        $id        = (INT)$request->input("data.idsupply_provider");
        $vluni     = (FLOAT)$request->input("data.supply_vlru");
        $descuento = (FLOAT)$request->input("data.supply_discount");
        $iva       = (FLOAT)$request->input("data.supply_iva");

        $vdescuento = (FLOAT)$request->input("data.vdescuento");
        $vtotal     = (FLOAT)$request->input("data.vtotal");

        $ProviderId = (FLOAT)$request->input("idprovider");
        $company    = (FLOAT)$request->input("company");

        try {

            $update_material = DB::table('supply_provider')
                ->where('idsupply_provider', '=', $id)
                ->update([

                    'supply_vlru'     => $vluni,
                    'supply_discount' => $descuento,
                    'supply_iva'      => $iva,
                    'vdescuento'      => $vdescuento,
                    'vtotal'          => $vtotal,

                ]);

            $result = true;

            $search_list = DB::table('list_provider')
                ->where('id_company', '=', $company)
                ->first();

            $suply_provider = ProviderController::suply_provider($ProviderId, $search_list->idList_provider);

        } catch (\Exception $e) {

            $result = false;
        }

        return response()->json(['status' => 'ok', 'result' => $result, 'suply_provider' => $suply_provider], 200);
    }

    public function addmaterial()
    {
        $material = DB::table('materiales')
            ->select('materiales.*')->get();

        return response()->json(['status' => 'ok', 'material' => $material], 200);
    }

    public function validate_mate(Request $request)
    {

        $material   = $request->input("material");
        $ProviderId = (INT)$request->input("provider");
        $company    = (INT)$request->input("company");
        $id_list    = (INT)$request->input("id_list");

        $validate = DB::table('supply_provider')
            ->where('supply_idprovider', '=', $ProviderId)
            ->where('supply_provider_code', '=', $material)
            ->where('list_provider.idList_provider', '=', $id_list)
            ->join('list_provider', 'supply_provider.supply_list', '=', 'list_provider.idList_provider')
            ->select('supply_provider.*')
            ->first();

        if (!$validate) {

            $insert_mate = true;

            $insert_mate = ProviderController::insert_material($id_list, $material, $ProviderId);

            $search_list = DB::table('list_provider')
                ->where('id_company', '=', $company)
                ->first();
            $suply_provider = ProviderController::suply_provider($ProviderId, $search_list->idList_provider);

        } else {

            $insert_mate    = false;
            $suply_provider = false;
        }

        return response()->json(['status' => 'ok', 'material' => $insert_mate, 'suply_provider' => $suply_provider], 200);

    }

    public function insert_material($id_list, $material, $ProviderId)
    {
        $insert = DB::table('supply_provider')
            ->insert([
                'supply_provider_code' => $material,
                'supply_list'          => $id_list,
                'supply_idprovider'    => $ProviderId,
            ]);
        $insert_mate = true;
        return $insert_mate;

    }

    public function delete_supply(Request $request)
    {

        $idsupply_provider = (INT)$request->input("idsupply_provider");
        $ProviderId        = (INT)$request->input("provider");
        $company           = (INT)$request->input("company");
        $id_list           = (INT)$request->input("id_list");

        $delete = DB::table('supply_provider')
            ->where('idsupply_provider', '=', $idsupply_provider)
            ->delete();

        $suply_provider = ProviderController::suply_provider($ProviderId, $id_list);

        if (!$delete) {
            $status = false;

        } else {
            $status = true;
        }

        return response()->json(['status' => 'ok', 'delete' => $status, 'suply_provider' => $suply_provider], 200);
    }

    public function search_provider()
    {

        $provider = DB::table('providers')
            ->select('providers.*')
            ->get();

        return response()->json(['status' => 'ok', 'provider' => $provider], 200);
    }

    public function list1(Request $request)
    {

        $company = $request->input("company");

        $list = DB::table('list_provider')
            ->where('id_company', '=', $company)
            ->select('list_provider.idList_provider')
            ->first();

        return response()->json(['status' => 'ok', 'list' => $list], 200);
    }

    //=============Nuevo===============================================

    public function insert_provider_n(Request $request)
    {
        $address     = $request->input("data.address");
        $code        = $request->input("data.code");
        $contact     = $request->input("data.contactname");
        $email       = $request->input("data.mail");
        $ext         = $request->input("data.ext");
        $fax         = $request->input("data.fax");
        $namep       = $request->input("data.name");
        $observation = $request->input("data.obs");
        $phone       = $request->input("data.phone");
        $providernit = $request->input("data.nitId");
        $company     = $request->input("company");
        $date        = $request->input("data.registerdate");
        $digito      = $request->input("data.digito");

        $validate = ProviderController::exist($providernit);

        if (!$validate) {

            $insert_provider = DB::table('providers')
                ->insertGetId([

                    'providers_nit'    => $providernit,
                    'providers_addres' => $address,
                    'providers_name'   => $namep,
                    'digito'           => $digito,
                    'date'             => $date,
                    'provee_id'        => $code,

                ]);

            $list = ProviderController::GetList($company);

            $Insert_providerInfo = DB::table('providers_info')
                ->insertgetid([

                    'id_provider'           => $insert_provider,
                    'phone_provider'        => $phone,
                    'fax_provider'          => $fax,
                    'mail_provider'         => $email,
                    'contact_provider'      => $contact,
                    'observations_provider' => $observation,
                    'ext__provider'         => $ext,
                    'list_provider'         => $id_list,
                    'providers_nit'         => $providernit,
                ]);
            $exist = true;
            return response()->json(['status' => $result, 'data' => $insert_provider, 'exist' => $exist], 200);

        } else {

            $exist = false;
            return response()->json(['exist' => $exist], 200);
        }
    }

    public function update_provider_n(Request $request)
    {

        $address     = $request->input("data.address");
        $code        = $request->input("data.code");
        $contact     = $request->input("data.contactname");
        $email       = $request->input("data.mail");
        $ext         = $request->input("data.ext");
        $fax         = $request->input("data.fax");
        $namep       = $request->input("data.name");
        $observation = $request->input("data.obs");
        $phone       = $request->input("data.phone");
        $providernit = $request->input("data.nitId");
        $company     = $request->input("company");
        $date        = $request->input("data.registerdate");
        $digito      = $request->input("data.digito");
        $id_list     = $request->input("id_list");
        $idproviderb = $request->input("idproviderb");
        $idprovidera = $request->input("idprovidera");

        $update_provider = DB::table('providers')
            ->where('idproviders', '=', $idproviderb)
            ->update([
                'providers_name'   => $namep,
                'providers_addres' => $address,
                'digito'           => $digito,
                'providers_nit'    => $providernit,
                'provee_id'        => $code,
            ]);

        $UpdateInfo = DB::table('providers_info')
            ->where('id_provider', '=', $idprovidera)
            ->update([

                'phone_provider'        => $phone,
                'fax_provider'          => $fax,
                'mail_provider'         => $email,
                'observations_provider' => $observation,
                'ext__provider'         => $ext,
                'contact_provider'      => $contact,
                'providers_nit'         => $providernit,
            ]);

        if (!$UpdateInfo) {
            $Insert_providerInfo = DB::table('providers_info')

                ->insertgetid([

                    'id_provider'           => $code,
                    'phone_provider'        => $phone,
                    'fax_provider'          => $fax,
                    'mail_provider'         => $email,
                    'contact_provider'      => $contact,
                    'observations_provider' => $observation,
                    'ext__provider'         => $ext,
                    'list_provider'         => $id_list,
                    'providers_nit'         => $providernit,
                ]);
        } else {

        }
        $result = true;

        return response()->json(['status' => $result], 200);
    }

    public function search_provider_n(Request $request)
    {

        $search = DB::table('providers')
            ->get();

        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function search_n(Request $request)
    {
        $idproviders = $request->input("idproviders");
        $search      = DB::table('providers')
            ->leftjoin('providers_info', 'providers.idproviders', 'providers_info.idproviders_info')
            ->where('idproviders', '=', $idproviders)
            ->where('idproviders_info', '=', $idproviders)
            ->first();

        $search_material = DB::table('supply_provider')
            ->leftjoin('materiales', 'supply_provider.idsupply_provider', 'materiales.idmateriales')
            ->where('supply_idprovider', '=', $idproviders)

            ->get();
        return response()->json(['status' => 'ok', 'search' => $search, 'search_materials' => $search_material], 200);
    }

    public function select_edit_mate_n(Request $request)
    {
        $idsupply_provider = $request->input("idsupply_provider");
        $search            = DB::table('supply_provider')
            ->where('idsupply_provider', '=', $idsupply_provider)
            ->first();

        return response()->json(['status' => 'ok', 'search' => $search], 200);
    }

    public function edit_material_n(Request $request)
    {
        $idmat         = $request->input("data.idmat");
        $codemat       = $request->input("data.codemat");
        $vunitario     = $request->input("data.vunitario");
        $descuento     = $request->input("data.descuento");
        $vcondescuento = $request->input("data.vcondescuento");
        $iva           = $request->input("data.iva");
        $vtotal        = $request->input("data.vtotal");

        $update = DB::table('supply_provider')
            ->where('idsupply_provider', '=', $idmat)
            ->update([
                'supply_provider_code' => $codemat,
                'supply_vlru'          => $vunitario,
                'supply_discount'      => $descuento,
                'vdescuento'           => $vcondescuento,
                'supply_iva'           => $iva,
                'vtotal'               => $vtotal,
            ]);

        return response()->json(['status' => 'ok'], 200);
    }

    public function matirials_n(Request $request)
    {
        $material = DB::table('materiales')
            ->get();

        return response()->json(['status' => 'ok', 'material' => $material], 200);
    }

    public function selectNewMat_n(Request $request)
    {

        $idprovider = $request->input("idprovider");
        $idmat      = $request->input("newrow.idmateriales");
        echo $idmat;

        $material = DB::table('supply_provider')
            ->insert([
                'supply_provider_code' => $idmat,
                'supply_idprovider'    => $idprovider,
            ]);

        return response()->json(['status' => 'ok'], 200);
    }

}
