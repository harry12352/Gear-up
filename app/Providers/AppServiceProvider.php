<?php

namespace App\Providers;

use App\Services\Shipping\Usps\Client;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Registering USPS client.
        $this->app->singleton(Client::class, function ($app) {
            $uspsConfig = config('services.usps');
            return new Client($uspsConfig['host'], $uspsConfig['client_id']);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
