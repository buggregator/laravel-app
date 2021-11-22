<?php

declare(strict_types=1);

namespace Infrastructure\EventSauce;

use EventSauce\EventSourcing\MessageRepository;
use EventSauce\EventSourcing\Serialization\ConstructingMessageSerializer;
use EventSauce\EventSourcing\Serialization\MessageSerializer;
use Illuminate\Support\ServiceProvider;
use Infrastructure\EventSauce\Persistance\CycleOrmMessageRepository;

final class EventSauceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MessageSerializer::class, function () {
            return new ConstructingMessageSerializer();
        });

        $this->app->bind(
            MessageRepository::class,
            CycleOrmMessageRepository::class
        );
    }
}
