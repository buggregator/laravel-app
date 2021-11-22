<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\IncommingEvent\Domain;

use App\Domain\ValueObjects\Uuid;
use Modules\IncommingEvents\Domain\EventProcess;
use Modules\IncommingEvents\Persistance\EventProcessAggregateRootRepository;
use Tests\DatabaseTestCase;

class EventProcessTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! $this->app->has(EventProcessAggregateRootRepository::class)) {
            $this->markTestSkipped('EventSauce is disabled.');
        }
    }

    public function testFireReceivedEvent()
    {
        $repository = $this->app[EventProcessAggregateRootRepository::class];

        $processedEvent = EventProcess::received(
            uuid: $uuid = Uuid::generate(),
            type: 'test',
            payload: ['foo' => 'bar'],
            timestamp: 12345
        );

        $repository->persist($processedEvent);

        $event = $repository->retrieve($uuid);

        $this->assertSame($uuid->toString(), $event->aggregateRootId()->toString());
        $this->assertSame('test', $event->type());
        $this->assertSame(['foo' => 'bar'], $event->payload());
        $this->assertFalse($event->isDeleted());
        $this->assertSame(12345, $event->date()->getTimestamp());
    }

    public function testFireDeletedEvent()
    {
        $repository = $this->app[EventProcessAggregateRootRepository::class];

        $processedEvent = EventProcess::received(
            uuid: $uuid = Uuid::generate(),
            type: 'test',
            payload: ['foo' => 'bar'],
            timestamp: 12345
        );

        $repository->persist($processedEvent);

        $event = $repository->retrieve($uuid);

        $event->delete();

        $this->assertTrue($event->isDeleted());
    }
}
