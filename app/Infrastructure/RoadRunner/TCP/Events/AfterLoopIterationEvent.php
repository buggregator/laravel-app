<?php

declare(strict_types=1);

namespace Infrastructure\RoadRunner\TCP\Events;

use Illuminate\Contracts\Foundation\Application;
use Spiral\RoadRunnerLaravel\Events\Contracts\WithApplication;

final class AfterLoopIterationEvent implements WithApplication
{
    public function __construct(private Application $app)
    {
    }

    public function application(): Application
    {
        return $this->app;
    }
}
