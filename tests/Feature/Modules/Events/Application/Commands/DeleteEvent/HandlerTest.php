<?php

declare(strict_types=1);

namespace Modules\Events\Application\Commands\DeleteEvent;

use App\Domain\Entity\Json;
use App\Domain\ValueObjects\Uuid;
use Cycle\ORM\TransactionInterface;
use Illuminate\Contracts\Events\Dispatcher;
use Modules\Events\Domain\Event;
use Modules\Events\Domain\EventRepository;
use Tests\TestCase;

class HandlerTest extends TestCase
{
    public function testEventShouldBeDeleted()
    {
        $command = new Command($uuid = Uuid::generate());

        $repository = \Mockery::mock(EventRepository::class);
        $repository->shouldReceive('findByPK')
            ->once()
            ->with($uuid->toString())
            ->andReturn($event = new Event($uuid, 'foo', new Json(['foo' => 'bar']), new \DateTimeImmutable()));

        $transaction = \Mockery::mock(TransactionInterface::class);
        $transaction->shouldReceive('delete')->once()->with($event);
        $transaction->shouldReceive('run')->once();

        $handler = new Handler($repository, \Mockery::mock(Dispatcher::class), $transaction);
        $handler($command);
    }
}
