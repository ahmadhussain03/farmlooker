<?php

use App\Models\City;
use App\Models\Farm;
use App\Models\TradingAnimal;
use RakibDevs\Weather\Weather;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\BreedController;
use App\Http\Controllers\PaymentController;
use Http\Client\Exception\RequestException;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SinglePageController;
use App\Http\Controllers\NotificationController;

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

Route::get('test', function(){
    $farm = Farm::first();
    $result = Http::asForm()->post(config('services.node-server.url') . 'ee/recl', [
        'geometry' => json_encode($farm->geometry['geometry']['coordinates'])
    ])->throw()->json();

    dd($result);
});

Route::get('subscription/success', [PaymentController::class, 'success'])->name('subscription.success');
Route::get('subscription/cancel', [PaymentController::class, 'cancel'])->name('subscription.cancel');

Route::group(['middleware' => 'auth', 'prefix' => 'admin', 'as' => 'admin.'], function(){

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('notification', NotificationController::class)->only(['index', 'store', 'create']);

    Route::resource('type', TypeController::class);

    Route::resource('breed', BreedController::class);

    // Route::get('/subscribe/{id}', [SubscriptionController::class, 'show']);


});

require __DIR__.'/auth.php';

Route::any('/{any?}', [SinglePageController::class, 'index'])->where('any', '.*');

