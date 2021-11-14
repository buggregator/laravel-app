<?php
declare(strict_types=1);

namespace Tests\Feature\Modules\Events\Application\Commands\StoreEvent;

use Modules\Events\Application\Commands\StoreEvent\Command;
use Modules\Events\Application\Commands\StoreEvent\Handler;
use Modules\Events\Domain\Event;
use Modules\Events\Domain\EventRepository;
use Ramsey\Uuid\Uuid;
use Tests\DatabaseTestCase;

class HandlerTest extends DatabaseTestCase
{
    public function testEventShouldBeStored()
    {
        $command = new Command(
            type: 'test',
            uuid: $uuid = Uuid::uuid4(),
            date: $date = new \DateTimeImmutable(),
            payload: ['foo' => 'bar']
        );

        $repository = \Mockery::mock(EventRepository::class);
        $repository->shouldReceive('store')->once()->withArgs(
            fn(Event $event) =>
            $event->getUuid()->equals($uuid)
            && $event->getEvent() === 'test'
            && $event->getDate() === $date
            && (string) $event->getPayload() === '{"foo":"bar"}'
        );

        $handler = new Handler($repository);
        $handler->handle($command);
    }
}
