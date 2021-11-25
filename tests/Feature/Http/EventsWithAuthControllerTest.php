<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use App\Domain\ValueObjects\Uuid;
use Tests\DatabaseTestCase;

class EventsWithAuthControllerTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('auth.enabled', true);
    }

    public function testGetListOfEvents()
    {
        $this->getJson(route('events'), ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()])
            ->assertUnauthorized();
    }

    public function testGetSpecificTypeListOfEventsForUnknownTypeEventShouldReturnNotFound()
    {
        $this->getJson(route('events.type', 'foo'),
            ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()]
        )->assertForbidden();
    }

    public function testGetSpecificTypeListOfEvents()
    {
        $event = $this->createEvent('test', []);
        $this->getJson(
            route('events.type', $event->getType()),
            ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()]
        )->assertForbidden();
    }

    public function testDeleteAllEvents()
    {
        $this->getJson(
            route('events'),
            ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()]
        )->assertUnauthorized();
    }

    public function testDeleteSpecificTypeEvents()
    {
        $this->getJson(
            route('events'),
            ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()]
        )->assertUnauthorized();
    }

    public function testDeleteEventByUuid()
    {
        $event = $this->createEvent('bar', ['foo1' => 'bar1']);
        $this->deleteJson(route('event.delete', $event->getUuid()->toString()))
            ->assertUnauthorized();
    }

    public function testGetEventJsonByUuid()
    {
        $event = $this->createEvent('bar', ['foo1' => 'bar1']);
        $this->getJson(route('event.show.json', $event->getUuid()->toString()))
            ->assertUnauthorized();
    }

    public function testGetEventJsonShouldReturn404IfNotFound()
    {
        $this->getJson(route('event.show.json', Uuid::generate()->toString()))
            ->assertUnauthorized();
    }

    public function testGetEventByUuid()
    {
        config()->set('server.foo.http.show', 'Foo/Show');
        $event = $this->createEvent('foo', ['foo1' => 'bar1']);
        $this->getJson(
            route('event.show', [$event->getType(), $event->getUuid()->toString()]),
            ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()]
        )->assertUnauthorized();
    }

    public function testGetEventShouldReturn404IfNotFound()
    {
        config()->set('server.foo.http.show', 'Foo/Show');
        $this->getJson(
            route('event.show', ['foo', Uuid::generate()->toString()]),
            ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()]
        )->assertUnauthorized();
    }

    public function testGetEventShouldReturn404IfWrongType()
    {
        $this->getJson(
            route('event.show', ['foo', Uuid::generate()->toString()]),
            ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()]
        )->assertUnauthorized();
    }
}
