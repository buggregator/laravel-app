<?php
declare(strict_types=1);

namespace Tests\Feature\Http;

use App\Domain\ValueObjects\Uuid;
use Inertia\Inertia;
use Modules\Events\Domain\Event;
use Tests\DatabaseTestCase;

class SentryControllerTest extends DatabaseTestCase
{
    public function testEventShouldBeStored()
    {
        $stream = new \Http\Message\Encoding\GzipEncodeStream(
            \GuzzleHttp\Psr7\Utils::streamFor(json_encode(['foo' => 'bar']))
        );

        $this->call('POST', route('sentry.event.store', 1), content: (string)$stream);

        /** @var \Modules\Events\Domain\Event $event */
        $event = $this->getRepositoryFor(Event::class)->findAll()->first();

        $this->assertSame('sentry', $event->getType());
        $this->assertSame(['foo' => 'bar'], $event->getPayload()->toArray());

        return $event;
    }

    public function testDeleteActionShouldShow404IfEventNotFound()
    {
        $this->deleteJson(route('sentry.delete', Uuid::generate()))->assertNotFound();
    }

    public function testDeleteEventByUuid()
    {
        $event = $this->createEvent('bar', ['foo1' => 'bar1']);
        $this->deleteJson(route('sentry.delete', $event->getUuid()->toString()));
        $this->assertCount(0, $this->getRepositoryFor(Event::class)->findAll());
    }

    public function testGetListAction()
    {
        $event1 = $this->createEvent('sentry', ['foo' => 'bar']);

        $this->createEvent('bar', ['foo' => 'bar']);
        $this->createEvent('bar2', ['foo2' => 'bar2']);

        $this->getJson(route('sentry'), ['X-Inertia' => true, 'X-Inertia-Version' => Inertia::getVersion()])
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


    public function testGetEventByUuid()
    {
        $event = $this->createEvent('sentry', ['foo1' => 'bar1']);
        $event1 = $this->createEvent('sentry', ['foo' => 'bar']);

        $this->getJson(
            route('sentry.show', $event->getUuid()->toString()),
            ['X-Inertia' => true, 'X-Inertia-Version' => Inertia::getVersion()]
        )->assertJsonFragment([
            'event' => [
                'data' => [
                    'uuid' => $event->getUuid()->toString(),
                    'type' => $event->getType(),
                    'payload' => $event->getPayload()->toArray(),
                    'timestamp' => $event->getDate()->getTimestamp(),
                ],
            ],
        ])->assertJsonFragment([
            'events' => [
                'data' => [
                    [
                        'uuid' => $event->getUuid()->toString(),
                        'type' => $event->getType(),
                        'payload' => $event->getPayload()->toArray(),
                        'timestamp' => $event->getDate()->getTimestamp(),
                    ],
                    [
                        'uuid' => $event1->getUuid()->toString(),
                        'type' => $event1->getType(),
                        'payload' => $event1->getPayload()->toArray(),
                        'timestamp' => $event1->getDate()->getTimestamp(),
                    ],
                ],
            ],
        ]);
    }

    public function testGetEventShouldReturn404IfNotFound()
    {
        $this->getJson(route('sentry.show', Uuid::generate()->toString()))
            ->assertNotFound();
    }
}
