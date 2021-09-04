
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\FarmController;
use App\Http\Controllers\Api\Admin\AssetController;
use App\Http\Controllers\Api\Admin\AnimalController;
use App\Http\Controllers\Api\Admin\WorkerController;
use App\Http\Controllers\Api\Admin\ExpenseController;
use App\Http\Controllers\Api\Admin\SummaryController;
use App\Http\Controllers\Api\Admin\ManagerController;
use App\Http\Controllers\Api\Admin\ProfileController;
use App\Http\Controllers\Api\Admin\OrderFeedController;
use App\Http\Controllers\Api\Admin\SubscriptionController;
use App\Http\Controllers\Api\Admin\NotificationController;
use App\Http\Controllers\Api\Admin\DiseaseAlertController;
use App\Http\Controllers\Api\Admin\TradingAnimalController;
use App\Http\Controllers\Api\Admin\VaccineRecordController;
use App\Http\Controllers\Api\Admin\RentalEquipmentController;
use App\Http\Controllers\Api\Admin\HomeTradingAnimalController;
use App\Http\Controllers\Api\Admin\HomeRentalEquipmentController;

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

Route::group(['middleware' => ['auth:sanctum', 'admin']], function(){
    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout']);

    // User Subscriptions Routes
    Route::get('/subscribe/{id}', [SubscriptionController::class, 'show']);
    Route::get('subscriptions', [SubscriptionController::class, 'index']);

    // Farm Routes
    Route::apiResource('farm', FarmController::class)->except(['show']);

    //  User Detail Routes
    Route::get('/user', [AuthController::class, 'user']);

    // Profile Update Route
    Route::post('/profile', [ProfileController::class, 'update']);

    // User Notification Routes
    Route::get('notifications', [NotificationController::class, 'index']);

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
    Route::apiResource('worker', WorkerController::class);

    // Assets Routes
    Route::apiResource('asset', AssetController::class);

    // Summary Routes
    Route::get('/summary', [SummaryController::class, 'index']);
    Route::get('/summary/{type}', [SummaryController::class, 'show']);

    // Purchased Animals List
    Route::get('expense', [ExpenseController::class, 'index']);

    // Manager Routes
    Route::apiResource('manager', ManagerController::class);

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
