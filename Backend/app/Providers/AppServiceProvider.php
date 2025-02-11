<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\http\Services\CryptocurrencyService;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind CryptocurrencyService to the container
        $this->app->singleton(CryptocurrencyService::class, function ($app) {
            return new CryptocurrencyService();
        });
    }

    public function boot()
    {
        //
    }
}
