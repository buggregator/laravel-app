<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use App\Domain\ValueObjects\Uuid;
use Modules\Project\Domain\Project;
use Tests\DatabaseTestCase;

class EventsControllerTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('auth.enabled', false);
    }

    public function testGetSentryTransactionsEventsCountSameTransaction()
    {
        $transactionId = $this->findOrCreateTransaction('bar');
        $this->createEvent('sentryTransaction', ['transaction' => 'bar'], $transactionId);
        $this->createEvent('sentryTransaction', ['transaction' => 'bar'], $transactionId);

        $this->getJson(route('events.transactions', [$transactionId, 1]), ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()])
            ->assertJsonFragment([
                'eventsCount' => 2,
                'type' => 'sentryTransaction',
                'projectId' => 1,
                'transactionId' => $transactionId,
            ]);
    }

    public function testGetSentryTransactionsEventsCountDifferentTransactions()
    {
        $transactionId1 = $this->findOrCreateTransaction('bar1');
        $transactionId2 = $this->findOrCreateTransaction('bar2');
        $this->createEvent('sentryTransaction', ['transaction' => 'bar1'], $transactionId1);
        $this->createEvent('sentryTransaction', ['transaction' => 'bar2'], $transactionId2);

        $this->getJson(route('events.transactions', [$transactionId1, 1]), ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()])
            ->assertJsonFragment([
                'eventsCount' => 1,
                'type' => 'sentryTransaction',
                'projectId' => 1,
                'transactionId' => $transactionId1,
            ]);

        $this->getJson(route('events.transactions', [$transactionId2, 1]), ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()])
            ->assertJsonFragment([
                'eventsCount' => 1,
                'type' => 'sentryTransaction',
                'projectId' => 1,
                'transactionId' => $transactionId2,
            ]);
    }

    public function testGetListOfEvents()
    {
        $event1 = $this->createEvent('foo', ['foo' => 'bar']);
        $event2 = $this->createEvent('bar', ['foo1' => 'bar1']);

        $this->getJson(route('events'), ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()])
            ->assertJsonFragment([
                'events' => [
                    'data' => [
                        [
                            'uuid' => $event1->getUuid()->toString(),
                            'type' => $event1->getType(),
                            'projectId' => $event1->getProjectId(),
                            'transactionId' => $event1->getTransactionId(),
                            'payload' => $event1->getPayload()->toArray(),
                            'timestamp' => $event1->getDate()->getTimestamp(),
                        ],
                        [
                            'uuid' => $event2->getUuid()->toString(),
                            'type' => $event2->getType(),
                            'projectId' => $event2->getProjectId(),
                            'transactionId' => $event2->getTransactionId(),
                            'payload' => $event2->getPayload()->toArray(),
                            'timestamp' => $event2->getDate()->getTimestamp(),
                        ],
                    ],
                ],
            ])->assertJsonCount(2, 'props.events.data');
    }

    public function testGetSentryTransactionsEventsCountForUnknownProject()
    {
        $transactionId1 = $this->findOrCreateTransaction('bar1');
        $this->createEvent('sentryTransaction', ['transaction' => 'bar1'], $transactionId1);
        $project = new Project(null, 'f16ec4b4aedaaa8422094fb1808542af');
        $this->persistEntity($project);

        $this->getJson(route('events.transactions', [$transactionId1, $project->getId()]), ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()])
            ->assertJsonFragment([
                'eventsCount' => 0,
                'type' => 'sentryTransaction',
                'projectId' => (int) $project->getId(),
                'transactionId' => $transactionId1,
            ]);
    }

    public function testGetSentryTransactionsEventsCountForUnknownTransaction()
    {
        $transactionId1 = $this->findOrCreateTransaction('f16ec4b4aedaaa8422094fb1808542af');
        $this->getJson(route('events.transactions', [$transactionId1, 1]), ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()])
            ->assertJsonFragment([
                'eventsCount' => 0,
                'type' => 'sentryTransaction',
                'projectId' => 1,
                'transactionId' => $transactionId1,
            ]);
    }

    public function testGetSpecificTypeListOfEventsForUnknownTypeEventShouldReturnNotFound()
    {
        $this->getJson(route('events.type', 'foo'),
            ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()]
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
            ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()]
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
                            'projectId' => $event1->getProjectId(),
                            'transactionId' => $event1->getTransactionId(),
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

        $this->cleanIdentityMap();

        $this->getJson(
            route('events'),
            ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()]
        )->assertJsonCount(0, 'props.events.data');
    }

    public function testDeleteAllEventsPreservingSentryTransactions()
    {
        $this->createEvent('foo', ['foo' => 'bar']);
        $this->createEvent('bar', ['foo1' => 'bar1']);
        $this->createEvent('baz', ['foo1' => 'bar1']);

        $transactionId = $this->findOrCreateTransaction('bar1');
        $this->createEvent('sentryTransaction', ['transaction' => 'bar1'], $transactionId);

        $this->deleteJson(route('events.clear'));

        $this->cleanIdentityMap();

        $this->getJson(
            route('events'),
            ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()]
        )->assertJsonCount(0, 'props.events.data');

        $this->getJson(route('events.transactions', [$transactionId, 1]), ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()])
            ->assertJsonFragment([
                'eventsCount' => 1,
                'type' => 'sentryTransaction',
                'projectId' => 1,
                'transactionId' => $transactionId,
            ]);
    }

    public function testDeleteSpecificTypeEvents()
    {
        $this->createEvent('foo', ['foo' => 'bar']);
        $this->createEvent('bar', ['foo1' => 'bar1']);
        $this->createEvent('baz', ['foo1' => 'bar2']);

        $this->deleteJson(route('events.clear'), ['type' => 'foo']);

        $this->getJson(
            route('events'),
            ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()]
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
            ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()]
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
                    'projectId' => $event->getProjectId(),
                    'transactionId' => $event->getTransactionId(),
                    'payload' => $event->getPayload()->toArray(),
                    'timestamp' => $event->getDate()->getTimestamp(),
                ],
            ]);
    }

    public function testGetEventJsonTransactionByUuid()
    {
        $transactionId = $this->findOrCreateTransaction('bar1');
        $event = $this->createEvent('sentryTransaction', ['transaction' => 'bar1'], $transactionId);
        $this->getJson(route('event.show.json', $event->getUuid()->toString()))
            ->assertJson([
                'data' => [
                    'uuid' => $event->getUuid()->toString(),
                    'type' => $event->getType(),
                    'projectId' => $event->getProjectId(),
                    'transactionId' => $event->getTransactionId(),
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
            ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()]
        )
            ->assertJsonFragment([
                'component' => 'Foo/Show',
            ])
            ->assertJsonFragment([
                'event' => [
                    'data' => [
                        'uuid' => $event->getUuid()->toString(),
                        'type' => $event->getType(),
                        'projectId' => $event->getProjectId(),
                        'transactionId' => $event->getTransactionId(),
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
            ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()]
        )
            ->assertNotFound();
    }

    public function testGetEventShouldReturn404IfWrongType()
    {
        $this->getJson(
            route('event.show', ['foo', Uuid::generate()->toString()]),
            ['X-Inertia' => true, 'X-Inertia-Version' => $this->inertiaVersion()]
        )
            ->assertForbidden();
    }
}
