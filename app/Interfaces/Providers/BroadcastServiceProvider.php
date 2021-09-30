<?php
declare(strict_types=1);

namespace Interfaces\Providers;

use App\Websocket\RoadRunnerBroadcaster;
use App\Websocket\RoadrunnerClient;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot()
    {
       $this->app[BroadcastManager::class]
           ->extend('roadrunner', fn (Application $app, array $config) => new RoadRunnerBroadcaster(
               new RoadrunnerClient($config['rpc_host'])
           ));


        require app_path('Interfaces/Websocket/channels.php');
    }
}
