<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\HoursController;
use App\Http\Controllers\Api\CampaignUserController;

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
    Route::get('/contractedHours', 'contractedHours');
    Route::get('/searchUser', 'search');
    Route::get('/rolesUser', 'roles');
    Route::get('/countUsers', 'count');
    Route::get('/paginateUsers', 'paginate');

});


Route::controller(CampaignController::class)->group(function(){

    Route::get('/campaigns', 'index');
    Route::get('/countCampaigns', 'count');
    Route::get('/campaign', 'store');
    Route::get('/campaign/{id}', 'show');
    Route::put('/campaign/{id}', 'update');
    Route::delete('/campaign/{id}', 'destroy');
    Route::get('/activecampaigns', 'active');
    Route::get('/searchCampaign', 'search');
    Route::get('/paginateCampaigns', 'paginate');



});

Route::controller(CampaignUserController::class)->group(function(){

    Route::get('/campaignsUsers', 'index');
    Route::get('/campaignUser', 'store');
    Route::get('/campaignUser/{id}', 'show');
    Route::put('/campaignUser/{id}', 'update');
    Route::delete('/campaignUser/{id}', 'destroy');
    Route::get('/participatingUsers', 'participatingUsers');
    Route::get('/checkIfUserCampaignExists', 'checkIfUserCampaignExists');
    Route::get('/campaigns_current_user', 'campaignByUser');

});



Route::controller(HoursController::class)->group(function(){

    Route::get('/hours', 'index');
    Route::get('/hour', 'store');
    Route::get('/hour/{id}', 'show');
    Route::put('/hour/{id}', 'update');
    Route::get('/updateWork', 'updateWork');
    Route::delete('/hour/{id}', 'destroy');
    Route::get('/validateHours', 'validateHours');
    Route::get('/insertedHours', 'insertedHours');
    Route::get('/searchHours', 'search');
    Route::get('/hoursByCampaign', 'hoursByCampaign');
    Route::get('/countHours', 'count');
    Route::get('/paginateHours', 'paginate');

});