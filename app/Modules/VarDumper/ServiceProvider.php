<?php

declare(strict_types=1);

namespace Modules\VarDumper;

use App\Contracts\TCP\Kernel;
use Modules\VarDumper\Console\TcpHandler;

final class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(): void
    {
        $this->app[Kernel::class]->addHandler('var-dumper', TcpHandler::class);
    }
}
