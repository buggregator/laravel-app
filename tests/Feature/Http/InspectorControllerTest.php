<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use App\Domain\ValueObjects\Uuid;
use Inertia\Inertia;
use Modules\Events\Domain\Event;
use Tests\DatabaseTestCase;

class InspectorControllerTest extends DatabaseTestCase
{
    public function testEventShouldBeStored()
    {
        $content = base64_encode(json_encode(
            $payload = ['foo' => 'bar']
        ));

        $headers = [
            'CONTENT_LENGTH' => mb_strlen($content, '8bit'),
            'CONTENT_TYPE' => 'application/json',
            'Accept' => 'application/json',
        ];

        $this->call(
            'POST',
            route('inspector.event.store'),
            [],
            $this->prepareCookiesForJsonRequest(),
            [],
            $this->transformHeadersToServerVars($headers),
            $content
        );

        /** @var \Modules\Events\Domain\Event $event */
        $event = $this->getRepositoryFor(Event::class)->findAll()->first();

        $this->assertSame('inspector', $event->getType());
        $this->assertSame($payload, $event->getPayload()->toArray());
    }

    public function testDeleteActionShouldShow404IfEventNotFound()
    {
        $this->deleteJson(route('inspector.delete', Uuid::generate()))->assertNotFound();
    }

    public function testDeleteEventByUuid()
    {
        $event = $this->createEvent('bar', ['foo1' => 'bar1']);
        $this->deleteJson(route('inspector.delete', $event->getUuid()->toString()));
        $this->assertCount(0, $this->getRepositoryFor(Event::class)->findAll());
    }

    public function testGetListAction()
    {
        $event1 = $this->createEvent('inspector', ['foo' => 'bar']);

        $this->createEvent('bar', ['foo' => 'bar']);
        $this->createEvent('bar2', ['foo2' => 'bar2']);

        $this->getJson(route('inspector'), ['X-Inertia' => true, 'X-Inertia-Version' => Inertia::getVersion()])
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
        $event = $this->createEvent('inspector', ['foo1' => 'bar1']);
        $event1 = $this->createEvent('inspector', ['foo' => 'bar']);

        $this->getJson(
            route('inspector.show', $event->getUuid()->toString()),
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
        $this->getJson(route('inspector.show', Uuid::generate()->toString()))
            ->assertNotFound();
    }
}
