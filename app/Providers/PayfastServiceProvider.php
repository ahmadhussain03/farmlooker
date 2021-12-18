<?php

namespace App\Providers;

use App\Payfast\Contracts\PaymentProcessor;
use App\Payfast\Payfast;
use Illuminate\Support\ServiceProvider;

class PayfastServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PaymentProcessor::class, Payfast::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
