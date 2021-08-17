<?php

namespace App\Providers;

use App\Ray;
use App\Sentry;
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
        $this->app->bind(Ray\Contracts\EventHandler::class, function () {
            return new Ray\EventHandler($this->app, config('server.ray.event_handlers'));
        });


        $this->app->bind(Sentry\Contracts\EventHandler::class, function () {
            return new Sentry\EventHandler($this->app, config('server.sentry.event_handlers'));
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
