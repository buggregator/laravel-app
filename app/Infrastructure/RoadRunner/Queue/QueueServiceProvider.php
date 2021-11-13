<?php
declare(strict_types=1);

namespace Infrastructure\RoadRunner\Queue;

use Illuminate\Support\ServiceProvider;

class QueueServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['queue']->extend('roadrunner', fn() => new RoadRunnerConnector);
    }
}
