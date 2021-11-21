<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use App\Domain\ValueObjects\Uuid;
use Inertia\Inertia;
use Tests\DatabaseTestCase;

class EventsControllerTest extends DatabaseTestCase
{
    public function testGetListOfEvents()
    {
        $event1 = $this->createEvent('foo', ['foo' => 'bar']);
        $event2 = $this->createEvent('bar', ['foo1' => 'bar1']);

        $this->getJson(route('events'), ['X-Inertia' => true, 'X-Inertia-Version' => Inertia::getVersion()])
            ->assertJsonFragment([
                'events' => [
                    'data' => [
                        [
                            'uuid' => $event1->getUuid()->toString(),
                            'type' => $event1->getType(),
                            'payload' => $event1->getPayload()->toArray(),
                            'timestamp' => $event1->getDate()->getTimestamp(),
                        ],
                        [
                            'uuid' => $event2->getUuid()->toString(),
                            'type' => $event2->getType(),
                            'payload' => $event2->getPayload()->toArray(),
                            'timestamp' => $event2->getDate()->getTimestamp(),
                        ],
                    ],
                ],
            ])->assertJsonCount(2, 'props.events.data');
    }

    public function testGetSpecificTypeListOfEventsForUnknownTypeEventShouldReturnNotFound()
    {
        $this->getJson(route('events.type', 'foo'),
            ['X-Inertia' => true, 'X-Inertia-Version' => Inertia::getVersion()]
        )->assertForbidden()->assertJsonFragment(['message' => 'Action for event type [foo] not found.']);
    }

    public function testGetSpecificTypeListOfEvents()
    {
        config()->set('server.foo.http.index', 'Foo/Index');
        $event1 = $this->createEvent('foo', ['foo' => 'bar']);
        $this->createEvent('bar', ['foo1' => 'bar1']);
        $this->createEvent('baz', ['foo1' => 'bar1']);

        $this->getJson(
            route('events.type', $event1->getType()),
            ['X-Inertia' => true, 'X-Inertia-Version' => Inertia::getVersion()]
        )
            ->assertJsonFragment([
                'component' => 'Foo/Index',
            ])
            ->assertJsonFragment([
                'events' => [
                    'data' => [
                        [
                            'uuid' => $event1->getUuid()->toString(),
                            'type' => $event1->getType(),
                            'payload' => $event1->getPayload()->toArray(),
                            'timestamp' => $event1->getDate()->getTimestamp(),
                        ],
                    ],
                ],
            ])->assertJsonCount(1, 'props.events.data');
    }

    public function testDeleteAllEvents()
    {
        $this->createEvent('foo', ['foo' => 'bar']);
        $this->createEvent('bar', ['foo1' => 'bar1']);
        $this->createEvent('baz', ['foo1' => 'bar1']);

        $this->deleteJson(route('events.clear'));

        $this->getJson(
            route('events'),
            ['X-Inertia' => true, 'X-Inertia-Version' => Inertia::getVersion()]
        )->assertJsonCount(0, 'props.events.data');
    }

    public function testDeleteSpecificTypeEvents()
    {
        $this->createEvent('foo', ['foo' => 'bar']);
        $this->createEvent('bar', ['foo1' => 'bar1']);
        $this->createEvent('baz', ['foo1' => 'bar2']);

        $this->deleteJson(route('events.clear'), ['type' => 'foo']);

        $this->getJson(
            route('events'),
            ['X-Inertia' => true, 'X-Inertia-Version' => Inertia::getVersion()]
        )->assertJsonCount(2, 'props.events.data');
    }

    public function testDeleteEventByUuid()
    {
        $this->createEvent('foo', ['foo' => 'bar']);
        $event = $this->createEvent('bar', ['foo1' => 'bar1']);
        $this->createEvent('baz', ['foo1' => 'bar2']);

        $this->deleteJson(route('event.delete', $event->getUuid()->toString()));

        $this->getJson(
            route('events'),
            ['X-Inertia' => true, 'X-Inertia-Version' => Inertia::getVersion()]
        )->assertJsonCount(2, 'props.events.data');
    }

    public function testGetEventJsonByUuid()
    {
        $event = $this->createEvent('bar', ['foo1' => 'bar1']);
        $this->getJson(route('event.show.json', $event->getUuid()->toString()))
            ->assertJson([
                'data' => [
                    'uuid' => $event->getUuid()->toString(),
                    'type' => $event->getType(),
                    'payload' => $event->getPayload()->toArray(),
                    'timestamp' => $event->getDate()->getTimestamp(),
                ],
            ]);
    }

    public function testGetEventJsonShouldReturn404IfNotFound()
    {
        $this->getJson(route('event.show.json', Uuid::generate()->toString()))
            ->assertNotFound();
    }

    public function testGetEventByUuid()
    {
        config()->set('server.foo.http.show', 'Foo/Show');
        $event = $this->createEvent('foo', ['foo1' => 'bar1']);
        $this->getJson(
            route('event.show', [$event->getType(), $event->getUuid()->toString()]),
            ['X-Inertia' => true, 'X-Inertia-Version' => Inertia::getVersion()]
        )
            ->assertJsonFragment([
                'component' => 'Foo/Show',
            ])
            ->assertJsonFragment([
                'event' => [
                    'data' => [
                        'uuid' => $event->getUuid()->toString(),
                        'type' => $event->getType(),
                        'payload' => $event->getPayload()->toArray(),
                        'timestamp' => $event->getDate()->getTimestamp(),
                    ],
                ],
            ]);
    }

    public function testGetEventShouldReturn404IfNotFound()
    {
        config()->set('server.foo.http.show', 'Foo/Show');
        $this->getJson(
            route('event.show', ['foo', Uuid::generate()->toString()]),
            ['X-Inertia' => true, 'X-Inertia-Version' => Inertia::getVersion()]
        )
            ->assertNotFound();
    }

    public function testGetEventShouldReturn404IfWrongType()
    {
        $this->getJson(
            route('event.show', ['foo', Uuid::generate()->toString()]),
            ['X-Inertia' => true, 'X-Inertia-Version' => Inertia::getVersion()]
        )
            ->assertForbidden();
    }
}
