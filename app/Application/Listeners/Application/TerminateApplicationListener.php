<?php

namespace App\Listeners\Application;

use Spiral\RoadRunnerLaravel\Events\AfterLoopIterationEvent;

class TerminateApplicationListener
{
    public function handle(AfterLoopIterationEvent $event): void
    {
        $event->application()->terminate();
    }
}
