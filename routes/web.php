<?php

use App\Http\Controllers\CronController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BatchedOrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\Administration;
use App\Http\Controllers\MapController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function(){return view('Login');})->name('login');
Route::post('/',[Administration::class,'Login'])->name('UserLogin');
Route::get('/insertuser',[Administration::class,'InsertUser']);
Route::get('/logout', [Administration::class,'Logout'])->name('logout');

Route::get('/forgot-password', [Administration::class, 'ShowForgotPasswordForm'])->name('forgot.password.get');
Route::post('/forgot-password', [Administration::class, 'SubmitForgotPasswordForm'])->name('forgot.password.post');
Route::get('/reset-password/{token}', [Administration::class, 'ShowResetPasswordForm'])->name('reset.password.get');
Route::post('/reset-password', [Administration::class, 'SubmitResetPasswordForm'])->name('reset.password.post');

Route::get('/orders/visit_invoice/{id}/{code}', [OrderController::class,'CreatePdfInvoice'])->name('PdfInvoice');
Route::get('/reports/customer_info',[ReportsController::class,'CustomerInfo'])->name('CustomerInfo');


Route::group(['middleware'=>'checkuser'], function() {

    Route::get('/customers', [CustomerController::class,'index'])->name('customers');
    Route::get('/new_customers', [CustomerController::class,'new_index'])->name('new_customers');
    Route::get('/customers/list', [CustomerController::class,'customer_list'])->name('customer_list');
    Route::get('/customers/add_customer', [CustomerController::class,'AddCustomerForm']);
    Route::post('/customers/add_customer', [CustomerController::class,'AddCustomer'])->name('AddCustomer');
    Route::get('/customers/edit_customer/{id}', [CustomerController::class,'EditCustomer']);
    Route::post('/customers/edit_customer', [CustomerController::class,'UpdateCustomer'])->name('UpdateCustomer');
    Route::post('/customers/edit_address', [CustomerController::class,'UpdateAddress'])->name('UpdateAddress');
    Route::post('/customers/add_address', [CustomerController::class,'AddAddress'])->name('AddAddress');
    Route::get('/customers/delete_customer/{id}', [CustomerController::class,'DeleteCustomer']);
    Route::get('/customers/delete_address/{id}', [CustomerController::class,'DeleteAddress']);

    Route::get('/orders/AddGeohash', [OrderController::class,'GeohashUpdate']);
    Route::get('/orders/auto_batch', [OrderController::class,'AutoBatch'])->name('AutoBatch');
    Route::post('/orders/visits_to_batch', [OrderController::class,'VisitsToBatch'])->name('VisitsToBatch');
    Route::get('/orders/schedules', [OrderController::class,'Schedules'])->name('schedules');
    Route::post('/orders/schedule/goodsamt', [OrderController::class,'GoodsAmt']);

    Route::get('/orders/add_customer' , [OrderController::class,'AddCustomer'])->name('NewOrder');
    Route::get('/orders/select_customer/{id}',[OrderController::class,'SelectCustomer']);
    Route::get('/orders/add_schedule/{id}' , [OrderController::class,'Schedule'])->name('NewSchedule');
    Route::get('/orders/get_goods' , [OrderController::class,'GetGoods']);
    Route::post('/orders/add_schedule' , [OrderController::class,'AddSchedule'])->name('AddSchedule');
    Route::get('/orders/edit_schedule/{id}', [OrderController::class,'EditSchedule']);
    Route::post('/orders/edit_schedule', [OrderController::class,'UpdateSchedule'])->name('UpdateSchedule');
    Route::get('/orders/delete_schedule/{id}', [OrderController::class,'DeleteSchedule']);
    Route::post('/orders/orders_to_batch', [OrderController::class,'OrdersToBatch'])->name('OrdersToBatch');


    Route::post('/orders/batched_orders/edit_batched_order', [BatchedOrderController::class,'EditBatch'])->name('EditBatch');
    Route::get('/orders/batched_orders/delete_batch/{id}', [BatchedOrderController::class,'DeleteBatch']);
    Route::post('/orders/batched_orders/send_to_delivery/' , [BatchedOrderController::class,'SendToDelivery'])->name('SendToDelivery');
    Route::get('/orders/batched_orders/send_to_delivery/select_driver/{id}' , [BatchedOrderController::class,'SelectDriver']);
    Route::get('/orders/batched_orders/delivered_batches/' , [BatchedOrderController::class,'DeliveredBatches'])->name('DeliveredBatches');
    Route::post('/orders/batched_orders/delivered_batches/' , [BatchedOrderController::class,'DeliveredBatches'])->name('DeliveredBatches');
    Route::post('/orders/batched_orders/assign_to_driver' , [BatchedOrderController::class,'AssignToDriver'])->name('AssignToDriver');
    Route::post('/orders/batched_orders/edit_assigned_batchs', [BatchedOrderController::class,'EditAssignedBatches'])->name('EditAssignedBatches');
    Route::get('/orders/batched_orders/unassign_batch/{id}', [BatchedOrderController::class,'UnassignBatch']);
    Route::get('/orders/batched_orders/visit_count', [BatchedOrderController::class,'VisitCount']);


    Route::get('/orders/batched_orders/{id?}',[BatchedOrderController::class,'BatchedOrders'])->name('BatchedOrders');
    Route::post('/orders/schedule_details',[OrderController::class,'ScheduleDetails']);
    Route::get('/orders/unbatched_visits',[OrderController::class,'UnBatchedVisits'])->name('UnBatchedVisits');
    Route::get('/orders/list', [OrderController::class,'orders_list'])->name('orders_list');
    Route::get('/orders/{id?}', [OrderController::class,'Visits'])->name('visits');
    Route::get('/new_orders/{id?}', [OrderController::class,'new_visits'])->name('new_visits');
    Route::post('/orders/{id?}', [OrderController::class,'Visits'])->name('visitpost');

    Route::get('/administration/goods', [Administration::class,'Goods'])->name('goods');
    Route::get('/administration/goods/add_goods', [Administration::class,'AddGoodsForm']);
    Route::post('administration/goods/add_goods', [Administration::class,'AddGoods'])->name('AddGoods');
    Route::get('/administration/goods/edit_goods/{id}', [Administration::class,'EditGoods']);
    Route::post('administration//goods/edit_goods/', [Administration::class,'UpdateGoods'])->name('UpdateGoods');
    Route::get('/administration/goods/delete_goods/{id}', [Administration::class,'DeleteGoods']);

    Route::get('/administration/goods/goods_type', [Administration::class,'GoodsType']);
    Route::post('/administration/goods/add_goodstype/', [Administration::class,'AddGoodsType'])->name('AddGoodsType');
    Route::get('/administration/goods/edit_goodstype/{id}', [Administration::class,'EditGoodsType']);
    Route::post('/administration/goods/update_goodstype', [Administration::class,'UpdateGoodsType'])->name('UpdateGoodsType');
    Route::get('/administration/goods/delete_goodstype/{id}', [Administration::class,'DeleteGoodsType']);

    Route::get('/administration/tags',[Administration::class,'Tags'])->name('tags');
    Route::post('/administration/tags/add_tag',[Administration::class,'AddTag'])->name('AddTag');
    Route::get('/administration/tags/edit_tag/{id}',[Administration::class,'EditTag']);
    Route::post('/administration/tags/update_tag',[Administration::class,'UpdateTag'])->name('UpdateTag');
    Route::get('/administration/tags/delete_tag/{id}',[Administration::class,'DeleteTag']);

    Route::get('/administration/users',[Administration::class,'Users'])->name('users');
    Route::post('/administration/users/add_user',[Administration::class,'AddUser'])->name('AddUser');
    Route::get('/administration/users/edit_user/{id}',[Administration::class,'EditUser']);
    Route::post('/administration/users/update_user',[Administration::class,'UpdateUser'])->name('UpdateUser');
    Route::get('/administration/users/delete_user/{id}',[Administration::class,'DeleteUser']);

    Route::get('/administration/pharmacies',[Administration::class,'Pharmacies'])->name('pharmacies');
    Route::post('/administration/pharmacies/add_pharmacy',[Administration::class,'AddPharmacy'])->name('AddPharmacy');
    Route::get('/administration/pharmacies/edit_pharmacy/{id}',[Administration::class,'EditPharmacy']);
    Route::post('/administration/pharmacies/update_pharmacy',[Administration::class,'UpdatePharmacy'])->name('UpdatePharmacy');
    Route::get('/administration/pharmacies/delete_pharmacy/{id}',[Administration::class,'DeletePharmacy']);

    Route::any('/reports/results' , [ReportsController::class,'ShowReport'])->name('ShowReport');
    Route::get('/reports/get_filteration_data',[ReportsController::class,'GetFilterationData']);
    Route::post('/reports/track_download',[ReportsController::class,'TrackDownload'])->name('TrackDownload');
    Route::get('/reports/signimage/{filename}',[ReportsController::class,'SignImage']);
    Route::get('/reports',[ReportsController::class,'index'])->name('reports');


    Route::get('/cron',[CronController::class,'index']);

    // Route::get('/map',[MapController::class,'MapCordinates'])->name('map');
    Route::get('/map',[MapController::class,'MapNewCordinates'])->name('map');
    // Route::post('/map',[MapController::class,'MapCordinates'])->name('MapFilter');
    Route::post('/map',[MapController::class,'MapNewCordinates'])->name('MapFilter');
    Route::post('/map/marker_info/',[MapController::class,'MarkerInfo']);
    Route::get('/map/marker_info/',[MapController::class,'GetMarkerInfo']);
});






