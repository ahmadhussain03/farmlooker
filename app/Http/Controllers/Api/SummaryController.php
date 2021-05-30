<?php

namespace App\Http\Controllers\Api;

use App\Models\Animal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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
        $animalSummary = Animal::select(['type', DB::raw('COUNT(type) as count')])->groupBy('type')->get();

        return response()->json($animalSummary);
    }
}
