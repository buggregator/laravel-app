<?php

declare(strict_types=1);

namespace Modules\IncommingEvent\Application\Commands\DeleteEvent;

use App\Commands\DeleteEvent;
use App\Domain\ValueObjects\Uuid;
use Modules\IncommingEvents\Application\Commands\DeleteEvent\Handler;
use Modules\IncommingEvents\Domain\EventProcess;
use Modules\IncommingEvents\Persistance\EventProcessAggregateRootRepository;
use Tests\TestCase;

class HandlerTest extends TestCase
{
    public function testHandle()
    {
        $uuid = Uuid::generate();

        $repository = \Mockery::mock(EventProcessAggregateRootRepository::class);
        $repository->shouldReceive('retrieve')
            ->once()
            ->with($uuid)
            ->andReturn($aggregateRoot = EventProcess::received($uuid, 'foo', ['foo' => 'bar'], 123));

        $repository->shouldReceive('persist')->once()
            ->withArgs(function (EventProcess $process) use ($aggregateRoot) {
                return $process === $aggregateRoot && $process->isDeleted();
            });

        $handler = new Handler($repository);
        $handler(new DeleteEvent($uuid));
    }
}
