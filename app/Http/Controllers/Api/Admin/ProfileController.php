<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function update(Request $request)
    {

        /** @var App\Models\User $user */
        $user = auth()->user();

        $validatedData = $this->validate($request, [
            "email" => "email|max:255|unique:users,email," . $user->id,
            "password" => "confirmed|min:6|max:255",
            "first_name" => "string|max:255",
            "last_name" => "string|max:255",
            "phone_no" => "string|phone:AUTO,SA|max:20",
            "experience" => "string",
            "device_token" => "string|max:255",
            "device_name" => "string|max:255",
            "image" => 'sometimes|mimes:jpeg,jpg,png,bmp'
        ]);

        if(isset($validatedData['password'])){
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        if(isset($validatedData['image'])){
            $validatedData['image'] = $validatedData['image']->getClientOriginalName();
        }


        $user->update($validatedData);

        return response()->success($user);
    }
}
