<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Events\Application\Commands\StoreEvent;

use App\Domain\ValueObjects\Uuid;
use Cycle\ORM\EntityManagerInterface;
use Illuminate\Contracts\Events\Dispatcher;
use Modules\Events\Application\Commands\StoreEvent\Command;
use Modules\Events\Application\Commands\StoreEvent\Handler;
use Modules\Events\Domain\Event;
use Tests\DatabaseTestCase;

class HandlerTest extends DatabaseTestCase
{
    public function testEventShouldBeStored()
    {
        $command = new Command(
            type: 'test',
            uuid: $uuid = Uuid::generate(),
            date: $date = new \DateTimeImmutable(),
            payload: ['foo' => 'bar'],
            projectId: 1,
            transactionId: null
        );

        $entityManager = \Mockery::mock(EntityManagerInterface::class);
        $entityManager->shouldReceive('persist')->once()->withArgs(
            fn (Event $event) => $event->getUuid()->equals($uuid)
                && $event->getType() === 'test'
                && $event->getDate() === $date
                && (string) $event->getPayload() === '{"foo":"bar"}'
        );

        $entityManager->shouldReceive('run')->once();

        $handler = new Handler(\Mockery::mock(Dispatcher::class), $entityManager);
        $handler->handle($command);
    }
}
