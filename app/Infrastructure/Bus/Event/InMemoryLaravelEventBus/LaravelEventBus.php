<?php

declare(strict_types=1);

namespace Infrastructure\Bus\Event\InMemoryLaravelEventBus;

use App\Contracts\Event\EventBus;
use Illuminate\Contracts\Events\Dispatcher;

final class LaravelEventBus implements EventBus
{
    public function __construct(private Dispatcher $bus)
    {
    }

    public function publish(object ...$events): void
    {
        foreach ($events as $event) {
            $this->bus->dispatch($event);
        }
    }
}
