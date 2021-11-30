<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $managerQuery = User::query()->with(['farm'])->where('user_type', 'moderator')->where('parent_id', auth()->id());

            if($request->has('sort_field') && $request->has('sort_order')){
                $relationArray = explode(".", $request->sort_field);
                if(count($relationArray) > 1){
                    $relation = $relationArray[0];
                    $field = $relationArray[1];
                    $sortOrder = $request->sort_order;

                    $managerQuery->with([$relation => function($query) use ($field, $sortOrder) {
                        $query->orderBy($field, $sortOrder);
                    }]);
                } else {
                    $managerQuery->orderBy($request->sort_field, $request->sort_order);
                }
            }

            $perPage = $request->has('limit') ? intval($request->limit) : 10;

            $managers = $managerQuery->search()->paginate($perPage);

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => $managers
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
                "email" => "required|email|max:255|unique:users",
                "password" => "required|confirmed|min:6|max:255",
                "first_name" => "required|string|max:255",
                "last_name" => "required|string|max:255",
                "phone_no" => "required|string|max:20",
                "farm_id" => 'required|integer|min:1'
            ]);

            /** @var App\Models\User */
            $currentUser = auth()->user();
            $farm = $currentUser->farms()->where('farms.id', $request->farm_id)->firstOrFail();

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone_no' => $request->phone_no,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'parent_id' => $currentUser->id,
            ]);


            $user->farms()->attach($farm);
            $user->forceFill(['user_type' => 'moderator'])->save();

            return response()->json([
                'code' => 200,
                'message' => 'Manager Created Successfully',
                'data' => $user
            ], Response::HTTP_OK);
        } catch (ValidationException $exception){
            return response()->json([
                'code' => 422,
                'message' => $exception->getMessage(),
                'data' => $exception->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(\Exception $exception){
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
    public function show($id)
    {
        /** @var App\Models\User */
        $currentUser = auth()->user();
        $manager = User::with(['farm'])->where('user_type', 'moderator')->where('parent_id', $currentUser->id)->where('id', $id)->firstOrFail();

        return response()->success($manager);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $manager)
    {
        try {
            $manager = User::where('id', $manager)->where('user_type', 'moderator')->where('parent_id', auth()->id())->firstOrFail();

            $data = $this->validate($request, [
                "email" => "email|max:255|unique:users,email," . $manager->id,
                "password" => "confirmed|min:6|max:255",
                "first_name" => "string|max:255",
                "last_name" => "string|max:255",
                "phone_no" => "string|max:20",
                "farm_id" => 'integer|min:1'
            ]);

            if($request->farm_id){
                /** @var App\Models\User */
                $currentUser = auth()->user();
                $farm = $currentUser->farms()->where('farms.id', $request->farm_id)->firstOrFail();
                $manager->farms()->sync($farm);
            }

            $manager->update($data);

            return response()->json([
                'code' => 200,
                'message' => 'Manager Updated Successfully',
                'data' => $manager
            ]);
        } catch (ModelNotFoundException $exception){
            return response()->json([
                'code' => 404,
                'message' => 'Manager Not Found.',
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
    public function destroy($manager)
    {
        try {
            $manager = User::where('id', $manager)->where('user_type', 'moderator')->where('parent_id', auth()->id())->firstOrFail();

            $manager->delete();

            return response()->json([
                "code" => 200,
                "message" => "Manager Deleted Successfully!",
                "data" => null
            ]);
        } catch (ModelNotFoundException $exception){
            return response()->json([
                'code' => 404,
                'message' => 'Manager Not Found.',
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
     * Delete Manager using Bulk IDs
     *
     * @return JsonResponse
     */
    public function delete(Request $request)
    {
        $request->validate([
            'managers' => 'required|array|min:1',
            'managers.*' => 'integer'
        ]);

         /** @var App\Models\User */
         $currentUser = auth()->user();
         User::query()->where('parent_id', $currentUser->id)->where('user_type', 'moderator')->whereIn('id', $request->managers)->delete();

        return response()->success(null, "Managers Deleted Successfully!");
    }
}
