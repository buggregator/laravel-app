<?php

declare(strict_types=1);

namespace Infrastructure\CycleOrm\Listeners;

use Infrastructure\RoadRunner\TCP\Events\AfterLoopIterationEvent;

class ClearIdentityMap
{
    public function handle(AfterLoopIterationEvent|\Spiral\RoadRunnerLaravel\Events\AfterLoopIterationEvent $event
    ): void {
        // TODO fix it
        // app(ORMInterface::class)->getHeap()->clean();
    }
}
