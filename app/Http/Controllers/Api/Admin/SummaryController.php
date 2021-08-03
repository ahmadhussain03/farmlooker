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
            $animalSummary = $currentUser->animals()->select(['animals.type', DB::raw('COUNT(animals.type) as count')])->groupBy(['animals.type', 'farm_user.user_id'])->get();

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => $animalSummary
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

            $animalSexDetail = $currentUser->animals()->select(['animals.sex', DB::raw('COUNT(animals.sex) as count')])->where('animals.type', $type)->groupBy(['animals.sex', 'farm_user.user_id'])->get();
            $animalHealthDetail = $currentUser->animals()->select(['animals.disease', DB::raw('COUNT(animals.disease) as count')])->where('animals.type', $type)->groupBy(['animals.disease', 'farm_user.user_id'])->get();

            $perPage = $request->has('limit') ? intval($request->limit) : 10;

            $animals = $currentUser->animals()->where('type', $type)->paginate($perPage);

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
