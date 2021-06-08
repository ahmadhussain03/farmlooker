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
            $animalSummary = Animal::select(['type', DB::raw('COUNT(type) as count')])->groupBy('type')->get();

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
}
