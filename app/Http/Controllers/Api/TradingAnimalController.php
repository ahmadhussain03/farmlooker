<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\TradingAnimal;
use App\Http\Controllers\Controller;
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
            $tradingAnimalQuery = TradingAnimal::query();

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
        try {
            $this->validate($request, [
                'type' => 'required|string|max:255',
                'price' => 'required|numeric',
                'location' => 'required|string',
                'dated' => 'required|date',
                'dob' => 'required|date',
                'image' => 'required|mimes:jpeg,jpg,png,bmp'
            ]);

            $imageName = time() . $request->image->getClientOriginalName();
            if($request->image->move('images/trading_animal/', $imageName)){
                $tradingAnimal = TradingAnimal::create(array_merge($request->all(), [
                    'user_id' => auth()->id(),
                    'image' => 'images/trading_animal/' . $imageName
                ]));

                return response()->json([
                    'code' => 200,
                    'message' => 'Trading Animal Created Successfully',
                    'data' => $tradingAnimal
                ]);
            } else {
                return response()->json([
                    'code' => 500,
                    'message' => 'Error Uploading Image.',
                    'data' => null
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (ValidationException $exception){
            return response()->json([
                'code' => 422,
                'message' => $exception->getMessage(),
                'data' => $exception->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $exception){
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
                'image' => 'sometimes|mimes:jpeg,jpg,png,bmp'
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
            $tradingAnimal = TradingAnimal::findOrFail($trading_animal);

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
}
