<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AnimalController;
use App\Http\Controllers\Api\SummaryController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function(){
    //  User Detail Routes
   Route::get('/user', [AuthController::class, 'user']);

    // Animal Routes
    Route::apiResource('animal', AnimalController::class);

    // Summary Routes
    Route::get('/summary', [SummaryController::class, 'index']);
    Route::get('/summary/{type}', [SummaryController::class, 'show']);
});
