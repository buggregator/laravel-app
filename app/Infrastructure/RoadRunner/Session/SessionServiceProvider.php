<?php

declare(strict_types=1);

namespace Infrastructure\RoadRunner\Session;

use Illuminate\Support\ServiceProvider;
use Spiral\Goridge\RPC\RPC;
use Spiral\RoadRunner\KeyValue\Factory;

final class SessionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['session']->extend('roadrunner', static function () {
            $factory = new Factory(RPC::create(config('roadrunner.rpc.host')));

            return new RoadRunnerSessionHandler(
                $factory->select(config('roadrunner.session.storage', 'session')),
                (int) config('session.lifetime')
            );
        });
    }
}
