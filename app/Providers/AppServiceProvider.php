<?php

namespace App\Providers;

use App\Stripe\Subscription;
use Laravel\Cashier\Cashier;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Stevebauman\Location\Facades\Location;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
         if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function ($data = null, $message = null, $code = 200) {
            return response()->json(['data' => $data, 'message' => $message, 'code' => $code], $code);
        });

        Response::macro('error', function($data = null, $message = null, $code = 500){
            return response()->json(['data' => $data, 'message' => $message, 'code' => $code], $code);
        });

        Cashier::useSubscriptionModel(Subscription::class);
    }
}
