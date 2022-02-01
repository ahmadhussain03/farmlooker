<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;

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
        $this->validate($request, [
            "email" => "required|email|max:255|unique:users,email",
            "password" => "required|confirmed|min:6|max:255",
            "first_name" => "required|string|max:255",
            "last_name" => "required|string|max:255",
            "phone_no" => "required|string|max:20",
            "experience" => "required|numeric",
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

        $user->sendEmailVerificationNotification();

        $user->createOrGetStripeCustomer();

        $token = $user->createToken($request->device_name)->plainTextToken;
        $user->device_token = $request->device_token;
        // $user->email_verified_at = now();
        $user->save();

        $user->refresh();

        $user->load(['farms.city.state.country']);

        return response()->success(["user" => $user, "token" => $token], "User Register Successfully");
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        $user->device_token = null;
        $user->save();

        return response()->success();
    }

    /**
     * User Login Request
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {

        $this->validate($request, [
            "email" => "required|email|max:255",
            "password" => "required|min:6|max:255",
            "device_token" => "required|string|max:255",
            "device_name" => "required|string|max:255",
        ]);

        $user = User::with(['activeSubscription', 'farms.city.state.country'])->where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;
        $user->device_token = $request->device_token;
        $user->save();

        return response()->success(["user" => $user, "token" => $token], null);
    }

    /**
     * Authenticated User Detail
     *
     * @return JsonResponse
     */
    public function user(): JsonResponse
    {
        $user = User::with(['farms.city.state.country'])->findOrFail(auth()->id());

        return response()->success($user, null);
    }
}
