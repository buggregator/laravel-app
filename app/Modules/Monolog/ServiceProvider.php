<?php

declare(strict_types=1);

namespace Modules\Monolog;

use App\Contracts\TCP\Kernel;
use Modules\Monolog\Console\TcpHandler;

final class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(): void
    {
        $this->app[Kernel::class]->addHandler('monolog', TcpHandler::class);

        $this->loadViewsFrom(__DIR__.'/resources/views', 'monolog');
    }
}
