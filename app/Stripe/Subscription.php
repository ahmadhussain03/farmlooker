<?php

namespace App\Stripe;

use App\Models\Plan;
use Laravel\Cashier\Subscription as CashierSubscription;

class Subscription extends CashierSubscription
{
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'stripe_price', 'stripe_id');
    }
}
