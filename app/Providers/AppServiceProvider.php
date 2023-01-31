<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGatewayInterface;
use App\Services\PaymentGateway\Stripe;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PaymentGatewayInterface::class, Stripe::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Response::macro('caps', function ($value) {
            return Response::make(strtoupper($value));
        });

        Http::macro('stripe', function () {
            return Http::withToken(config('app.stripe_secret_key'))->baseUrl('https://api.stripe.com/v1')->asForm();
        });
    }
}
