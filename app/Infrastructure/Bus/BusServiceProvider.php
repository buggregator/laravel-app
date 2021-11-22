<?php

declare(strict_types=1);

namespace Infrastructure\Bus;

use App\Attributes\Locator;
use App\Contracts\Command\CommandBus;
use App\Contracts\Event\EventBus;
use App\Contracts\Query\QueryBus;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Infrastructure\Bus\Command\MessengerCommandBus;
use Infrastructure\Bus\Event\InMemoryLaravelEventBus\LaravelEventBus;
use Infrastructure\Bus\Query\MessengerQueryBus;
use Symfony\Component\Messenger\Handler\HandlersLocatorInterface;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

final class BusServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerBusHandlers();
    }

    public function boot(): void
    {
        $this->registerCommandBus();
        $this->registerEventBus();
        $this->registerQueryBus();
    }

    public function registerBusHandlers(): void
    {
        $this->app->singleton(HandlersLocatorInterface::class, function ($app) {
            return new HandlersLocator($app, new Locator($app));
        });
    }

    private function registerCommandBus(): void
    {
        $this->app->singleton(CommandBus::class, function ($app) {
            return new MessengerCommandBus(
                new MessageBus([
                    new HandleMessageMiddleware(
                        $app[HandlersLocatorInterface::class]
                    ),
                ])
            );
        });
    }

    private function registerEventBus(): void
    {
        $this->app->singleton(EventBus::class, function ($app) {
            return new LaravelEventBus(
                $app[Dispatcher::class]
            );
        });
    }

    private function registerQueryBus(): void
    {
        $this->app->singleton(QueryBus::class, function ($app) {
            return new MessengerQueryBus(
                new MessageBus([
                    new HandleMessageMiddleware(
                        $app[HandlersLocatorInterface::class]
                    ),
                ])
            );
        });
    }
}
