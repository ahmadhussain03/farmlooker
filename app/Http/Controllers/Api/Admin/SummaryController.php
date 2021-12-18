<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Animal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SummaryController
 * @package App\Http\Controllers\Api
 */
class SummaryController extends Controller
{
    /**
     * Animal Summary
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            /** @var App\Models\User */
            $currentUser = auth()->user();
            $animalSummary = $currentUser->animals()->with(['type'])->select(['animals.type_id', DB::raw('COUNT(animals.type_id) as count')])->groupBy(['animals.type_id', 'farm_user.user_id'])->get();
            $animalSexSummary = $currentUser->animals()->select(['animals.sex', DB::raw('COUNT(animals.sex) as count')])->groupBy(['animals.sex', 'farm_user.user_id'])->get();
            $sick = $currentUser->animals()->select(['animals.disease', DB::raw('COUNT(animals.disease) as count')])->where('animals.disease', 'sick')->groupBy(['animals.disease', 'farm_user.user_id'])->first();
            $vaccinated = $currentUser->vaccineRecords()->select(['vaccine_records.animal_id', DB::raw('COUNT(vaccine_records.animal_id) as count')])->groupBy(['vaccine_records.animal_id', 'farm_user.user_id'])->count();
            $workerCount = $currentUser->workers()->count();
            $assetsCount = $currentUser->assets()->count();
            $rentalEquipmentCount = $currentUser->rentalEquipments()->count();
            $tradingAnimalCount = $currentUser->tradingAnimals()->count();
            $liveAdsCount = $tradingAnimalCount + $rentalEquipmentCount;

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => ['summary' => $animalSummary, 'worker' => $workerCount, 'assets' => $assetsCount, 'rental_equipment' => $rentalEquipmentCount, 'live_ads' => $liveAdsCount, 'health_summary' => ['sex' => $animalSexSummary, 'vaccinated' => $vaccinated, 'sick' => $sick]]
            ]);
        } catch (\Exception $exception){
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Request $request, $type)
    {
        try {

            /** @var App\Models\User */
            $currentUser = auth()->user();

            $animalSexDetail = $currentUser->animals()->select(['animals.sex', DB::raw('COUNT(animals.sex) as count')])->where('animals.type_id', $type)->groupBy(['animals.sex', 'farm_user.user_id'])->get();
            $animalHealthDetail = $currentUser->animals()->select(['animals.disease', DB::raw('COUNT(animals.disease) as count')])->where('animals.type_id', $type)->groupBy(['animals.disease', 'farm_user.user_id'])->get();

            $perPage = $request->has('limit') ? intval($request->limit) : 10;

            $animals = $currentUser->animals()->where('type_id', $type)->paginate($perPage);

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => [
                    'gender' => $animalSexDetail,
                    'disease' => $animalHealthDetail,
                    'animals' => $animals
                ]
            ], Response::HTTP_OK);

        } catch (\Exception $exception){
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
