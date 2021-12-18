<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\UserSubscription;
use App\Http\Controllers\Controller;
use App\Payfast\Contracts\PaymentProcessor;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends Controller
{

    public function index()
    {
        try {
            $subscriptions = Subscription::all();

            return response()->json([
                'code' => 200,
                'message' => null,
                'data' => $subscriptions
            ]);
        } catch (\Exception $exception){
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(PaymentProcessor $payfast, $id)
    {
        $subscription = Subscription::findOrFail($id);

        $userSubscription = UserSubscription::create([
            'user_id' => auth()->id(),
            'subscription_id' => $subscription->id
        ]);

        // Build up payment Paramaters.
        $payfast->setBuyer(auth()->user()->first_name, auth()->user()->last_name, auth()->user()->email);
        $payfast->setAmount($subscription->amount);
        $payfast->setItem($subscription->title, $subscription->description);
        $payfast->setMerchantReference($userSubscription->id);

        // Optionally send confirmation email to seller
        // $payfast->setEmailConfirmation();
        // $payfast->setConfirmationAddress(env('PAYFAST_CONFIRMATION_EMAIL'));

        // Optionally make this a subscription
        $payfast->setSubscriptionType();    // will default to 1
        $payfast->setFrequency();           // will default to 3 = monthly if not set
        $payfast->setCycles();              // will default to 0 = indefinite if not set

        $payfast->setCancelUrl(route('subscription.cancel', ['sub_id' => $userSubscription->id]));
        $payfast->setReturnUrl(route('subscription.success', ['sub_id' => $userSubscription->id]));

        // Return the payment form.
        $paymentForm = $payfast->paymentForm('Redirecting...');

        return response()->json([
            'code' => 200,
            'message' => null,
            'data' => view('payment.index', compact('paymentForm'))->render()
        ]);
    }
}
