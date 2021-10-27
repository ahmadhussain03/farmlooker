<?php

namespace App\Http\Controllers\Api\Admin;

use DataTables;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\RentalEquipment;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RentalEquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $rentalEquipmentQuery = RentalEquipment::query()->with(['images'])->where('user_id', auth()->id());

            if($request->has('client') && $request->client === 'datatable'){
                return DataTables::eloquent($rentalEquipmentQuery)
                        ->setRowId('id')
                        ->addColumn('image', function($rentalEquipment){
                            $image = $rentalEquipment->images()->first();
                            if($image){
                                $image = asset($image->image);
                                return "<div class='aspect-w-16 aspect-h-10'><img class='object-center object-contain border text-center rounded shadow ' src=". $image ."></div>";
                            } else {
                                return "-";
                            }
                        })
                        ->editColumn('dated', function($rentalEquipment){
                            return $rentalEquipment->dated->toFormattedDateString();
                        })
                        ->rawColumns(['image'])
                        ->addIndexColumn()->toJson();
            }

            $perPage = $request->has('limit') ? intval($request->limit) : 10;

            $rentalEquipments = $rentalEquipmentQuery->search()->paginate($perPage);

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255|min:2',
            "model" => "required|string|max:255",
            'rent' => 'required|numeric',
            'location' => 'required|string',
            'dated' => 'required|date',
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'required|mimes:jpeg,jpg,png,bmp',
            "phone" => "required|string|max:20"
        ]);

        $rentalEquipment = RentalEquipment::create([
            'name' => $request->name,
            'model' => $request->model,
            'rent' => $request->rent,
            'location' => $request->location,
            'dated' => $request->dated,
            'phone' => $request->phone,
            'user_id' => auth()->id()
        ]);

        foreach($request->images as $uploadedImage){
            $image = new Image();
            $imageName = time() . $uploadedImage->getClientOriginalName();
            $uploadedImage->move('images/rental_equipment/', $imageName);

            $image->image = 'images/rental_equipment/' . $imageName;

            $rentalEquipment->images()->save($image);
        }

        return response()->success($rentalEquipment, "Rental Equipment Created Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($rental_equipment)
    {
        try {
            $rentalEquipment = RentalEquipment::findOrFail($rental_equipment);

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => $rentalEquipment
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $exception){
            return response()->json([
                'code' => 404,
                'message' => 'Rental Equipment Not Found.',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $exception){
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $rental_equipment)
    {
        try {
            $rentalEquipment = RentalEquipment::findOrFail($rental_equipment);

            $this->validate($request, [
                'name' => 'nullable|string|max:255|min:2',
                "model" => "nullable|string|max:255",
                'rent' => 'nullable|numeric',
                'location' => 'nullable|string',
                'dated' => 'nullable|date',
                'image' => 'sometimes|mimes:jpeg,jpg,png,bmp',
                "phone" => "nullable|string|max:20"
            ]);

            $image = $rentalEquipment->image;

            if($request->image){
                unlink($rentalEquipment->getRawOriginal('image'));

                $imageName = time() . $request->image->getClientOriginalName();
                if($request->image->move('images/rental_equipment/', $imageName)){
                    $image = 'images/rental_equipment/' . $imageName;
                } else {
                    return response()->json([
                        'code' => 500,
                        'message' => 'Error Uploading Image.',
                        'data' => null
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }

            $rentalEquipment->update(array_merge($request->all(), ['image' => $image]));

            return response()->json([
                'code' => 200,
                'message' => 'Rental Equipment Updated Successfully',
                'data' => $rentalEquipment
            ]);
        } catch (ModelNotFoundException $exception){
            return response()->json([
                'code' => 404,
                'message' => 'Rental Equipment Not Found.',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        } catch (ValidationException $exception){
            return response()->json([
                'code' => 422,
                'message' => $exception->getMessage(),
                'data' => $exception->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($rental_equipment)
    {
        try {
            $rentalEquipment = RentalEquipment::findOrFail($rental_equipment);

            if(file_exists($rentalEquipment->getRawOriginal('image'))){
                unlink($rentalEquipment->getRawOriginal('image'));
            }

            $rentalEquipment->delete();

            return response()->json([
                "code" => 200,
                "message" => "Rental Equipment Deleted Successfully!",
                "data" => null
            ]);
        } catch (ModelNotFoundException $exception){
            return response()->json([
                'code' => 404,
                'message' => 'Rental Equipment Not Found.',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $exception){
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
