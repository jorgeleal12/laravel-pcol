<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|header('Access-Control-Allow-Origin: http://myclient.com');
header('Access-Control-Allow-Credentials: true');
 */

Route::group(['middleware' => 'cors'], function () {
    Route::post('/user', 'User\UserController@readJson');
    Route::post('/user/create', 'User\UserController@create');
    Route::post('/user/change', 'User\UserController@change');
    Route::post('/user/search_init', 'User\UserController@search_init');
    Route::post('/user/update_user', 'User\UserController@update_user');
    Route::post('/user/user_search', 'User\UserController@user_search');
    Route::post('/user/add_contract', 'User\UserController@add_contract');
    Route::post('/user/search_contract', 'User\UserController@search_contract');
    Route::post('/user/delete_contract', 'User\UserController@delete_contract');

    Route::post('/user/session_movil', 'User\UserController@session_movil');

    Route::post('/profiles', 'P_profile\P_profilesController@Profiles');

//rutas de materiales
    Route::post('/material', 'Materiales\MaterialController@query');
    Route::post('/material/create', 'Materiales\MaterialController@create');
    Route::post('/material/update', 'Materiales\MaterialController@update');
    Route::post('/material/delete', 'Materiales\MaterialController@delete');
    Route::get('/material/autocomplete', 'Materiales\MaterialController@AutoQueryCode');
    Route::get('/material/autocompletedesc', 'Materiales\MaterialController@AutoQueryDescry');
    Route::post('/material/inventary', 'Materiales\MaterialController@inventary');
    Route::post('/material/historico', 'Materiales\MaterialController@historico');

    Route::get('/employee/autocomplete_employee', 'Employee\EmployeeController@autocomplete_employee');
    Route::get('/employee/searc_employee', 'Employee\EmployeeController@searc_employee');
    Route::post('/employee/insert_employee', 'Employee\EmployeeController@insert_employee');
    Route::post('/employee/search_employee', 'Employee\EmployeeController@search_employee');
    Route::post('/employee/upload_image', 'Employee\EmployeeController@upload_image');
    Route::post('/employee/update_employee', 'Employee\EmployeeController@update_employee');
    Route::post('/employee/view_contract', 'Employee\EmployeeController@view_contract');
    Route::post('/employee/request_contract', 'Employee\EmployeeController@request_contract');
    Route::post('/employee/update_contract', 'Employee\EmployeeController@update_contract');

    Route::post('/employee/insert_contrat', 'Employee\EmployeeController@insert_contrat');
    Route::post('/employee/get_SubCharge', 'Employee\EmployeeController@get_SubCharge');
    Route::post('/employee/charge', 'Employee\EmployeeController@charge');

    Route::get('/material/query_inventmate_code', 'Materiales\MaterialController@query_inventmate_code');
    Route::get('/material/query_inventmate_descrip', 'Materiales\MaterialController@query_inventmate_descrip');

//list
    Route::post('/state_moves', 'Lists\ListsController@state_moves');
    Route::post('/income_move', 'Lists\ListsController@income_move');
    Route::post('/cellar', 'Lists\ListsController@cellar');
    Route::post('/dispatches/dispatches_move', 'Lists\ListsController@dispatches_move');
    Route::post('/dispatches/destination_dispatches', 'Lists\ListsController@destination_dispatches');
    Route::post('/departamentos/departamentos', 'Lists\ListsController@departamentos');
    Route::post('/departamentos/municipios', 'Lists\ListsController@municipios');

    //Route::post('departamentos/municipios', 'Lists\ListsController@municipios');

    Route::post('list/sexo', 'Lists\ListsController@sexo');
    Route::post('list/account_type', 'Lists\ListsController@account_type');
    Route::post('list/bank', 'Lists\ListsController@bank');
    Route::post('list/eps', 'Lists\ListsController@eps');
    Route::post('list/pensions', 'Lists\ListsController@pensions');
    Route::post('list/arl', 'Lists\ListsController@arl');
    Route::post('list/states', 'Lists\ListsController@states');
    Route::post('list/type_contract', 'Lists\ListsController@type_contract');
    Route::post('list/company', 'Lists\ListsController@company');

    Route::post('list/charges', 'Lists\ListsController@charges');
    Route::post('list/clasificaciones', 'Lists\ListsController@clasificaciones');
    Route::post('list/education_level', 'Lists\ListsController@education_level');
    Route::post('list/profiles', 'Lists\ListsController@profiles');
    Route::post('list/contract', 'Lists\ListsController@contract');
    Route::post('list/gangs', 'Lists\ListsController@gangs');
    Route::post('list/civil_status', 'Lists\ListsController@civil_status');
    Route::post('list/place_of_work', 'Lists\ListsController@place_of_work');
    Route::post('list/location', 'Lists\ListsController@location');
    Route::post('list/local_contract', 'Lists\ListsController@local_contract');
    Route::post('list/list_profiles', 'Lists\ListsController@list_profiles');
    Route::post('list/type_charges', 'Lists\ListsController@type_charges');
    Route::post('list/list_tipeext', 'Lists\ListsController@list_tipeext');
    Route::post('list/state_ext', 'Lists\ListsController@state_ext');

    Route::post('list/state_anillo', 'Lists\ListsController@state_anillo');
    Route::post('list/type_obr_anillo', 'Lists\ListsController@type_obr_anillo');
    Route::post('list/tipo_s_item', 'Lists\ListsController@tipo_s_item');
    Route::post('list/tipo_item', 'Lists\ListsController@tipo_item');
    Route::post('list/clasificacion_item', 'Lists\ListsController@clasificacion_item');

    Route::post('list/tipo_medidor', 'Lists\ListsController@tipo_medidor');
    Route::post('list/state_activity', 'Lists\ListsController@state_activity');

//providers
    Route::get('/provider/autocomplete', 'Providers\ProviderController@autocomplete');
    Route::get('/provider/autocomplete_code', 'Providers\ProviderController@autocomplete_code_provider');
    Route::get('/provider/autocomplete_description_provider', 'Providers\ProviderController@autocomplete_description_provider');

    Route::post('/provider/insert_provider', 'Providers\ProviderController@insert_provider');
    Route::post('/provider/update_provider', 'Providers\ProviderController@update_provider');
    Route::post('/provider/search', 'Providers\ProviderController@search');
    Route::post('/provider/query', 'Providers\ProviderController@query');

    Route::post('/provider/add', 'Providers\ProviderController@addmaterial');
    Route::post('/provider/validate', 'Providers\ProviderController@validate_mate');
    Route::post('/provider/list', 'Providers\ProviderController@list1');
    Route::post('/provider/edit', 'Providers\ProviderController@edit_material');
    Route::post('/provider/delete_supply', 'Providers\ProviderController@delete_supply');
    Route::post('/provider/search_provider', 'Providers\ProviderController@search_provider');

//compras
    Route::post('/purchase/create', 'purchase\PurchaseController@create');
    Route::post('/purchase/search', 'purchase\PurchaseController@search_order');
    Route::post('/purchase/search_detail', 'purchase\PurchaseController@search_purchases');
    Route::post('/purchase/update', 'purchase\PurchaseController@update');
    Route::post('/purchase/delete', 'purchase\PurchaseController@delete');
    Route::get('/purchase/print', 'purchase\PurchasePrintController@print');

    // DESPACHOS

    Route::post('/dispatche/insert', 'Dispatche\DispatcheController@insert');
    Route::post('/dispatche/update', 'Dispatche\DispatcheController@update');
    Route::post('/dispatche/search', 'Dispatche\DispatcheController@search_head');
    Route::post('/dispatche/search_dispatches', 'Dispatche\DispatcheController@search_dispatches');
    Route::post('/dispatche/search_dispatches_refunt', 'Dispatche\DispatcheController@search_dispatches_refunt');
    Route::post('/dispatche/series_delet', 'Dispatche\DispatcheController@series_delet');

    //INGRESOS
    Route::post('/income/search_income', 'Income\IncomeController@search_income');

    Route::post('/income/create', 'Income\IncomeController@create');
    Route::post('/income/search_date', 'Income\IncomeController@search_date');
    Route::post('/income/search', 'Income\IncomeController@search');
    Route::post('/income/update', 'Income\IncomeController@update');
    Route::post('/income/delete', 'Income\IncomeController@delete');
    Route::post('/income/editpurchase', 'Income\IncomeController@editpurchase');
    Route::post('/income/edit_mate', 'Income\IncomeController@edit_mate');
    Route::get('/income/print', 'Income\IncomePrintController@print');

    Route::post('/detailspurchase/create', 'purchase\DetailsController@create');
    Route::post('/listmateriales', 'Lists\MaterialListsController@query');
    Route::post('/permission', 'Permission\PermissionsController@profile');
    Route::post('/permission/obr', 'Permission\PermissionsController@obr');

    Route::post('/permission/profile_search', 'Permission\PermissionsController@profile_search');
    Route::post('/permission/create_profile', 'Permission\PermissionsController@create_profile');
    Route::post('/permission/update_permisos', 'Permission\PermissionsController@update_permisos');
    Route::post('/permission/create_permise', 'Permission\PermissionsController@create_profile');
    Route::post('/permission/update_permits', 'Permission\PermissionsController@update_permits');

    Route::post('/permission/contract_employee', 'Permission\PermissionsController@contract_employee');

    Route::get('/company', 'Company\CompanyController@index');
    Route::post('/company/search', 'Company\CompanyController@search');
    Route::post('/company/create', 'Company\CompanyController@create');
    Route::post('/company/update', 'Company\CompanyController@update');

    //REINTEGROS
    Route::post('/refund/create', 'Refund\RefundController@create');
    Route::post('/refund/search_date_refund', 'Refund\RefundController@search_date_refund');
    Route::post('/refund/search_refund', 'Refund\RefundController@search_refund');
    Route::post('/refund/update', 'Refund\RefundController@update');

    Route::post('/refund/search_massive', 'Massive_Refund\Massive_RefoundController@search_massive');
    Route::post('/refund/massive_refound', 'Massive_Refund\Massive_RefoundController@massive_refound');
    Route::post('/refund/insert', 'Massive_Refund\Massive_RefoundController@insert');

    Route::post('/refund/update_head', 'Massive_Refund\Massive_RefoundController@update_head');
    Route::post('/refund/search_refund_massive', 'Massive_Refund\Massive_RefoundController@search_refund_massive');

    // traslados
    Route::post('/transfer/insert', 'Transfer\TransferController@InsertHead');
    Route::post('/transfer/search', 'Transfer\TransferController@search');
    Route::post('/transfer/searchhead', 'Transfer\TransferController@searchhead');
    Route::post('/transfer/updatehead', 'Transfer\TransferController@updatehead');

    //external
    Route::post('/external/insert', 'External\ExternalController@insert');
    Route::post('/external/update', 'External\ExternalController@update');
    Route::post('/external/search_consec', 'External\ExternalController@search_consec');
    Route::post('/external/insert_anillo', 'External\ExternalController@insert_anillo');
    Route::get('/external/autoconsecutive', 'External\ExternalController@autoconsecutive');
    Route::get('/external/oti', 'External\ExternalController@oti');
    Route::post('/external/consecutive', 'External\ExternalController@consecutive');
    Route::post('/external/search_municipaly', 'External\ExternalController@search_municipaly');
    Route::post('/external/search_oti', 'External\ExternalController@search_oti');

    Route::post('/external/update_anillo', 'External\ExternalController@update_anillo');
    Route::post('/external/save_presupuesto_item', 'External\ExternalController@save_presupuesto_item');
    Route::get('/external/autocomplecode', 'Items\ItemsController@autocomplecodeexternal');
    Route::get('/external/autocomplecodetopo', 'Items\ItemsController@autocomplecodetopo');

    Route::get('/external/autocomplecode4', 'Items\ItemsController@autocomplecodeexternal4');
    Route::get('/external/autocomplename4', 'Items\ItemsController@autocomplename4');

    Route::get('/items/autocompleinternal', 'Items\ItemsController@autocompleinternal');
    Route::get('/items/autocomplenameinternas', 'Items\ItemsController@autocomplenameinternas');
    Route::get('/external/autocomplename', 'Items\ItemsController@autocomplename');

    Route::get('/items/autocompleoym', 'Items\ItemsController@autocompleoym');
    Route::get('/items/autocomplenameoym', 'Items\ItemsController@autocomplenameoym');

    Route::post('/external/search_presupuesto_item', 'External\ExternalController@search_presupuesto_item');
    Route::post('/external/delete_items', 'External\ExternalController@delete_items');
    Route::post('/external/insert_dobra', 'External\ExternalController@insert_dobra');
    Route::post('/external/searc_detalle_obra', 'External\ExternalController@searc_detalle_obra');
    Route::post('/external/searc_detalle_obra_edit', 'External\ExternalController@searc_detalle_obra_edit');
    Route::post('/external/update_dobra', 'External\ExternalController@update_dobra');
    Route::post('/external/save_dobra', 'External\ExternalController@save_dobra');
    Route::post('/external/search_dobra', 'External\ExternalController@search_dobra');
    Route::post('/external/delete_dobra', 'External\ExternalController@delete_dobra');
    Route::post('/external/save_item_cbr', 'External\ExternalController@save_item_cbr');
    Route::post('/external/search_item_cbr', 'External\ExternalController@search_item_cbr');
    Route::post('/external/delete_item_cbr', 'External\ExternalController@delete_item_cbr');
    Route::post('/external/save_mate', 'External\ExternalController@save_mate');
    Route::post('/external/search_mate', 'External\ExternalController@search_mate');
    Route::post('/external/delete_mate', 'External\ExternalController@delete_mate');
    Route::post('/external/save_activity', 'External\ExternalController@save_activity');
    Route::post('/external/search_activity', 'External\ExternalController@search_activity');
    Route::post('/external/search_params_oti', 'External\ExternalController@search_params_oti');
    Route::get('/external/autocomplete_addrees', 'External\ExternalController@autocomplete_addrees');
    Route::post('/external/search_params_addrees', 'External\ExternalController@search_params_addrees');
    Route::post('/external/search_params_consec', 'External\ExternalController@search_params_consec');
    Route::post('/external/delete_detalle_obra', 'External\ExternalController@delete_detalle_obra');
    Route::post('/external/send_image', 'External\ExternalController@send_image');
    Route::post('/external/lis_type_acta', 'External\ExternalController@lis_type_acta');
    Route::post('/external/import_presu', 'External\ExternalController@import_presu');
    Route::post('/external/search_obr_dispachet', 'External\ExternalController@search_obr_dispachet');
    Route::post('/external/delete_activity', 'External\ExternalController@delete_activity');
    Route::post('/external/update_activity', 'External\ExternalController@update_activity');

    Route::post('/external/saveacta', 'External\ExternalController@saveacta');
    Route::post('/external/search_actas', 'External\ExternalController@search_actas');
    Route::post('/external/update_acta', 'External\ExternalController@update_acta');

    Route::post('/external/oti_movil', 'External\ExternalController@oti_movil');
    Route::post('/external/acta_image', 'External\ExternalController@acta_image');
    Route::post('/external/acta_image1', 'External\ExternalController@acta_image1');
    Route::post('/external/send_mail', 'External\ExternalController@send_mail');
    Route::post('/external/search_act', 'External\ExternalController@search_act');
    Route::post('/external/imagesend_acta', 'External\ExternalController@imagesend_acta');
    Route::post('/external/search_imageactas', 'External\ExternalController@search_imageactas');
    Route::post('/external/imagesend_ext', 'External\ExternalController@imagesend_ext');
    Route::post('/external/view_image', 'External\ExternalController@view_image');

    Route::post('/external/create_acta', 'External\ExternalController@create_acta');
    Route::post('/external/create_acta1', 'External\ExternalController@create_acta1');

    Route::post('/external/searchidobr', 'External\ExternalController@searchidobr');

    //rutas de items de cobro
    Route::post('/items/search', 'Items\ItemsController@search');
    Route::post('/items/insert', 'Items\ItemsController@insert');
    Route::post('/items/update', 'Items\ItemsController@update');
    Route::post('/items/state_items', 'Items\ItemsController@state_items');

    Route::post('/items/delete', 'Items\ItemsController@delete');

    //contratos empresariales

    Route::post('/contract/create', 'Contract\ContractController@create');
    Route::post('/contract/search', 'Contract\ContractController@search');
    Route::post('/contract/update', 'Contract\ContractController@update');

    Route::post('/series/save', 'Series\SeriesController@save');
    Route::post('/series/search', 'Series\SeriesController@search');
    Route::post('/series/update', 'Series\SeriesController@update');
    Route::post('/series/sprint', 'Series\SeriesPrintController@sprint');
    Route::post('/series/delete', 'Series\SeriesController@delete');
    Route::post('/series/save_series', 'Series\SeriesController@save_series');
    Route::get('/series/search_series', 'Series\SeriesController@search_series');
    Route::post('/series/searchs', 'Series\SeriesController@searchs');

    Route::get('/interna/search_consec', 'Interna\InternalController@search_consec');
    Route::get('/interna/search_pedido', 'Interna\InternalController@search_pedido');
    Route::get('/interna/search_cedula', 'Interna\InternalController@search_cedula');
    Route::get('/interna/search_address', 'Interna\InternalController@search_address');

    Route::get('/interna/search_consec_dispache', 'Interna\InternalController@search_consec_dispache');

    Route::get('/interna/search_instal', 'Interna\InternalController@search_instal');
    Route::post('/internal/searchpedido', 'Interna\InternalController@searchpedido');
    Route::post('/internal/cedula', 'Interna\InternalController@searchcedula');
    Route::post('/internal/address', 'Interna\InternalController@searchaddress');
    Route::post('/internal/install', 'Interna\InternalController@searchinstall');
    Route::post('/internal/searchobr', 'Interna\InternalController@searchobr');

    Route::post('/internal/Tipo_Anillo', 'Interna\InternalController@Tipo_Anillo');
    Route::post('/internal/Tipo_Empalme', 'Interna\InternalController@Tipo_Empalme');
    Route::post('/internal/Accesorio', 'Interna\InternalController@Accesorio');
    Route::post('/internal/Permiso_Ruptura', 'Interna\InternalController@Permiso_Ruptura');
    Route::post('/internal/Estado_Acometida', 'Interna\InternalController@Estado_Acometida');

    Route::post('/internal/update', 'Interna\InternalController@update');
    Route::post('/internal/pdf', 'Interna\InternalController@pdf');
    Route::post('/internal/clasificacion', 'Interna\InternalController@clasificacion');
    Route::post('/internal/motivos_dac', 'Interna\InternalController@motivos_dac');
    Route::post('/internal/dac', 'Interna\InternalController@dac');
    Route::post('/internal/save_dac', 'Interna\InternalController@save_dac');
    Route::post('/internal/search_dac', 'Interna\InternalController@search_dac');
    Route::post('/internal/update_dac', 'Interna\InternalController@update_dac');
    Route::post('/internal/subtipo_obr_internas', 'Interna\InternalController@subtipo_obr_internas');
    Route::post('/internal/sub_state', 'Interna\InternalController@sub_state');
    Route::post('/internal/update_ot', 'Interna\InternalController@update_ot');
    Route::post('/internal/tipo_obr', 'Interna\InternalController@tipo_obr');
    Route::post('/internal/state', 'Interna\InternalController@state');
    Route::post('/internal/search_histo', 'Interna\InternalController@search_histo');
    Route::post('/internal/search_itemsapli', 'Interna\InternalController@search_itemsapli');
    Route::post('/internal/item_ap', 'Interna\InternalController@item_ap');
    Route::post('/internal/itemaplic_update', 'Interna\InternalController@itemaplic_update');
    Route::post('/internal/itemaplic_inser', 'Interna\InternalController@itemaplic_inser');
    Route::post('/internal/itemaplic_delet', 'Interna\InternalController@itemaplic_delet');
    Route::post('/internal/search_consecutive', 'Interna\InternalController@search_consecutive');
    Route::post('/internal/serie_medidor', 'Interna\InternalController@serie_medidor');
    Route::post('/internal/send_image', 'Interna\InternalController@image_upload');
    Route::post('/internal/search_image', 'Interna\InternalController@search_image');
    Route::post('/internal/movepdf', 'Interna\InternalController@movepdf');

    Route::post('/internal/search_idobr', 'Interna\InternalController@search_idobr');

    Route::post('/internal/search_recorrodor', 'Interna\TravelController@search_recorrodor');
    Route::post('/internal/saverecorredor', 'Interna\TravelController@saverecorredor');
    Route::post('/internal/programada', 'Interna\TravelController@programada');

    Route::post('/internal/search_porprogramar', 'Interna\TravelController@search_porprogramar');
    Route::post('/internal/saveprogramacion', 'Interna\TravelController@saveprogramacion');

    Route::post('/items_internas/insert_items', 'Interna\ItemsController@insert');
    Route::post('/items_internas/search_items', 'Interna\ItemsController@search_items');
    Route::post('/items_internas/delete_items', 'Interna\ItemsController@delete_items');

    Route::post('/material_internas/insert_mate', 'Interna\MaterialController@insert');
    Route::post('/material_internas/search_material', 'Interna\MaterialController@searchmaterial');
    Route::post('/material_internas/delete_material', 'Interna\MaterialController@delete_material');

    Route::post('/activity/save_activitys', 'Interna\ActivityController@save_activitys');
    Route::post('/activity/delete_activitys', 'Interna\ActivityController@delete_activitys');

    Route::post('/activity/search_activitys', 'Interna\ActivityController@search');

    Route::post('/impresion/search', 'Interna\ImpresionController@search');
    Route::post('/impresion/search_progra', 'Interna\ImpresionController@search_progra');
    Route::get('/impresion/printliquidacion', 'Interna\ImpresionController@printliquidacion');
    Route::get('/impresion/printfc', 'Interna\ImpresionController@printfc');

    Route::post('/activities/save', 'Activities\ActivitiesController@save');
    Route::post('/activities/search', 'Activities\ActivitiesController@search');
    Route::post('/activities/update', 'Activities\ActivitiesController@update');
    Route::post('/activities/delete', 'Activities\ActivitiesController@delete');
    Route::get('/activities/autocomple', 'Activities\ActivitiesController@autocomple');

//importar  obra
    Route::post('/importar/obra', 'Importobr\ImportObrController@data');
    Route::post('/importar/check', 'Importobr\ImportObrController@check');
    Route::post('/importar/adress_medellin', 'Importobr\ImportObrController@adress_medellin');
    Route::post('/payment/search_payment', 'Payment\PaymentController@search_payment');

    Route::post('/payment/search_total', 'Payment\PaymentController@search_total');
    Route::post('/payment/pay', 'Payment\PaymentController@pay');
    Route::post('/payment/searchpay', 'Payment\PaymentController@searchpay');
    Route::post('/payment/search_payupdate', 'Payment\PaymentController@search_payupdate');
    Route::post('/payment/payupdate', 'Payment\PaymentController@payupdate');
    Route::get('/pay_activity/print', 'Payment\PrintController@print');

    Route::post('/movil/search_programming', 'Movil\MovilController@search_programming');
    Route::post('/movil/search_programming2', 'Movil\MovilController@search_programming2');
    Route::post('/movil/search_programming_oym', 'Movil\MovilController@search_programming_oym');

    Route::post('/movil/send_image', 'Movil\MovilController@send_image');
    Route::post('/movil/search_image', 'Movil\MovilController@search_image');
    Route::post('/movil/search_imageone', 'Movil\MovilController@search_imageone');
    Route::post('/movil/search_consec', 'Movil\MovilController@search_consec');
    Route::post('/movil/send_image_oym', 'Movil\MovilController@send_image_oym');
    Route::post('/movil/search_image_oym', 'Movil\MovilController@search_image_oym');
    Route::post('/movil/search_imageone_oym', 'Movil\MovilController@search_imageone_oym');

    Route::post('/movil/search_consec_oym', 'Movil\MovilController@search_consec_oym');
    Route::post('/movil/search_items', 'Movil\MovilController@search_items');

    Route::post('/acta/validate', 'Acta\ActaController@validate_acta');
    Route::post('/acta/upload_acta', 'Acta\ActaController@upload_acta');
    Route::get('/acta/download', 'Acta\ActaController@download');

    Route::post('/pqr/state_pqr', 'Pqr\PqrController@state_pqr');
    Route::post('/pqr/origin_pqr', 'Pqr\PqrController@origin_pqr');
    Route::post('/pqr/type_pqr', 'Pqr\PqrController@type_pqr');
    Route::post('/pqr/reason_pqr', 'Pqr\PqrController@reason_pqr');
    Route::post('/pqr/type_queja', 'Pqr\PqrController@type_queja');
    Route::post('/pqr/save_pqr', 'Pqr\PqrController@save_pqr');
    Route::post('/pqr/search_pqr', 'Pqr\PqrController@search_pqr');
    Route::post('/pqr/edit', 'Pqr\PqrController@edit');
    Route::post('/pqr/delete', 'Pqr\PqrController@delete');
    Route::post('/pqr/update', 'Pqr\PqrController@update');
    Route::post('/pqr/search_obr', 'Pqr\PqrController@search_obr');
    Route::post('/pqr/search_externas', 'Pqr\PqrController@search_externas');

    Route::post('/operaction/list_tipo', 'Operation\OperationController@list_tipo');
    Route::post('/operaction/list', 'Operation\OperationController@list');

    Route::post('/operaction/state', 'Operation\OperationController@state');
    Route::post('/operaction/update', 'Operation\OperationController@update');
    Route::get('/operaction/search_consec', 'Operation\OperationController@search_consec');
    Route::get('/operaction/search_pedido', 'Operation\OperationController@search_pedido');
    Route::post('/operaction/consec', 'Operation\OperationController@consec');
    Route::post('/operaction/searchoym', 'Operation\OperationController@searchoym');

    Route::post('/operaction/validate', 'Importoym\ImportoymController@check');
    Route::post('/operaction/data', 'Importoym\ImportoymController@data');

    Route::post('/operaction/municipios', 'Importoym\ImportoymController@municipios');
    Route::post('/operaction/xml', 'Importoym\ImportoymController@xml');

    Route::post('/operaction/search_items', 'Operation\OperationController@search_items');
    Route::post('/operaction/insert_items', 'Operation\OperationController@insert');
    Route::post('/operaction/delete_items', 'Operation\OperationController@delete_items');

    Route::post('/operaction/search_activity', 'Operation\OperationController@search_activity');

    Route::post('/operaction/search_image', 'Operation\OperationController@search_image');

    Route::post('/operaction/insert_material', 'Operation\OperationController@insert_material');
    Route::post('/operaction/searc_material', 'Operation\OperationController@searchmaterial');
    Route::post('/operaction/list_type_activity', 'Operation\OperationController@list_type_activity');
    Route::post('/operaction/search_oym_date', 'Operation\OperationController@search_oym_date');
    Route::post('/operaction/search_activity', 'Operation\OperationController@search_acti');

    Route::post('/operaction/consulta', 'Operation\RenameController@consulta');
    Route::post('/operaction/savepdf', 'Operation\RenameController@generetepdf');

    Route::post('/operaction/search_dac', 'Operation\OperationController@search_dac');

    Route::post('/operaction/clasificacion', 'Operation\OperationController@clasificacion');
    Route::post('/operaction/motivos_dac', 'Operation\OperationController@motivos_dac');

    Route::post('/operaction/save_dac', 'Operation\OperationController@save_dac');
    Route::post('/operaction/update_dac', 'Operation\OperationController@update_dac');
    Route::post('/operaction/search_dacuno', 'Operation\OperationController@search_dacone');
    Route::post('/operaction/search_histo', 'Operation\OperationController@search_histo');

    Route::post('/operaction/save_activitys', 'Operation\OperationController@save_activitys');
    Route::post('/operaction/delet_activity', 'Operation\OperationController@delet_activity');
    Route::post('/operaction/imagesend_oym', 'Operation\OperationController@imagesend_oym');
    Route::post('/external/save_items_actas', 'External\ExternalController@save_items_actas');
    Route::post('/external/search_items_actas', 'External\ExternalController@search_items_actas');

    Route::post('/external/delet_items_actas', 'External\ExternalController@delet_items_actas');

    Route::get('/external/auto_acta', 'External\ExternalController@auto_acta');
    Route::get('/external/auto_user', 'External\ExternalController@auto_user');
    Route::get('/external/auto_address', 'External\ExternalController@auto_address');

    Route::post('/external/search_acta', 'External\ExternalController@search_acta');
    Route::post('/external/search_user', 'External\ExternalController@search_user');
    Route::post('/external/search_address', 'External\ExternalController@search_address');
    Route::post('/external/search_idoti', 'External\ExternalController@search_idoti');
    Route::post('/external/type_gans', 'External\ExternalController@type_gans');

    Route::post('/external/save_dobra_gerencial', 'External\ExternalController@save_dobra_gerencial');
    Route::post('/external/search_dobra_gerencial', 'External\ExternalController@search_dobra_gerencial');
    Route::post('/external/delete_det_itemp_gen', 'External\ExternalController@delete_det_itemp_gen');
    Route::post('/external/saveow', 'External\ExternalController@saveow');
    Route::post('/external/search_list_ipid', 'External\ExternalController@list_ipid');
    Route::post('/external/search_ow', 'External\ExternalController@search_ow');
    Route::post('/external/delete_itemsow', 'External\ExternalController@delete_itemsow');
    Route::post('/external/idoti', 'External\ExternalController@idoti');

    Route::post('/permit/save', 'Permit\Permicontroller@save');

    Route::post('/external/savetopo', 'External\ExternalController@savetopo');
    Route::post('/external/searchtopo', 'External\ExternalController@searchtopo');
    Route::post('/external/searchOne', 'External\ExternalController@searchOne');
    Route::post('/external/updatetopo', 'External\ExternalController@updatetopo');
    Route::get('/external/search_ipi', 'External\ExternalController@search_ipid');
    Route::post('/external/search_dataipid', 'External\ExternalController@search_dataipid');
    Route::get('/Scraping/Scraping', 'Scraping\ScrapingController@Scraping');
    Route::post('/Scraping/loader', 'Scraping\ScrapingController@loader');
    Route::post('/ows/searc_one', 'Ows\OwsController@searc_one');
    Route::post('/ows/searc', 'Ows\OwsController@searc');
    Route::post('/ows/update', 'Ows\OwsController@update');

    Route::get('/operaction/search_consec_dispachet', 'Operation\OperationController@search_consec_dispachet');
    Route::get('/imagenes/idmagenes', 'Cambiode\imagenes@imagenes');

    //rutas de modulos nuevos de inventario
    Route::post('/ArrayCellar', 'Lists\ListsController@ArrayCellar');
    Route::post('save/purchase', 'Compras\Compras@save');
    Route::post('purchase/search_date', 'Compras\Compras@search_date');
    Route::post('purchase/search_conse', 'Compras\Compras@search_conse');
    Route::post('purchase/search_one', 'Compras\Compras@search_one');
    Route::post('purchase/delete', 'Compras\Compras@delete');

    Route::post('income/search_one', 'Ingresos\EntryController@search_purchases');
    Route::post('income/save', 'Ingresos\EntryController@save');
    Route::post('income/search_incom', 'Ingresos\EntryController@search_incom');
    Route::post('income/search_conse', 'Ingresos\EntryController@search_conse');
    Route::post('income/search_one_income', 'Ingresos\EntryController@search_one_income');
    Route::post('income/update_income', 'Ingresos\EntryController@update_income');
    Route::post('income/edit_income', 'Ingresos\EntryController@edit_income');
    Route::post('income/income_details', 'Ingresos\EntryController@income_details');
    Route::post('income/edit_state', 'Ingresos\EntryController@edit_state');
    Route::post('income/historico', 'Ingresos\EntryController@historico');

    Route::post('despacho/save', 'Despachos\DispatcheController@save');

    Route::post('despacho/search', 'Despachos\DispatcheController@search');
    Route::post('despacho/search_one', 'Despachos\DispatcheController@search_one');
    Route::post('despacho/search_one_diapches', 'Despachos\DispatcheController@search_one_diapches');
    Route::post('despacho/update_quantity', 'Despachos\DispatcheController@update_quantity');
    Route::post('despacho/despacho_series', 'Despachos\DispatcheController@despacho_series');
    Route::post('despacho/image_upload', 'Despachos\DispatcheController@image_upload');
    Route::post('despacho/historico', 'Despachos\DispatcheController@historico');

    Route::post('reintegro/save', 'Reintegro\ReintegroController@save');
    Route::post('reintegro/search_conse', 'Reintegro\ReintegroController@search_conse');
    Route::post('reintegro/search_reintegro', 'Reintegro\ReintegroController@search_reintegro');
    Route::post('reintegro/search_reintegro_date', 'Reintegro\ReintegroController@search_reintegro_date');
    Route::post('reintegro/search_one_diapches', 'Reintegro\ReintegroController@search_one_diapches');
    Route::post('reintegro/search_conse_despach', 'Reintegro\ReintegroController@search_conse_despach');
    Route::post('reintegro/update_reintegro', 'Reintegro\ReintegroController@update_reintegro');
    Route::post('reintegro/historico', 'Reintegro\ReintegroController@historico');

    Route::post('traslados/save', 'Traslados\TrasladosController@save');
    Route::post('traslados/search_date', 'Traslados\TrasladosController@search_date');

    Route::post('traslados/search_one', 'Traslados\TrasladosController@search_one');
    Route::post('traslados/historico', 'Traslados\TrasladosController@historico');

    Route::post('traslados/edit', 'Traslados\TrasladosController@edit');
    Route::post('traslados/search_conse', 'Traslados\TrasladosController@search_conse');

    Route::get('traslados/ping', 'Traslados\TrasladosController@ping');

    Route::get('odi/search_consec', 'Odi\OdiController@search_consec');
    Route::get('odi/search_pedido', 'Odi\OdiController@search_pedido');
    Route::get('odi/search_ot', 'Odi\OdiController@search_ot');
    Route::get('odi/search_cedula', 'Odi\OdiController@search_cedula');
    Route::get('odi/search_address', 'Odi\OdiController@search_address');
    Route::get('odi/search_instal', 'Odi\OdiController@search_instal');

    Route::post('odi/searchconsec', 'Odi\OdiController@searchconsec');
    Route::post('odi/searchot', 'Odi\OdiController@searchot');
    Route::post('odi/searchpedido', 'Odi\OdiController@searchpedido');
    Route::post('odi/searchcedula', 'Odi\OdiController@searchcedula');
    Route::post('odi/address', 'Odi\OdiController@address');
    Route::post('odi/searchodi', 'Odi\OdiController@searchodi');
    Route::post('odi/searchtipoobr', 'Odi\OdiController@searchtipoobr');
    Route::post('odi/update', 'Odi\OdiController@update');

    Route::post('odi/imagesend', 'Odi\OdiController@imagesend');
    Route::post('odi/search_image', 'Odi\OdiController@search_image');

    Route::post('/importar/adress_medellin', 'Importobr\ImportObrController@adress_medellin');
    Route::post('/certificado/create', 'certificado\Certificado@create');
    Route::post('/certificado/search', 'certificado\Certificado@search');
    Route::post('/client/create', 'client\ClientController@create');

    //Rutas Anderson
    Route::post('/line/create', 'Administrator\AdministratorController@createLine');
    Route::post('/line/delete', 'Administrator\AdministratorController@deleteLine');
    Route::post('/line/search', 'Administrator\AdministratorController@searchLine');
    Route::post('/line/update', 'Administrator\AdministratorController@updateLine');

    Route::post('/subline/create', 'Administrator\AdministratorController@createSubline');
    Route::post('/subline/delete', 'Administrator\AdministratorController@deleteSubline');
    Route::post('/subline/search', 'Administrator\AdministratorController@searchSubline');
    Route::post('/subline/update', 'Administrator\AdministratorController@updateSubline');
    Route::post('/subline/cargarLine', 'Administrator\AdministratorController@cargarLineSubline');

    Route::post('/administrator/create', 'Administrator\AdministratorController@createDesDis');
    Route::post('/administrator/delete', 'Administrator\AdministratorController@deleteDesDis');
    Route::post('/administrator/search', 'Administrator\AdministratorController@searchDesDis');
    Route::post('/administrator/update', 'Administrator\AdministratorController@updateDesDis');

    Route::post('/gang/create', 'Administrator\AdministratorController@createGang');
    Route::post('/gang/delete', 'Administrator\AdministratorController@deleteGang');
    Route::post('/gang/search', 'Administrator\AdministratorController@searchGang');
    Route::post('/gang/update', 'Administrator\AdministratorController@updateGang');

    Route::post('/worktype/create', 'Administrator\AdministratorController@createWorkType');
    Route::post('/worktype/delete', 'Administrator\AdministratorController@deleteWorkType');
    Route::post('/worktype/search', 'Administrator\AdministratorController@searchWorkType');
    Route::post('/worktype/update', 'Administrator\AdministratorController@updateWorkType');

    Route::post('/subworktype/create', 'Administrator\AdministratorController@createSWT');
    Route::post('/subworktype/delete', 'Administrator\AdministratorController@deleteSWT');
    Route::post('/subworktype/search', 'Administrator\AdministratorController@searchSWT');
    Route::post('/subworktype/update', 'Administrator\AdministratorController@updateSWT');
    Route::post('/subworktype/cargarType', 'Administrator\AdministratorController@cargarSWT');
    Route::post('/subworktype/cargarState', 'Administrator\AdministratorController@cargarsSWT');

    Route::post('/updatework/create', 'Administrator\AdministratorController@createUpdateW');
    Route::post('/updatework/delete', 'Administrator\AdministratorController@deleteUpdateW');
    Route::post('/updatework/search', 'Administrator\AdministratorController@searchUpdateW');
    Route::post('/updatework/update', 'Administrator\AdministratorController@updateUpdateW');
    Route::post('/updatework/cargarType', 'Administrator\AdministratorController@cargarSWT');
    Route::post('/updatework/cargarM', 'Administrator\AdministratorController@cargarM');
    Route::post('/updatework/cargarSubstado', 'Administrator\AdministratorController@cargarSubstados');
    Route::post('/updatework/cargarSubtipos', 'Administrator\AdministratorController@cargarSubtipos');
    Route::post('/updatework/searchot', 'Administrator\AdministratorController@searchUpdateWot');
    Route::post('/updatework/updateot', 'Administrator\AdministratorController@updateUpdateWot');

    Route::post('/dispatch-movements/create', 'Administrator\AdministratorController@createDispatchMov');
    Route::post('/dispatch-movements/delete', 'Administrator\AdministratorController@deleteDispatchMov');
    Route::post('/dispatch-movements/search', 'Administrator\AdministratorController@searchDispatchMov');
    Route::post('/dispatch-movements/update', 'Administrator\AdministratorController@updateDispatchMov');

    Route::post('/units/create', 'Administrator\AdministratorController@createUnits');
    Route::post('/units/delete', 'Administrator\AdministratorController@deleteUnits');
    Route::post('/units/search', 'Administrator\AdministratorController@searchUnits');
    Route::post('/units/update', 'Administrator\AdministratorController@updateUnits');

    Route::post('/inputtype/create', 'Administrator\AdministratorController@createInputType');
    Route::post('/inputtype/delete', 'Administrator\AdministratorController@deleteInputType');
    Route::post('/inputtype/search', 'Administrator\AdministratorController@searchInputType');
    Route::post('/inputtype/update', 'Administrator\AdministratorController@updateInputType');

    Route::post('/vehicles/create', 'Administrator\AdministratorController@createVehicles');
    Route::post('/vehicles/delete', 'Administrator\AdministratorController@deleteVehicles');
    Route::post('/vehicles/search', 'Administrator\AdministratorController@searchVehicles');
    Route::post('/vehicles/update', 'Administrator\AdministratorController@updateVehicles');
    Route::post('/vehicles/update', 'Administrator\AdministratorController@updateVehicles');

    Route::post('/order_status/create', 'Administrator\AdministratorController@createOrdSt');
    Route::post('/order_status/delete', 'Administrator\AdministratorController@deleteOrdSt');
    Route::post('/order_status/search', 'Administrator\AdministratorController@searchOrdSt');
    Route::post('/oym_records/subir', 'Administrator\AdministratorController@subirOymRecords');

    Route::post('/user/cargarCompany', 'User\UserController@cargarCompany');
    Route::post('/user/cargarAlmacen', 'User\UserController@cargarAlmacen');
    Route::post('/user/search_cellar', 'User\UserController@search_cellar');
    Route::post('/user/save_cellar', 'User\UserController@save_cellar');
    Route::post('/user/delete_cellar', 'User\UserController@delete_cellar');
    Route::post('/user/reset', 'User\UserController@reset');
    Route::get('/user/conf-pass', 'User\UserController@conf_pass');

    Route::post('/importdac/onFileChange', 'Administrator\AdministratorController@subir');

    Route::post('/meters/update', 'Administrator\AdministratorController@update');
    Route::post('/meters/search', 'Administrator\AdministratorController@search');

    Route::post('/oym-records/subir', 'Administrator\AdministratorController@subirOymRecords');

    Route::post('/charge/create', 'Administrator\AdministratorController@createCharge');
    Route::post('/charge/delete', 'Administrator\AdministratorController@deleteCharge');
    Route::post('/charge/search', 'Administrator\AdministratorController@searchCharge');
    Route::post('/charge/update', 'Administrator\AdministratorController@updateCharge');
    Route::post('/charge/cargarclasschange', 'Administrator\AdministratorController@cargarclasschange');

    Route::post('/programming-oym/search', 'ProgrammingOyM\ProgrammingOyMController@search');
    Route::post('/programming-oym/cargarstate', 'Administrator\AdministratorController@cargarsSWT');
    Route::post('/programming-oym/programar', 'ProgrammingOyM\ProgrammingOyMController@programar');

    Route::post('/ows/create', 'Ows\OwsController@create');

    Route::post('/receivedow/create', 'Receivedow\ReceivedowController@create');

    Route::post('/updatework/cargarContrato', 'Administrator\AdministratorController@cargarContrato');

    Route::post('/subcharge/create', 'Administrator\AdministratorController@createSubCharge');
    Route::post('/subcharge/delete', 'Administrator\AdministratorController@deleteSubCharge');
    Route::post('/subcharge/search', 'Administrator\AdministratorController@searchSubCharge');
    Route::post('/subcharge/update', 'Administrator\AdministratorController@updateSubCharge');
    Route::post('/subcharge/cargarCharge', 'Administrator\AdministratorController@cargarChargeSubCharge');

    Route::post('/documents/cargarContract', 'Administrator\AdministratorController@cargarContrato');
    Route::post('/documents/cargarCompany', 'User\UserController@cargarCompany');
    Route::post('/documents/search', 'Administrator\AdministratorController@searchDocuments');
    Route::post('/documents/create', 'Administrator\AdministratorController@createDocuments');

    Route::post('/user/saveIncome', 'User\UserController@saveIncome');

    Route::post('/actImages/search', 'Administrator\AdministratorController@searchActImages');
    Route::post('/actImages/delete', 'Administrator\AdministratorController@deleteActImages');
    Route::post('/actImages/deleteImage', 'Administrator\AdministratorController@deleteoneActImages');
    Route::post('/actImages/deleteAll', 'Administrator\AdministratorController@deleteallActImages');
    Route::post('/actImages/searchImage', 'Administrator\AdministratorController@searchImagesActImages');
    Route::post('/actImages/update', 'Administrator\AdministratorController@updateActImages');

    Route::post('/sys-inventory/search', 'SysInventory\SysInventoryController@search');
    Route::post('/sys-inventory/update', 'SysInventory\SysInventoryController@update');
    Route::post('/sys-inventory/create', 'SysInventory\SysInventoryController@create');
    Route::post('/sys-inventory/subir', 'SysInventory\SysInventoryController@subir');
    Route::post('/sys-inventory/saveHistory', 'SysInventory\SysInventoryController@saveHistory');
    Route::post('/sys-inventory/updateHistory', 'SysInventory\SysInventoryController@updateHistory');
    Route::post('/sys-inventory/search_inventory', 'SysInventory\SysInventoryController@search_inventory');

    Route::post('/downloadmages/copy', 'Administrator\AdministratorController@copyImages');

    Route::post('/providern/insert_provider', 'Providers\ProviderController@insert_provider_n');
    Route::post('/providern/update_provider', 'Providers\ProviderController@update_provider_n');
    Route::post('/providern/search_provider', 'Providers\ProviderController@search_provider_n');
    Route::post('/providern/search', 'Providers\ProviderController@search_n');
    Route::post('/providern/search_materials', 'Providers\ProviderController@search_materials_n');
    Route::post('/providern/search_edit_mate', 'Providers\ProviderController@select_edit_mate_n');
    Route::post('/providern/edit_material', 'Providers\ProviderController@edit_material_n');
    Route::post('/providern/matirials', 'Providers\ProviderController@matirials_n');
    Route::post('/providern/selectNewMat', 'Providers\ProviderController@selectNewMat_n');

    Route::post('/renameFiles/subir', 'Administrator\AdministratorController@renameFiles');

    Route::post('/uploadodi/onFileChange', 'Odi\OdiController@onFileChange');

    Route::post('/update-dispatches/subir', 'Administrator\AdministratorController@uploadUpdateDispatches');

////////////////////////////////////////////////////////////////////////////////////////////////-------------------//////////////////////////////////////////////
    ///////////////rutas nuevas /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    Route::post('/user/permission/create', 'NewControllers\user\management\PermissionController@create');
    Route::post('/user/permission/search', 'NewControllers\user\management\PermissionController@search');
    Route::post('/user/permission/update', 'NewControllers\user\management\PermissionController@update');
    Route::post('/user/rol/create', 'NewControllers\user\management\PermissionController@create_rol');
    Route::post('/user/rol/searchs', 'NewControllers\user\management\PermissionController@searchs');
    Route::post('/user/rol/search', 'NewControllers\user\management\PermissionController@search_rol');
    Route::post('/user/rol/update_rol', 'NewControllers\user\management\PermissionController@update_rol');
    Route::post('/user/rol/update_permission_rol', 'NewControllers\user\management\PermissionController@update_permission_rol');

    Route::post('/company/create', 'NewControllers\company\CompanyController@create');
    Route::post('/company/searchs', 'NewControllers\company\CompanyController@searchs');
    Route::post('/company/update', 'NewControllers\company\CompanyController@update');
    Route::post('/company/list_company', 'NewControllers\company\CompanyController@list_company');

    Route::post('/contract/create', 'NewControllers\contract\ContractController@create');
    Route::post('/contract/searchs', 'NewControllers\contract\ContractController@searchs');
    Route::post('/contract/update', 'NewControllers\contract\ContractController@update');
    Route::post('/list/contract', 'NewControllers\contract\ContractController@search_contracts');

    Route::post('/user/users/create', 'NewControllers\user\users\UsersController@create');
    Route::post('/user/users/update', 'NewControllers\user\users\UsersController@update');
    Route::post('/user/users/delete', 'NewControllers\user\users\UsersController@delete');
    Route::post('/user/users/searchs', 'NewControllers\user\users\UsersController@searchs');
    Route::post('/user/users/search_contract', 'NewControllers\user\users\UsersController@search_contract');
    Route::post('/user/users/delete_contract', 'NewControllers\user\users\UsersController@delete_contract');
    Route::post('/user/users/search_user', 'NewControllers\user\users\UsersController@search_user');

    Route::post('/login/login', 'NewControllers\login\LoginController@login');
    Route::post('/login/load_rol', 'NewControllers\login\LoginController@load_rol');

    Route::post('/employee/create', 'NewControllers\employee\EmployeeController@create');
    Route::post('/employee/search_employee', 'NewControllers\employee\EmployeeController@search_employee');
    Route::post('/employee/update', 'NewControllers\employee\EmployeeController@update');

    Route::get('/employee/autocomplete', 'NewControllers\employee\EmployeeController@autocomplete');
    Route::post('/employee/create_arl', 'NewControllers\employee\EmployeeController@create_arl');
    Route::post('/employee/search_arl', 'NewControllers\employee\EmployeeController@search_arl');
    Route::post('/employee/delete_arl', 'NewControllers\employee\EmployeeController@delete_arl');
    Route::post('/employee/search_eps', 'NewControllers\employee\EmployeeController@search_eps');
    Route::post('/employee/create_eps', 'NewControllers\employee\EmployeeController@create_eps');
    Route::post('/employee/delete_eps', 'NewControllers\employee\EmployeeController@delete_eps');
    Route::post('/employee/search_pension', 'NewControllers\employee\EmployeeController@search_pension');
    Route::post('/employee/create_pension', 'NewControllers\employee\EmployeeController@create_pension');
    Route::post('/employee/delete_pension', 'NewControllers\employee\EmployeeController@delete_pension');

    Route::post('/list/list_eps', 'NewControllers\lists\ListController@list_eps');
    Route::post('/list/list_arl', 'NewControllers\lists\ListController@list_arl');
    Route::post('/list/list_pension', 'NewControllers\lists\ListController@list_pension');
    Route::post('/list/list_service', 'NewControllers\lists\ListController@list_service');
    Route::post('/list/list_photos', 'NewControllers\lists\ListController@list_photos');
    Route::post('/list/list_municipality', 'NewControllers\lists\ListController@list_municipality');
    Route::post('/list/list_type_network', 'NewControllers\lists\ListController@list_type_network');
    Route::post('/list/list_photos_service', 'NewControllers\lists\ListController@list_photos_service');

    Route::post('/odi/create', 'NewControllers\odi\OdiController@create');
    Route::post('/odi/update', 'NewControllers\odi\OdiController@update');

    Route::get('/odi/autocomplete', 'NewControllers\odi\OdiController@autocomplete');
    Route::post('/odi/photoid', 'NewControllers\odi\OdiController@photoid');

    Route::post('/odi/send_image', 'NewControllers\odi\ImageController@Uploadimage');
    Route::post('/odi/search_image', 'NewControllers\odi\ImageController@search_image');
    Route::post('/odi/send_image_movil', 'NewControllers\odi\ImageController@send_image_movil');
    Route::post('/odi/delete_photo', 'NewControllers\odi\ImageController@delete_photo');

    Route::post('/odi/defectos', 'NewControllers\odi\OdiController@defectos');
    Route::post('/odi/search_defectos', 'NewControllers\odi\OdiController@search_defectos');
    Route::post('/odi/correcion_defectos', 'NewControllers\odi\OdiController@correcion_defectos');
    Route::post('/odi/search_correcion_defectos', 'NewControllers\odi\OdiController@search_correcion_defectos');
    Route::post('/odi/save_test', 'NewControllers\odi\OdiController@save_test');
    Route::post('/odi/search', 'NewControllers\odi\OdiController@search');
    Route::post('/odi/certficate_create', 'NewControllers\odi\OdiController@certficate_create');
    Route::post('/odi/certficate_search', 'NewControllers\odi\OdiController@certficate_search');

    Route::post('/odi/certficate_delete', 'NewControllers\odi\OdiController@certficate_delete');

    Route::get('/autocomplete/autocomplete_certicate', 'NewControllers\autocomplete\AutocompleteController@autocomplete_certicate');
    Route::post('/client/create', 'NewControllers\client\ClientController@create');
    Route::post('/client/create_account', 'NewControllers\client\ClientController@create_account');
    Route::post('/client/delete_account', 'NewControllers\client\ClientController@delete_account');
    Route::get('/client/search', 'NewControllers\client\ClientController@search');
    Route::post('/client/search_account', 'NewControllers\client\ClientController@search_account');

    Route::post('/movil/login', 'NewControllers\appmovil\AppMovilController@login');
    Route::post('/movil/totalasignadas', 'NewControllers\appmovil\AppMovilController@totalasignadas');
    Route::post('/movil/seach_asignadas', 'NewControllers\appmovil\AppMovilController@seach_asignadas');
    Route::post('/movil/photos_service', 'NewControllers\appmovil\AppMovilController@photos_service');
    Route::post('/movil/registerToken', 'NewControllers\appmovil\AppMovilController@registerToken');
    Route::get('/movil/search_materials', 'NewControllers\appmovil\AppMovilController@search_materials');
    Route::get('/movil/search_builder', 'NewControllers\appmovil\AppMovilController@search_builder');
    Route::get('/movil/search_builder', 'NewControllers\appmovil\AppMovilController@search_builder');
    Route::post('/movil/search_certificate', 'NewControllers\appmovil\AppMovilController@search_certificate');
    Route::post('/movil/save_certificate', 'NewControllers\appmovil\AppMovilController@save_certificate');
    Route::post('/movil/number', 'NewControllers\appmovil\AppMovilController@number_certificate');
    Route::post('/movil/ViewImage', 'NewControllers\appmovil\AppMovilController@ViewImage');
    Route::post('/movil/SaveService', 'NewControllers\appmovil\AppMovilController@SaveService');
    Route::post('/movil/SaveCliente', 'NewControllers\appmovil\AppMovilController@SaveCliente');
    Route::get('/movil/ListClient', 'NewControllers\appmovil\AppMovilController@SearchClient');
    Route::get('/movil/AutoListClient', 'NewControllers\appmovil\AppMovilController@AutoListClient');
    Route::post('/movil/ListAcount', 'NewControllers\appmovil\AppMovilController@ListAcount');
    Route::get('/movil/ListCity', 'NewControllers\appmovil\AppMovilController@ListCity');
    Route::get('/movil/AutoCity', 'NewControllers\appmovil\AppMovilController@AutoCity');
    Route::get('/movil/ListMaterial', 'NewControllers\appmovil\AppMovilController@ListMaterial');
    Route::get('/movil/AutoListMaterial', 'NewControllers\appmovil\AppMovilController@AutoListMaterial');
    Route::post('/materials/savemovil', 'NewControllers\administration\material\MaterialController@savemovil');
    Route::get('/movil/MaterialCertificate', 'NewControllers\appmovil\AppMovilController@MaterialCertificate');
    Route::get('/movil/listsic', 'NewControllers\appmovil\AppMovilController@listsic');
    Route::get('/movil/listcom', 'NewControllers\appmovil\AppMovilController@listcom');

    Route::post('/movil/certificate_material', 'NewControllers\appmovil\AppMovilController@certificate_material');
    Route::post('/movil/sic_builder', 'NewControllers\appmovil\AppMovilController@sic_builder');
    Route::post('/movil/com_builder', 'NewControllers\appmovil\AppMovilController@com_builder');

    Route::get('/movil/ListBuilder', 'NewControllers\appmovil\AppMovilController@ListBuilder');
    Route::get('/movil/ListBuilder', 'NewControllers\appmovil\AppMovilController@ListBuilder');
    Route::get('/movil/search_address', 'NewControllers\appmovil\AppMovilController@search_address');
    Route::post('/movil/change_state', 'NewControllers\appmovil\AppMovilController@change_state');
    Route::post('/movil/change_active', 'NewControllers\appmovil\AppMovilController@change_active');
    Route::post('/movil/change_active_service', 'NewControllers\appmovil\AppMovilController@change_active_service');

    Route::post('/photos/create', 'NewControllers\administration\photos\PhotosController@create');
    Route::post('/photos/search', 'NewControllers\administration\photos\PhotosController@search');

    Route::post('/photos/update', 'NewControllers\administration\photos\PhotosController@update');
    Route::post('/photos/delete', 'NewControllers\administration\photos\PhotosController@delete');

    Route::post('/network/create', 'NewControllers\administration\type_network\type_network@create');
    Route::post('/network/search', 'NewControllers\administration\type_network\type_network@search');
    Route::post('/network/update', 'NewControllers\administration\type_network\type_network@update');
    Route::post('/network/delete', 'NewControllers\administration\type_network\type_network@delete');
    Route::post('/network/create_photo', 'NewControllers\administration\type_network\type_network@create_photo');
    Route::post('/network/search_photo', 'NewControllers\administration\type_network\type_network@search_photo');
    Route::post('/network/delete_photo', 'NewControllers\administration\type_network\type_network@delete_photo');

    Route::post('/materials/create', 'NewControllers\administration\material\MaterialController@create');

    Route::post('/materials/search', 'NewControllers\administration\material\MaterialController@search');
    Route::post('/materials/update', 'NewControllers\administration\material\MaterialController@update');
    Route::post('/materials/create_certificate', 'NewControllers\administration\material\MaterialController@create_certificate');
    Route::post('/materials/search_certificate', 'NewControllers\administration\material\MaterialController@search_certificate');
    Route::post('/materials/send_document', 'NewControllers\administration\material\MaterialController@send_document');
    Route::post('/materials/search_document', 'NewControllers\administration\material\MaterialController@search_document');
    Route::post('/materials/delete_document', 'NewControllers\administration\material\MaterialController@delete_document');
    Route::post('/materials/delete', 'NewControllers\administration\material\MaterialController@delete');
    Route::post('/materials/update_certificate', 'NewControllers\administration\material\MaterialController@update_certificate');
    Route::post('/materials/delete_certificate', 'NewControllers\administration\material\MaterialController@delete_certificate');

    Route::post('/builder/create', 'NewControllers\administration\builder\BuilderController@create');
    Route::get('/builder/search', 'NewControllers\administration\builder\BuilderController@search');
    Route::post('/builder/create_sic', 'NewControllers\administration\builder\BuilderController@create_sic');
    Route::post('/builder/search_sic', 'NewControllers\administration\builder\BuilderController@search_sic');
    Route::post('/builder/send_document', 'NewControllers\administration\builder\BuilderController@send_document');
    Route::post('/builder/search_sic_document', 'NewControllers\administration\builder\BuilderController@search_sic_document');
    Route::post('/builder/delete_sic_document', 'NewControllers\administration\builder\BuilderController@delete_sic_document');
    Route::post('/builder/create_competition', 'NewControllers\administration\builder\BuilderController@create_competition');
    Route::post('/builder/search_competition', 'NewControllers\administration\builder\BuilderController@search_competition');
    Route::post('/builder/search_document_competition', 'NewControllers\administration\builder\BuilderController@search_document_competition');
    Route::post('/builder/delete_document_competition', 'NewControllers\administration\builder\BuilderController@delete_document_competition');
    Route::post('/builder/delete_competition', 'NewControllers\administration\builder\BuilderController@delete_competition');
    Route::post('/builder/delete', 'NewControllers\administration\builder\BuilderController@delete');

    Route::post('/competition/create', 'NewControllers\administration\competition\CompetitionController@save');
    Route::post('/competition/delete', 'NewControllers\administration\competition\CompetitionController@delete');
    Route::get('/competition/search', 'NewControllers\administration\competition\CompetitionController@search');

    Route::post('/certificate/create', 'NewControllers\administration\certificate\CertificateController@create');
    Route::post('/certificate/delete', 'NewControllers\administration\certificate\CertificateController@delete');
    Route::get('/certificate/search', 'NewControllers\administration\certificate\CertificateController@search');

    Route::get('/autocomplete/autocomplete_materials', 'NewControllers\autocomplete\AutocompleteController@AutocompleteMaterial');
    Route::get('/autocomplete/autocomplete_constrctor', 'NewControllers\autocomplete\AutocompleteController@AutocompleteConstructor');
    Route::get('/autocomplete/autocomplete_city', 'NewControllers\autocomplete\AutocompleteController@autocomplete_city');

    Route::post('/import/import', 'NewControllers\odi\ImporController@import');

    Route::post('/programming/search', 'NewControllers\odi\ProgrammingController@search');

    Route::post('/programming/programming', 'NewControllers\odi\ProgrammingController@programming');

});
