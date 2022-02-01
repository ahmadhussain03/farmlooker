
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TypeController;
use App\Http\Controllers\Api\CountryController;

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
