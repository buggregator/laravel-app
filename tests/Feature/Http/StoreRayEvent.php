<?php
declare(strict_types=1);

namespace Tests\Feature\Http;

use Modules\Events\Domain\Event;
use Tests\DatabaseTestCase;

class StoreRayEvent extends DatabaseTestCase
{
    public function testEventShouldBeStored()
    {
        $this->postJson(route('ray.event.store'), $payload = [
            'payloads' => [
                [
                    'type' => 'test',
                    'content' => [
                        'foo' => 'bar'
                    ]
                ]
            ]
        ]);

        /** @var \Modules\Events\Domain\Event $event */
        $event = $this->getRepositoryFor(Event::class)->findAll()->first();

        $this->assertSame('ray', $event->getEvent());
        $this->assertSame($payload, $event->getPayload()->toArray());
    }
}
