<?php

declare(strict_types=1);

namespace Infrastructure\CycleOrm;

use Illuminate\Support\ServiceProvider;
use Spiral\Core\Container as SpiralContainer;
use Spiral\Core\FactoryInterface;

class ContainerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SpiralContainer::class, SpiralContainer::class);
        $this->app->alias(SpiralContainer::class, FactoryInterface::class);
    }
}
