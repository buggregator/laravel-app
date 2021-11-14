<?php
declare(strict_types=1);

namespace Tests\Feature\Modules\IncommingEvent\Domain;

use App\Domain\ValueObjects\Uuid;
use Modules\IncommingEvent\Persistance\EventProcessAggregateRootRepository;
use Tests\DatabaseTestCase;

class EventProcessTest extends DatabaseTestCase
{
    public function testFireReceivedEvent()
    {
        $repository = $this->app[EventProcessAggregateRootRepository::class];

        $processedEvent = EventProcess::received(
            $uuid = Uuid::generate(), 'test', ['foo' => 'bar'], 12345
        );

        $repository->persist($processedEvent);

        $event = $repository->retrieve($uuid);

        $this->assertSame($uuid->toString(), $event->aggregateRootId()->toString());
        $this->assertSame('test', $event->type());
        $this->assertSame(['foo' => 'bar'], $event->payload());
    }
}
