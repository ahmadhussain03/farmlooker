<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthController
 * @package App\Http\Controllers\Api
 */
class AuthController extends Controller
{
    /**
     * User Register Request
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
       try {
           $this->validate($request, [
               "email" => "required|email|max:255|unique:users",
               "password" => "required|confirmed|min:6|max:255",
               "first_name" => "required|string|max:255",
               "last_name" => "required|string|max:255",
               "phone_no" => "required|string|phone:AUTO,SA|max:20",
               "experience" => "required|string",
               "device_token" => "required|string|max:255",
               "device_name" => "required|string|max:255"
           ]);

           $user = User::create([
               'first_name' => $request->first_name,
               'last_name' => $request->last_name,
               'phone_no' => $request->phone_no,
               'email' => $request->email,
               'experience' => $request->experience,
               'password' => Hash::make($request->password)
           ]);

            $token = $user->createToken($request->device_name)->plainTextToken;
            $user->device_token = $request->device_token;
            $user->save();

           return response()->json([
               'code' => 200,
               'message' => 'User Register Successfully',
               'data' => ["user" => $user, "token" => $token]
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

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            $user->currentAccessToken()->delete();
            $user->device_token = null;
            $user->save();

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => null
            ], Response::HTTP_OK);
        } catch (\Exception $exception){
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * User Login Request
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {

            $this->validate($request, [
                "email" => "required|email|max:255",
                "password" => "required|min:6|max:255",
                "device_token" => "required|string|max:255",
                "device_name" => "required|string|max:255",
            ]);

            $user = User::with('activeSubscription')->where('email', $request->email)->first();
            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $token = $user->createToken($request->device_name)->plainTextToken;
            $user->device_token = $request->device_token;
            $user->save();

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => ["user" => $user, "token" => $token]
            ], Response::HTTP_OK);
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
     * Authenticated User Detail
     *
     * @return JsonResponse
     */
    public function user(): JsonResponse
    {
        $user = User::with('activeSubscription')->findOrFail(auth()->id());

        return response()->json([
            'code' => 200,
            'message' => null,
            'data' => $user
        ], Response::HTTP_OK);
    }
}
