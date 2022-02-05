<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
         /** @var App\Models\User */
         $user = auth()->user();

         $paymentMethods = $user->defaultPaymentMethod();

         return response()->success(['payment_methods' => $paymentMethods]);
    }
}
