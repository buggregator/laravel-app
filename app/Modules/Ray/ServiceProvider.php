<?php
declare(strict_types=1);

namespace Modules\Ray;

use Modules\Ray\Contracts\EventHandler as EventHandlerContract;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->bind(EventHandlerContract::class, function () {
            return new EventHandler($this->app, []);
        });
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'ray');
    }
}
