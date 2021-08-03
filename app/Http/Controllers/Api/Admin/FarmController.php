<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Farm;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FarmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $authUser = User::findOrFail(auth()->id());

            $farmsQuery = $authUser->farms();

            $perPage = $request->has('limit') ? intval($request->limit) : 10;

            $farms = $farmsQuery->paginate($perPage);

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => $farms
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
            $data = $this->validate($request, [
                'location' => 'required|string|max:255',
                'area_of_hector' => 'required|max:255'
            ]);

            $user = User::findOrFail(auth()->id());

            $farm = Farm::create($data);

            $user->farms()->attach($farm);

            return response()->json([
                'code' => 200,
                'message' => 'Farm Created Successfully',
                'data' => $farm
            ]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $farm)
    {
        try {
            $farm = Farm::findOrFail($farm);

            $data = $this->validate($request, [
                'location' => 'nullable|string|max:255',
                'area_of_hector' => 'nullable|max:255'
            ]);

            $farm->update($data);

            return response()->json([
                'code' => 200,
                'message' => 'Farm Updated Successfully',
                'data' => $farm
            ]);
        } catch (ModelNotFoundException $exception){
            return response()->json([
                'code' => 404,
                'message' => 'Farm Not Found.',
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
    public function destroy($farm)
    {
        try {
            $farm = Farm::findOrFail($farm);

            $farm->delete();

            return response()->json([
                "code" => 200,
                "message" => "Farm Deleted Successfully!",
                "data" => null
            ]);
        } catch (ModelNotFoundException $exception){
            return response()->json([
                'code' => 404,
                'message' => 'Farm Not Found.',
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
