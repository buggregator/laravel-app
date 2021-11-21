<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use Modules\Events\Domain\Event;
use Tests\DatabaseTestCase;

class SentryControllerTest extends DatabaseTestCase
{
    public function testEventShouldBeStored()
    {
        $stream = new \Http\Message\Encoding\GzipEncodeStream(
            \GuzzleHttp\Psr7\Utils::streamFor(json_encode(['foo' => 'bar']))
        );

        $this->call('POST', route('sentry.event.store', 1), content: (string) $stream);

        /** @var \Modules\Events\Domain\Event $event */
        $event = $this->getRepositoryFor(Event::class)->findAll()->first();

        $this->assertSame('sentry', $event->getType());
        $this->assertSame(['foo' => 'bar'], $event->getPayload()->toArray());

        return $event;
    }
}
