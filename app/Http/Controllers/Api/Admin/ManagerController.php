<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
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

            $managerQuery = User::query()->where('user_type', 'moderator')->where('parent_id', auth()->id());

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
                "phone_no" => "required|string|phone:AUTO,SA|max:20"
            ]);

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone_no' => $request->phone_no,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'parent_id' => auth()->id(),
            ]);

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
        //
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
                "email" => "nullable|email|max:255|unique:users,email," . $manager->id,
                "password" => "nullable|confirmed|min:6|max:255",
                "first_name" => "nullable|string|max:255",
                "last_name" => "nullable|string|max:255",
                "phone_no" => "nullable|string|phone:AUTO,SA|max:20"
            ]);

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
}
