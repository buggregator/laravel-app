<?php
declare(strict_types=1);

namespace Tests\Feature\Http;

use Modules\Events\Domain\Event;
use Tests\DatabaseTestCase;

class ListSentryEvents extends DatabaseTestCase
{
    public function testEventShouldBeQueried()
    {
        $stream = new \Http\Message\Encoding\GzipEncodeStream(
            \GuzzleHttp\Psr7\Utils::streamFor(json_encode(['foo' => 'bar']))
        );


        $this->call('POST', route('sentry.event.store', 1), content: (string) $stream);

        $this->get(route('sentry'))
            ->dd();

        /** @var \Modules\Events\Domain\Event $event */
        $event = $this->getRepositoryFor(Event::class)->findAll()->first();

        $this->assertSame('ray', $event->getEvent());
        $this->assertSame($payload, $event->getPayload()->toArray());
    }
}
