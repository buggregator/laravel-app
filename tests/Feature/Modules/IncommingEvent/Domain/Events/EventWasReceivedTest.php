<?php
declare(strict_types=1);

namespace Tests\Feature\Modules\IncommingEvent\Domain\Events;

use App\Domain\ValueObjects\Uuid;
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
            'uuid' => $uuid,
            'type' => 'test',
            'payload' => ['foo' => 'bar'],
            'timestamp' => 12345
        ], $event->toPayload());
    }

    public function testConvertsArrayToEvent()
    {
        $event = EventWasReceived::fromPayload([
            'uuid' => $uuid = Uuid::generate(),
            'type' => 'test',
            'payload' => ['foo' => 'bar'],
            'timestamp' => 12345
        ]);

        $this->assertSame($uuid, $event->uuid);
        $this->assertSame('test', $event->type);
        $this->assertSame(['foo' => 'bar'], $event->payload);
        $this->assertSame(12345, $event->timestamp);
    }
}
