<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Animal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        try {

            $perPage = $request->has('limit') ? intval($request->limit) : 10;

            /** @var App\Models\User */
            $currentUser = auth()->user();
            $purchaseAnimals = $currentUser->animals()->where('animals.add_as', 'purchased')->paginate($perPage);

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => $purchaseAnimals
            ]);
         } catch (\Exception $exception){
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show()
    {
        try {

            /** @var App\Models\User */
            $currentUser = auth()->user();
            $purchaseTotal = $currentUser->animals()->where('animals.add_as', 'purchased')->sum('animals.price');

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => $purchaseTotal
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
