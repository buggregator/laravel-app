<?php

namespace App\Providers;

use App\Contracts\WebsocketClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(WebsocketClient::class, function ($app) {
            // If
            if ($app->bound(\Swoole\Http\Server::class)) {
                return $app->make(\App\Websocket\SwooleClient::class);
            }

            return new \App\Websocket\Client();
        });
    }
}
