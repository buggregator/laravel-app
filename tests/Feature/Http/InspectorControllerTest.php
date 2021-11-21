<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

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
}
