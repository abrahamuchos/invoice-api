<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\UserController;
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
    Route::post('users', [UserController::class, 'store']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');

    //Auth routes
    Route::middleware('auth:sanctum')->group(function(){

        Route::get('users', [UserController::class, 'index'])->middleware('admin');
        Route::get('users/{user}', [UserController::class, 'show']);
        Route::put('users/{user}', [UserController::class, 'update']);
        Route::patch('users/{user}', [UserController::class, 'update']);
        Route::delete('users/{user}', [UserController::class, 'destroy'])->middleware('admin');

        Route::apiResource('invitations', InvitationController::class)->middleware('admin');


        Route::apiResource('customers', CustomerController::class);

        Route::apiResource('invoices', InvoiceController::class);
        Route::post('invoices/bulk', [InvoiceController::class, 'bulkStore']);

        Route::get('logout',  [AuthController::class, 'logout']);
    });
});


