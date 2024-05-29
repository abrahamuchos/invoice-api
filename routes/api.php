<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function(){
    Route::post('login', [AuthController::class, 'login']);

    //Auth routes
    Route::middleware('auth:sanctum', )->group(function(){

        Route::apiResource('customers', CustomerController::class);

        Route::apiResource('invoices', InvoiceController::class);
        Route::post('invoices/bulk', [InvoiceController::class, 'bulkStore']);

        Route::get('logout',  [AuthController::class, 'logout']);
    });
});


