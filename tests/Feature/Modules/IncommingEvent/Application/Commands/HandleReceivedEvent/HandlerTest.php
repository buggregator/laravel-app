<?php

declare(strict_types=1);

namespace Modules\IncommingEvent\Application\Commands\HandleReceivedEvent;

use App\Commands\HandleReceivedEvent;
use Modules\IncommingEvents\Application\Commands\HandleReceivedEvent\Handler;
use Modules\IncommingEvents\Domain\EventProcess;
use Modules\IncommingEvents\Persistance\EventProcessAggregateRootRepository;
use Tests\TestCase;

class HandlerTest extends TestCase
{
    public function testHandle()
    {
        $repository = \Mockery::mock(EventProcessAggregateRootRepository::class);
        $repository->shouldReceive('persist')->once()->withArgs(function (EventProcess $process) {
            return $process->type() === 'foo'
                && $process->isDeleted() === false
                && $process->payload() === ['foo' => 'bar']
                && $process->date()->getTimestamp() === time();
        });

        $handler = new Handler($repository);
        $handler(new HandleReceivedEvent(
            'foo', ['foo' => 'bar']
        ));
    }
}
