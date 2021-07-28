
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\AnimalController;
use App\Http\Controllers\Api\WorkerController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\SummaryController;
use App\Http\Controllers\Api\OrderFeedController;
use App\Http\Controllers\Api\DiseaseAlertController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\TradingAnimalController;
use App\Http\Controllers\Api\VaccineRecordController;
use App\Http\Controllers\Api\RentalEquipmentController;
use App\Http\Controllers\Api\HomeTradingAnimalController;
use App\Http\Controllers\Api\HomeRentalEquipmentController;
use App\Http\Controllers\Api\SubscriptionController;

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
    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout']);

    // User Subscriptions Routes
    Route::get('/subscribe/{id}', [SubscriptionController::class, 'show']);
    Route::get('subscriptions', [SubscriptionController::class, 'index']);

    // User Notification Routes
    Route::get('notifications', [NotificationController::class, 'index']);

    //  User Detail Routes
   Route::get('/user', [AuthController::class, 'user']);

    // Animal Routes
    Route::apiResource('animal', AnimalController::class);
    Route::get('animal/{id}/tree', [AnimalController::class, 'tree']);

    // Order Feed Routes
    Route::apiResource('order_feed', OrderFeedController::class);

    // Rental Equipment Routes
    Route::apiResource('rental_equipment', RentalEquipmentController::class);

     // Animal Trading Routes
     Route::apiResource('trading_animal', TradingAnimalController::class);

    // Disease Alert Routes
    Route::apiResource('disease_alert', DiseaseAlertController::class)->except(['update']);

    // Vaccine Record Routes
    Route::apiResource('vaccine_record', VaccineRecordController::class);

    // Workers Routes
    Route::resource('worker', WorkerController::class);

    // Assets Routes
    Route::apiResource('asset', AssetController::class);

    // Summary Routes
    Route::get('/summary', [SummaryController::class, 'index']);
    Route::get('/summary/{type}', [SummaryController::class, 'show']);

    // Purchased Animals List
    Route::get('expense', [ExpenseController::class, 'index']);

    // Home Routes
    Route::prefix('home')->group(function () {
        // All Rental Equipment Route
        Route::get('rental_equipment', [HomeRentalEquipmentController::class, 'index']);

        // All Trading Animal Route
        Route::get('trading_animal', [HomeTradingAnimalController::class, 'index']);

        // Total Expense
        Route::get('expense/total', [ExpenseController::class, 'show']);
    });
});
