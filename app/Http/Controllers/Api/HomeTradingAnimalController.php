<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\TradingAnimal;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class HomeTradingAnimalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $tradingAnimalQuery = TradingAnimal::query();

            if($request->has('type')){
                $tradingAnimalQuery->where('type', 'like', '%' . $request->type . '%');
            }

            if($request->has('location')){
                $tradingAnimalQuery->where('location', 'like', '%' . $request->location . '%');
            }

            if($request->has('from_price')){
                $tradingAnimalQuery->where('price', '>=', $request->from_price);
            }

            if($request->has('to_price')){
                $tradingAnimalQuery->where('price', '<=', $request->to_price);
            }

            if($request->has('sort')){
                $tradingAnimalQuery->orderBy('created_at', $request->sort);
            }

            $perPage = $request->has('limit') ? intval($request->limit) : 10;

            $tradingAnimals = $tradingAnimalQuery->paginate($perPage);

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => $tradingAnimals
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
