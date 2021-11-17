<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use App\Domain\ValueObjects\Uuid;
use Inertia\Inertia;
use Modules\Events\Domain\Event;
use Tests\DatabaseTestCase;

class SmtpControllerTest extends DatabaseTestCase
{
    public function testDeleteActionShouldShow404IfEventNotFound()
    {
        $this->deleteJson(route('smtp.delete', Uuid::generate()))->assertNotFound();
    }

    public function testDeleteEventByUuid()
    {
        $event = $this->createEvent('bar', ['foo1' => 'bar1']);
        $this->deleteJson(route('smtp.delete', $event->getUuid()->toString()));
        $this->assertCount(0, $this->getRepositoryFor(Event::class)->findAll());
    }

    public function testGetListAction()
    {
        $event1 = $this->createEvent('smtp', ['foo' => 'bar']);

        $this->createEvent('bar', ['foo' => 'bar']);
        $this->createEvent('bar2', ['foo2' => 'bar2']);

        $this->getJson(route('smtp'), ['X-Inertia' => true, 'X-Inertia-Version' => Inertia::getVersion()])
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
        $event = $this->createEvent('smtp', ['foo1' => 'bar1']);
        $event1 = $this->createEvent('smtp', ['foo' => 'bar']);

        $this->getJson(
            route('smtp.show', $event->getUuid()->toString()),
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
        $this->getJson(route('smtp.show', Uuid::generate()->toString()))
            ->assertNotFound();
    }


    public function testGetEventHtmlByUuid()
    {
        $event = $this->createEvent('smtp', ['foo1' => 'bar1', 'data' => ['html' => '<h1>Hello world</h1>']]);

        $this->get(route('smtp.show.html', $event->getUuid()->toString()))
            ->assertOk()
            ->assertSeeText('Hello world');
    }


    public function testGetEventHtmlShouldReturn404IfNotFound()
    {
        $this->getJson(route('smtp.show.html', Uuid::generate()->toString()))
            ->assertNotFound();
    }
}
