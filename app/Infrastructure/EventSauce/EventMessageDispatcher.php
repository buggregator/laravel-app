<?php

declare(strict_types=1);

namespace Infrastructure\EventSauce;

use App\Contracts\Event\EventBus;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageDispatcher;

final class EventMessageDispatcher implements MessageDispatcher
{
    public function __construct(private EventBus $eventBus)
    {
    }

    public function dispatch(Message ...$messages): void
    {
        $this->eventBus->publish(...$messages);
    }
}
