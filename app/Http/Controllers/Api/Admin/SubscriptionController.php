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
        //
    }

    public function show($id)
    {

    }
}
