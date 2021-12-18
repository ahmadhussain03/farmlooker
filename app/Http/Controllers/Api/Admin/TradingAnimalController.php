<?php

namespace App\Http\Controllers\Api\Admin;

use DataTables;
use Illuminate\Http\Request;
use App\Models\TradingAnimal;
use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TradingAnimalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $tradingAnimalQuery = TradingAnimal::query()->with(['images'])->where('user_id', auth()->id());

            // if($request->has('client') && $request->client === 'datatable'){
            //     return DataTables::eloquent($tradingAnimalQuery)
            //             ->setRowId('id')
            //             ->addColumn('image', function($tradingAnimal){
            //                 $image = $tradingAnimal->images()->first();
            //                 if($image){
            //                     $image = asset($image->image);
            //                     return "<div class='aspect-w-16 aspect-h-10'><img class='object-center object-contain border text-center rounded shadow ' src=". $image ."></div>";
            //                 } else {
            //                     return "-";
            //                 }
            //             })
            //             ->editColumn('dob', function($tradingAnimal){
            //                 return $tradingAnimal->dob->toFormattedDateString();
            //             })
            //             ->editColumn('dated', function($tradingAnimal){
            //                 return $tradingAnimal->dated->toFormattedDateString();
            //             })
            //             ->rawColumns(['image'])
            //             ->addIndexColumn()->toJson();
            // }

            if($request->has('search') && $request->search != ""){
                $search = $request->search;
                $tradingAnimalQuery
                    ->where('type', 'like', '%' . $search . '%')
                    ->orWhere('price', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%')
                    ->orWhere('dated', 'like', '%' . $search . '%')
                    ->orWhere('dob', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            }

            if($request->has('sort_field') && $request->has('sort_order')){
                $relationArray = explode(".", $request->sort_field);
                if(count($relationArray) > 1){
                    $relation = $relationArray[0];
                    $field = $relationArray[1];
                    $sortOrder = $request->sort_order;

                    $tradingAnimalQuery->with([$relation => function($query) use ($field, $sortOrder) {
                        $query->orderBy($field, $sortOrder);
                    }]);
                } else {
                    $tradingAnimalQuery->orderBy($request->sort_field, $request->sort_order);
                }
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'type' => 'required|string|max:255',
            'price' => 'required|numeric',
            'location' => 'required|string',
            'dated' => 'required|date',
            'dob' => 'required|date',
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'required|mimes:jpeg,jpg,png,bmp',
            "phone" => "required|string|max:20"
        ]);

        $tradingAnimal = TradingAnimal::create([
            'type' => $request->type,
            'price' => $request->price,
            'location' => $request->location,
            'dated' => $request->dated,
            'dob' => $request->dob,
            'phone' => $request->phone,
            'user_id' => auth()->id()
        ]);

        foreach($request->images as $uploadedImage){
            $image = new Image();
            $imageName = time() . $uploadedImage->getClientOriginalName();
            $uploadedImage->move('images/trading_animal/', $imageName);

            $image->image = 'images/trading_animal/' . $imageName;

            $tradingAnimal->images()->save($image);
        }

        return response()->success($tradingAnimal, "Trading Animal Created Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($trading_animal)
    {
        try {
            $tradingAnimal = TradingAnimal::findOrFail($trading_animal);

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => $tradingAnimal
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
    public function update(Request $request, $trading_animal)
    {
        try {
            $tradingAnimal = TradingAnimal::findOrFail($trading_animal);

            $data = $this->validate($request, [
                'type' => 'nullable|string|max:255',
                'price' => 'nullable|numeric',
                'location' => 'nullable|string',
                'dated' => 'nullable|date',
                'dob' => 'nullable|date',
                'image' => 'sometimes|mimes:jpeg,jpg,png,bmp',
                "phone" => "nullable|string|max:20"
            ]);

            $image = $tradingAnimal->image;

            if($request->image){
                unlink($tradingAnimal->getRawOriginal('image'));

                $imageName = time() . $request->image->getClientOriginalName();
                if($request->image->move('images/trading_animal/', $imageName)){
                    $image = 'images/trading_animal/' . $imageName;
                } else {
                    return response()->json([
                        'code' => 500,
                        'message' => 'Error Uploading Image.',
                        'data' => null
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }

            $tradingAnimal->update(array_merge($request->all(), ['image' => $image]));

            return response()->json([
                'code' => 200,
                'message' => 'Trading Animal Updated Successfully',
                'data' => $tradingAnimal
            ]);
        } catch (ModelNotFoundException $exception){
            return response()->json([
                'code' => 404,
                'message' => 'Trading Animal Not Found.',
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
    public function destroy($trading_animal)
    {
        try {
            $tradingAnimal = TradingAnimal::where('user_id', auth()->id())->where('id', $trading_animal)->firstOrFail();

            if(file_exists($tradingAnimal->getRawOriginal('image'))){
                unlink($tradingAnimal->getRawOriginal('image'));
            }

            $tradingAnimal->delete();

            return response()->json([
                "code" => 200,
                "message" => "Trading Animal Deleted Successfully!",
                "data" => null
            ]);
        } catch (ModelNotFoundException $exception){
            return response()->json([
                'code' => 404,
                'message' => 'Trading Animal Not Found.',
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
     * Delete Trading Animals Animal using Bulk IDs
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request)
    {
        $request->validate([
            'tradings' => 'required|array|min:1',
            'tradings.*' => 'integer'
        ]);

         /** @var App\Models\User */
         $currentUser = auth()->user();
         TradingAnimal::where('user_id', $currentUser->id)->whereIn('id', $request->tradings)->delete();

        return response()->success(null, "Trading Animals Deleted Successfully!");
    }
}
