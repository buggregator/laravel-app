<?php

declare(strict_types=1);

namespace Infrastructure\EventSauce;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageDispatcher;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;

final class QueueMessageDispatcher implements MessageDispatcher
{
    private ?string $queue = null;

    public function __construct(
        private Dispatcher $dispatcher,
        private ConsumerLocator $consumers
    ) {
    }

    public function dispatch(Message ...$messages): void
    {
        foreach ($messages as $message) {
            foreach ($this->consumers->getConsumers($message) as $consumer) {
                if (is_a($consumer[0], ShouldQueue::class, true)) {
                    $dispatch = $this->dispatcher->dispatch(new HandleConsumer($consumer, $message));

                    if ($this->queue) {
                        $dispatch->onQueue($this->queue);
                    }
                } else {
                    $this->dispatcher->dispatchNow(new HandleConsumer($consumer, $message));
                }
            }
        }
    }

    public function onQueue(string $queue): self
    {
        $this->queue = $queue;

        return $this;
    }
}
