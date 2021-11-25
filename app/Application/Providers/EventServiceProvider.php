<?php

declare(strict_types=1);

namespace App\Providers;

use App\Attributes\EventBus\Listener;
use App\Attributes\Locator;
use App\Events\Websocket\Joined;
use Generator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use ReflectionMethod;
use ReflectionUnionType;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            [SendEmailVerificationNotification::class, 'handle'],
        ],
        Joined::class => [
            //SendEventsAfterChannelJoin::class
        ],
    ];

    public function getEvents()
    {
        if ($this->app->eventsAreCached()) {
            $cache = require $this->app->getCachedEventsPath();

            return $cache[get_class($this)] ?? [];
        } else {
            return array_merge_recursive(
                $this->discoveredEvents(),
                $this->discoveredEventsThroughAttributes(),
                $this->listens()
            );
        }
    }

    private function discoveredEventsThroughAttributes(): array
    {
        $listen = [];

        $locator = new Locator($this->app);

        foreach ($locator->findClassMethodsAttributes('app', Listener::class) as $method => $attributes) {
            foreach ($this->processListenerAttributes($method) as $event => $listener) {
                $listen[$event][] = $listener;
            }
        }

        return $listen;
    }

    private function processListenerAttributes(ReflectionMethod $method): Generator
    {
        foreach ($method->getParameters() as $parameter) {
            if (! $parameter->hasType()) {
                continue;
            }

            $type = $parameter->getType();

            if ($type instanceof ReflectionUnionType) {
                foreach ($type->getTypes() as $t) {
                    if (class_exists($t->getName())) {
                        yield $t->getName() => [$method->getDeclaringClass()->getName(), $method->getName()];
                    }
                }
            } elseif (class_exists($type->getName())) {
                yield $type->getName() => [$method->getDeclaringClass()->getName(), $method->getName()];
            }
        }
    }
}
