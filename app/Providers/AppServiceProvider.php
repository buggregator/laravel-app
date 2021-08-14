<?php

namespace App\Providers;

use App\Ray;
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
            return new Ray\EventHandler($this->app, config('ray.event_handlers'));
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
