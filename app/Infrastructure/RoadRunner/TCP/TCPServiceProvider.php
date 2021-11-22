<?php

declare(strict_types=1);

namespace Infrastructure\RoadRunner\TCP;

use App\Contracts\TCP\Kernel as KernelContract;
use Illuminate\Support\ServiceProvider;

final class TCPServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(KernelContract::class, Kernel::class);
    }
}
