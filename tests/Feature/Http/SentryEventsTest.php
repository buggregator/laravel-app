<?php
declare(strict_types=1);

namespace Http;

use Inertia\Inertia;
use Modules\Events\Domain\Event;
use Tests\DatabaseTestCase;

class SentryEventsTest extends DatabaseTestCase
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

    public function testEventShouldBeQueried()
    {
        $event = $this->testEventShouldBeStored();

        $response = $this->getJson(
            route('sentry'),
            ['X-Inertia' => true, 'X-Inertia-Version' => Inertia::getVersion()]
        );

        $response->assertJsonFragment([
            'events' => [
                'data' => [
                    [
                        'uuid' => $event->getUuid()->toString(),
                        'type' => 'sentry',
                        'payload' => ['foo' => 'bar'],
                        'timestamp' => $event->getDate()->getTimestamp()
                    ]
                ]
            ]
        ]);
    }

    public function testEventShouldBeDeleted()
    {
        $event = $this->testEventShouldBeStored();

        $response = $this->deleteJson(
            route('sentry.delete', $event->getUuid()->toString()),
            ['X-Inertia' => true, 'X-Inertia-Version' => Inertia::getVersion()]
        );

        $this->assertNull($this->getRepositoryFor(Event::class)->findByPK($event->getUuid()->toString()));
    }
}
