<?php

namespace App\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\StreamHandler;
use GuzzleHttp\HandlerStack;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bypass broken PHP cURL extension by forcing StreamHandler
        $this->app->bind(ClientInterface::class, function () {
            return new Client([
                'handler' => HandlerStack::create(
                    new StreamHandler
                ),
            ]);
        });

        $this->app->bind(Client::class, function () {
            return new Client([
                'handler' => HandlerStack::create(
                    new StreamHandler
                ),
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
