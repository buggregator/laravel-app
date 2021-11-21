<?php

declare(strict_types=1);

namespace Infrastructure\RoadRunner\Broadcast;

use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

final class BroadcastServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app[BroadcastManager::class]
            ->extend('roadrunner', fn (Application $app, array $config) => new RoadRunnerBroadcaster(
                new RoadrunnerClient($config['rpc_host'])
            ));

        require app_path('Interfaces/Websocket/channels.php');
    }
}
