<?php

declare(strict_types=1);

namespace Infrastructure\EventSauce;

use App\Attributes\Locator;
use App\Attributes\Projectors\Projector;
use App\Contracts\EventSource\Event;
use EventSauce\EventSourcing\Message;
use Generator;
use ReflectionClass;

final class ConsumerLocator
{
    private array $consumers = [];

    public function __construct(private Locator $attributesLocator)
    {
        foreach ($attributesLocator->findClassAttributes('app', Projector::class) as $class => $attributes) {
            $this->processProjectors($class);
        }
    }

    public function getConsumers(Message $message): Generator
    {
        $event = $message->event();

        foreach ($this->consumers as $eventClass => $consumers) {
            if ($event instanceof $eventClass) {
                foreach ($consumers as $consumer) {
                    yield $consumer;
                }
            }
        }
    }

    private function processProjectors(ReflectionClass $class)
    {
        foreach ($class->getMethods() as $method) {
            foreach ($method->getParameters() as $parameter) {
                if (is_a($parameter->getType()->getName(), Event::class, true)) {
                    $this->consumers[$parameter->getType()->getName()][] = [
                        $method->getDeclaringClass()->getName(),
                        $method->getName(),
                    ];
                }
            }
        }
    }
}
