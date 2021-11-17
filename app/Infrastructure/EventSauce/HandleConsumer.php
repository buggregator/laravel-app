<?php

declare(strict_types=1);

namespace Infrastructure\EventSauce;

use App\Contracts\EventSource\Projector;
use EventSauce\EventSourcing\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class HandleConsumer implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;

    public function __construct(private array $consumer, private Message $message)
    {
    }

    public function handle(Container $container): void
    {
        $consumer = $this->resolveConsumer($container);

        call_user_func([$consumer, $this->consumer[1]], $this->message->event());
    }

    private function resolveConsumer(Container $container): Projector
    {
        return $container->make($this->consumer[0]);
    }
}
