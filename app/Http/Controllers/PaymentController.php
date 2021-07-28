<?php

namespace App\Http\Controllers;

use App\Models\UserSubscription;
use App\Payfast\Contracts\PaymentProcessor;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function success(Request $request)
    {
        $userSubscription = UserSubscription::findOrFail($request->sub_id);
        $userSubscription->status = 'SUCCESSFULL';
        $userSubscription->save();

        return view('payment.success');
    }

    public function cancel(Request $request)
    {
        $userSubscription = UserSubscription::findOrFail($request->sub_id);
        $userSubscription->status = 'CANCELLED';
        $userSubscription->save();

        return view('payment.cancel');
    }
}
