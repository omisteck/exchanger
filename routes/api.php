<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FixerController;
use App\Http\Controllers\ThresholdController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix("/v1")->group(function(){

    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login'])->name('login');
    
    /*
    |--------------------------------------------------------------------------
    | Auth Protected Routes
    |-------------------------------------------------------------------------
    */

    Route::prefix('/user')->middleware('auth:api')->group(function(){

        Route::post('/changecurrency', [FixerController::class, 'changeCurrency']);

        Route::post('/rates', [FixerController::class, 'rateList']);

        Route::post('/set/threshold', [ThresholdController::class, 'store']);

        Route::post('/logout', [UserController::class, 'logout']);
    });

});