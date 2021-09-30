<?php
declare(strict_types=1);

namespace App\Providers;

use App\Queue\RoadRunnerConnector;
use App\Session\RoadRunnerSessionHandler;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Spiral\Goridge\RPC\RPC;
use Spiral\RoadRunner\KeyValue\Factory;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['queue']->extend('roadrunner', fn() => new RoadRunnerConnector);

        CliDumper::$defaultOutput = 'php://stderr';

        $this->app['session']->extend('roadrunner', static function () {
            $factory = new Factory(RPC::create(config('roadrunner.rpc.host')));
            return new RoadRunnerSessionHandler(
                $factory->select(config('roadrunner.session.storage', 'session')),
                (int)config('session.lifetime')
            );
        });
    }
}
