<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use Modules\Events\Domain\Event;
use Tests\DatabaseTestCase;

class HttpDumpControllerTest extends DatabaseTestCase
{
    public function testFormUrlEncodedEventShouldBeStored()
    {
        $value = microtime(true);
        $this->call('POST', route('httpdump.event.store', 1), content: ['now' => $value]);

        /** @var \Modules\Events\Domain\Event $event */
        $event = $this->getRepositoryFor(Event::class)->findAll()->first();

        $this->assertSame('httpdump', $event->getType());
        $this->assertSame(['now' => $value], $event->getPayload()->toArray()['request']['body']);
        $this->assertSame('POST', $event->getPayload()->toArray()['request']['method']);

        return $event;
    }
}
