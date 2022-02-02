<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\UserSubscription;
use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{

    public function index()
    {
        //
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'payment_method' => 'required|string'
        ]);

        $plan = Plan::findOrFail($id);
        /** @var App\Models\User */
        $user = auth()->user();

        $user->newSubscription(
            'default', $plan->stripe_id
        )->create($request->payment_method);

        $user->load(['farms.city.state.country', 'subscriptions' => function($query){
            $query->active();
        }, 'subscriptions.plan']);

        return response()->success(['user' => $user], 'Successfully Subscribe to ' . $plan->name . '!');
    }

    public function destroy()
    {
        /** @var App\Models\User */
        $user = auth()->user();

        if ($user->subscription('default')->onGracePeriod()) {
            $user->subscription('default')->cancelNow();
        } else {
            $user->subscription('default')->cancel();
        }


        $user->load(['farms.city.state.country', 'subscriptions' => function($query){
            $query->active();
        }, 'subscriptions.plan']);

        return response()->success(['user' => $user]);
    }

    public function resume()
    {
        /** @var App\Models\User */
        $user = auth()->user();

        $user->subscription('default')->resume();

        $user->load(['farms.city.state.country', 'subscriptions' => function($query){
            $query->active();
        }, 'subscriptions.plan']);

        return response()->success(['user' => $user]);
    }

    public function billing()
    {
        /** @var App\Models\User */
        $user = auth()->user();

        return response()->success(['url' => $user->billingPortalUrl(route('spa'))]);
    }
}
