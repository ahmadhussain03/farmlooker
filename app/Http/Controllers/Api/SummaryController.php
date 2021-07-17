<?php

namespace App\Http\Controllers\Api;

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
            $animalSummary = Animal::select(['type', DB::raw('COUNT(type) as count')])->where('user_id', auth()->id())->groupBy('type')->get();

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

            $animalSexDetail = Animal::select(['sex', DB::raw('COUNT(sex) as count')])->where('type', $type)->where('user_id', auth()->id())->groupBy('sex')->get();
            $animalHealthDetail = Animal::select(['disease', DB::raw('COUNT(disease) as count')])->where('type', $type)->where('user_id', auth()->id())->groupBy('disease')->get();

            $perPage = $request->has('limit') ? intval($request->limit) : 10;

            $animals = Animal::where('type', $type)->where('user_id', auth()->id())->paginate($perPage);

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
