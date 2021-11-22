<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use Modules\Events\Domain\Event;
use Tests\DatabaseTestCase;

class MonologSlackControllerTest extends DatabaseTestCase
{
    public function testEventShouldBeStored()
    {
        $this->postJson(route('monolog.slack.event.store'), ['foo' => 'bar']);

        /** @var \Modules\Events\Domain\Event $event */
        $event = $this->getRepositoryFor(Event::class)->findAll()->first();

        $this->assertSame('slack', $event->getType());
        $this->assertSame(['foo' => 'bar'], $event->getPayload()->toArray());
    }
}
