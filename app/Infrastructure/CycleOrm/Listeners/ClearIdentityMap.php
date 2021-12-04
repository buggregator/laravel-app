<?php

declare(strict_types=1);

namespace Infrastructure\CycleOrm\Listeners;

use Cycle\ORM\ORMInterface;
use Infrastructure\RoadRunner\TCP\Events\AfterLoopIterationEvent as TCPAfterLoopIterationEventAlias;
use Spiral\RoadRunnerLaravel\Events\AfterLoopIterationEvent as HTTPAfterLoopIterationEventAlias;

class ClearIdentityMap
{
    public function handle(
        TCPAfterLoopIterationEventAlias|HTTPAfterLoopIterationEventAlias $event
    ): void {
        app(ORMInterface::class)->getHeap()->clean();
    }
}
