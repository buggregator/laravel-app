<?php

declare(strict_types=1);

namespace Infrastructure\RoadRunner\Cache;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Spiral\Goridge\RPC\RPC;
use Spiral\RoadRunner\KeyValue\Factory;

final class CacheServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->booting(function () {
            Cache::extend('roadrunner', function ($app) {
                $factory = new Factory(RPC::create(config('roadrunner.rpc.host')));

                return Cache::repository(
                    new RoadRunnerStore(
                        $factory->select(config('roadrunner.cache.storage', 'cache'))
                    )
                );
            });
        });
    }
}
