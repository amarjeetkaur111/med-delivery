<?php

use App\Http\Controllers\Api\UpdateVisitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use \App\Http\Controllers\Api\DailyBatchesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('Authorization', [AuthController::class, 'index']);

Route::group(['middleware'=>'auth:sanctum'], function() {

    Route::post('DailyBatches', [DailyBatchesController::class, 'index']);
    Route::post('UpdateVisits', [UpdateVisitController::class, 'index']);
    Route::post('UploadVisitSign', [UpdateVisitController::class, 'UploadVisitSign']);
    Route::get('Logout', [AuthController::class, 'logout']);

});





