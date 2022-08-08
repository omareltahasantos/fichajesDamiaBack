<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\HoursController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(UserController::class)->group(function(){

    Route::get('/users', 'index');
    Route::get('/user', 'store');
    Route::get('/user/{id}', 'show');
    Route::put('/user/{id}', 'update');
    Route::delete('/user/{id}', 'destroy');

    Route::get('/checkuser', 'checkUser');

});


Route::controller(CampaignController::class)->group(function(){

    Route::get('/campaigns', 'index');
    Route::get('/campaign', 'store');
    Route::get('/campaign/{id}', 'show');
    Route::put('/campaign/{id}', 'update');
    Route::delete('/campaign/{id}', 'destroy');

});



Route::controller(HoursController::class)->group(function(){

    Route::get('/hours', 'index');
    Route::get('/hour', 'store');
    Route::get('/hour/{id}', 'show');
    Route::put('/hour/{id}', 'update');
    Route::delete('/hour/{id}', 'destroy');

});

