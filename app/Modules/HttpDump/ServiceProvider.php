<?php

declare(strict_types=1);

namespace Modules\HttpDump;

use Modules\HttpDump\Contracts\EventHandler as EventHandlerContract;

final class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->bind(EventHandlerContract::class, function () {
            return new EventHandler($this->app, []);
        });
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'httpdump');
    }
}
