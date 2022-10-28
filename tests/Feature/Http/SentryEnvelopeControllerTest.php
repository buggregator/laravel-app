<?php

namespace Tests\Feature\Http;

use Modules\Events\Domain\Event;
use Tests\DatabaseTestCase;

class SentryEnvelopeControllerTest extends DatabaseTestCase
{
    public function testEventShouldBeStored(): Event
    {
        $data = json_encode(['event_id' => '2136ca5a35954125bc977d64f552d679']);
        $data .= "\n".json_encode(['type' => 'transaction', 'content_type' => 'application/json']);
        $data .= "\n".json_encode(['transaction' => 'bar']);

        $stream = new \Http\Message\Encoding\GzipEncodeStream(
            \GuzzleHttp\Psr7\Utils::streamFor($data)
        );

        $this->call('POST', route('sentry.event.envelope', 1), content: (string) $stream);

        /** @var \Modules\Events\Domain\Event $event */
        $event = $this->getRepositoryFor(Event::class)->findAll()->first();

        $this->assertNotNull($event);
        $this->assertSame('sentryTransaction', $event->getType());
        $this->assertSame(['transaction' => 'bar'], $event->getPayload()->toArray());

        return $event;
    }
}
