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
            $purchaseAnimals = Animal::where('add_as', 'purchased')->where('user_id', auth()->id())->paginate($perPage);

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

            $purchaseTotal = Animal::where('add_as', 'purchased')->where('user_id', auth()->id())->sum('price');

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
