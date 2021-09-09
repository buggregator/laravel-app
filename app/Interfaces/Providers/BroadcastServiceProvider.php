<?php

namespace Interfaces\Providers;

use App\Broadcasting\Broadcasters\SwooleBroadcaster;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot()
    {
       $this->app[BroadcastManager::class]->extend('swoole', fn (Application $app, array $config) => $app[SwooleBroadcaster::class]);
    }
}
