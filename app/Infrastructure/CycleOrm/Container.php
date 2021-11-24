<?php

declare(strict_types=1);

namespace Infrastructure\CycleOrm;

use Spiral\Core\FactoryInterface;

class Container implements FactoryInterface
{
    public function __construct(
        private \Illuminate\Contracts\Container\Container $container
    ) {
    }

    public function make(string $alias, array $parameters = [])
    {
        return $this->container->make($alias, $parameters);
    }
}
