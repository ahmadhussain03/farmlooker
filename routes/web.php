<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\BreedController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SinglePageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PlanController;
use App\Models\City;
use App\Models\TradingAnimal;

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

Route::get('subscription/success', [PaymentController::class, 'success'])->name('subscription.success');
Route::get('subscription/cancel', [PaymentController::class, 'cancel'])->name('subscription.cancel');

Route::group(['middleware' => 'auth', 'prefix' => 'admin', 'as' => 'admin.'], function(){

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('notification', NotificationController::class)->only(['index', 'store', 'create']);

    Route::resource('type', TypeController::class);

    Route::resource('breed', BreedController::class);

    Route::resource('plan', PlanController::class);

});

require __DIR__.'/auth.php';

Route::any('/{any?}', [SinglePageController::class, 'index'])->where('any', '.*')->name('spa');
