<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class EmailVerificationController extends Controller
{
    public function resend()
    {
         /** @var App\Models\User */
         $currentUser = auth()->user();

         $currentUser->sendEmailVerificationNotification();

         return response()->success(null, 'Verification Code Sent!');
    }

    public function verify(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|alpha_num|size:6',
        ]);

        /** @var App\Models\User */
        $currentUser = auth()->user();
        $currentUser->load(['farms']);

        $verificationCodeExists = $currentUser->verificationCode()->where('code', $request->code)->first();

        if($verificationCodeExists){
            $currentUser->email_verified_at = now();
            $currentUser->save();

            $verificationCodeExists->delete();

            return response()->success($currentUser);
        } else {
            throw ValidationException::withMessages([
                'code' => ['The provided code is incorrect.'],
            ]);
        }
    }
}
