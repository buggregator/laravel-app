<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\IncommingEvent\Domain\Events;

use App\Domain\ValueObjects\Uuid;
use Modules\IncommingEvents\Domain\Events\EventWasReceived;
use Tests\TestCase;

class EventWasReceivedTest extends TestCase
{
    public function testConvertsEventToArray()
    {
        $event = new EventWasReceived(
            $uuid = Uuid::generate(),
            'test',
            ['foo' => 'bar'],
            12345
        );

        $this->assertSame([
            'uuid' => $uuid->toString(),
            'type' => 'test',
            'payload' => ['foo' => 'bar'],
            'timestamp' => 12345,
        ], $event->toPayload());
    }

    public function testConvertsArrayToEvent()
    {
        $event = EventWasReceived::fromPayload([
            'uuid' => $uuid = Uuid::generate()->toString(),
            'type' => 'test',
            'payload' => ['foo' => 'bar'],
            'timestamp' => 12345,
        ]);

        $this->assertSame($uuid, $event->uuid->toString());
        $this->assertSame('test', $event->type);
        $this->assertSame(['foo' => 'bar'], $event->payload);
        $this->assertSame(12345, $event->timestamp);
    }
}
