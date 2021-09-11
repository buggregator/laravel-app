<?php

namespace App\Providers;

use App\Contracts\WebsocketClient;
use App\Queue\RoadRunnerConnector;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['queue']->extend('roadrunner', fn() => new RoadRunnerConnector);
    }
}
