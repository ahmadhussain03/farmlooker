
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
use App\Http\Controllers\Api\Admin\OrderFeedController;
use App\Http\Controllers\Api\Admin\SubscriptionController;
use App\Http\Controllers\Api\Admin\NotificationController;
use App\Http\Controllers\Api\Admin\DiseaseAlertController;
use App\Http\Controllers\Api\Admin\TradingAnimalController;
use App\Http\Controllers\Api\Admin\VaccineRecordController;
use App\Http\Controllers\Api\Admin\RentalEquipmentController;
use App\Http\Controllers\Api\Admin\HomeTradingAnimalController;
use App\Http\Controllers\Api\Admin\HomeRentalEquipmentController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\TypeController;

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

Route::middleware(['auth:api'])->get('/user', function(Request $request){
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::get('countries', [CountryController::class, 'countries']);
    Route::get('states/{id}', [CountryController::class, 'states']);
    Route::get('cities/{id}', [CountryController::class, 'cities']);

    Route::get('types', [TypeController::class, 'types']);
    Route::get('breeds/{id}', [TypeController::class, 'breeds']);
});
