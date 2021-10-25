<?php
declare(strict_types=1);

namespace Modules\Monolog;

use Modules\Monolog\Console\Commands\StartServerCommand;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->commands([
            StartServerCommand::class
        ]);
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'monolog');
    }
}
