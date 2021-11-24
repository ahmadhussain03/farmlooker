
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\FarmController;
use App\Http\Controllers\Api\Admin\AssetController;
use App\Http\Controllers\Api\Admin\AnimalController;
use App\Http\Controllers\Api\Admin\IncomeController;
use App\Http\Controllers\Api\Admin\SalaryController;
use App\Http\Controllers\Api\Admin\WorkerController;
use App\Http\Controllers\Api\Admin\ExpenseController;
use App\Http\Controllers\Api\Admin\ManagerController;
use App\Http\Controllers\Api\Admin\ProfileController;
use App\Http\Controllers\Api\Admin\SummaryController;
use App\Http\Controllers\Api\Admin\WeatherController;
use App\Http\Controllers\Api\Admin\OrderFeedController;
use App\Http\Controllers\Api\Admin\AnimalSoldController;
use App\Http\Controllers\Api\Admin\IncomeChartController;
use App\Http\Controllers\Api\Admin\OtherIncomeController;
use App\Http\Controllers\Api\Admin\DiseaseAlertController;
use App\Http\Controllers\Api\Admin\ExpenseChartController;
use App\Http\Controllers\Api\Admin\NotificationController;
use App\Http\Controllers\Api\Admin\SubscriptionController;
use App\Http\Controllers\Api\Admin\MiscelleneousController;
use App\Http\Controllers\Api\Admin\TradingAnimalController;
use App\Http\Controllers\Api\Admin\VaccineRecordController;
use App\Http\Controllers\Api\Admin\RentalEquipmentController;
use App\Http\Controllers\Api\Admin\OrderFeedExpenseController;
use App\Http\Controllers\Api\Admin\EmailVerificationController;
use App\Http\Controllers\Api\Admin\HomeTradingAnimalController;
use App\Http\Controllers\Api\Admin\HomeRentalEquipmentController;
use App\Models\RentalEquipment;
use App\Models\TradingAnimal;

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

    // Email Verification Routes
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])->middleware('throttle:6,1');
    Route::post('/email/verify', [EmailVerificationController::class, 'verify']);

    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout']);

    // Verified Routes
    // Route::group(['middlware' => 'verified'], function(){

    // });

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
    Route::delete('/animal', [AnimalController::class, 'delete']);

    // Order Feed Routes
    Route::apiResource('order_feed', OrderFeedController::class);

    // Rental Equipment Routes
    Route::apiResource('rental_equipment', RentalEquipmentController::class);
    Route::delete('rental_equipment', [RentalEquipmentController::class, 'delete']);

     // Animal Trading Routes
     Route::apiResource('trading_animal', TradingAnimalController::class);
     Route::delete('trading_animal', [TradingAnimalController::class, 'delete']);

    // Disease Alert Routes
    Route::apiResource('disease_alert', DiseaseAlertController::class)->except(['update']);
    Route::delete('disease_alert', [DiseaseAlertController::class, 'delete']);

    // Vaccine Record Routes
    Route::apiResource('vaccine_record', VaccineRecordController::class);
    Route::delete('vaccine_record', [VaccineRecordController::class, 'delete']);

    // Workers Routes
    Route::apiResource('worker', WorkerController::class);
    Route::delete('worker', [WorkerController::class, 'delete']);

    // Assets Routes
    Route::apiResource('asset', AssetController::class);
    Route::delete('asset', [AssetController::class, 'delete']);

    // Summary Routes
    Route::get('/summary', [SummaryController::class, 'index']);
    Route::get('/summary/{type}', [SummaryController::class, 'show']);

    // Purchased Animals List
    Route::get('expense', [ExpenseController::class, 'index']);

    // Manager Routes
    Route::apiResource('manager', ManagerController::class);

    // Weather Route
    Route::apiResource('weather', WeatherController::class)->only(['index']);

    // Salary Route
    Route::apiResource('salary', SalaryController::class)->only(['store']);

    // Order Feed Expense Route
    Route::apiResource('order_feed_expense', OrderFeedExpenseController::class)->only(['store']);

    // Miscelleneous Route
    Route::apiResource('miscelleneous', MiscelleneousController::class)->only(['store']);

    // Animal Sold Route
    Route::apiResource('animal_sold', AnimalSoldController::class)->only(['store']);

    // Other Income Route
    Route::apiResource('other_income', OtherIncomeController::class)->only(['store']);

    // Income Routes
    Route::apiResource('income', IncomeController::class)->only(['index']);

    // Expense Routes
    Route::apiResource('expense', ExpenseController::class)->only(['index']);

    // Home Routes
    Route::prefix('home')->group(function () {
        // All Rental Equipment Route
        Route::get('rental_equipment', [HomeRentalEquipmentController::class, 'index']);

        // All Trading Animal Route
        Route::get('trading_animal', [HomeTradingAnimalController::class, 'index']);

        // Total Expense
        Route::get('expense/total', [ExpenseController::class, 'show']);

        // Total Expense Summary
        Route::get('expense/summary', [ExpenseController::class, 'summary']);

        // Expense Chart
        Route::get('expense_chart', [ExpenseChartController::class, 'index']);

        // Total Income Summary
        Route::get('income/summary', [IncomeController::class, 'summary']);

        // Income Chart
        Route::get('income_chart', [IncomeChartController::class, 'index']);
    });
});
