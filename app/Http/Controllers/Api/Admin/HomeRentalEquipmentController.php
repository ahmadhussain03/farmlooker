<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Models\RentalEquipment;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class HomeRentalEquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $rentalEquipmentQuery = RentalEquipment::query()->with(['user', 'images']);

            if($request->has('name')){
                $rentalEquipmentQuery->where('name', 'like', '%' . $request->name . '%');
            }

            if($request->has('model')){
                $rentalEquipmentQuery->where('model', 'like', '%' . $request->model . '%');
            }

            if($request->has('location')){
                $rentalEquipmentQuery->where('location', 'like', '%' . $request->location . '%');
            }

            if($request->has('from_rent')){
                $rentalEquipmentQuery->where('rent', '>=', $request->from_rent);
            }

            if($request->has('to_rent')){
                $rentalEquipmentQuery->where('rent', '<=', $request->to_rent);
            }

            if($request->has('sort')){
                $rentalEquipmentQuery->orderBy('created_at', $request->sort);
            }

            $perPage = $request->has('limit') ? intval($request->limit) : 10;

            $rentalEquipments = $rentalEquipmentQuery->paginate($perPage);

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => $rentalEquipments
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
