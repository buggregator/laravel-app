<?php

declare(strict_types=1);

namespace Modules\Smtp;

use App\Contracts\TCP\Kernel;
use Modules\Smtp\Console\TcpHandler;

final class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(): void
    {
        $this->app[Kernel::class]->addHandler('smtp', TcpHandler::class);

        $this->loadViewsFrom(__DIR__.'/resources/views', 'smtp');
    }
}
