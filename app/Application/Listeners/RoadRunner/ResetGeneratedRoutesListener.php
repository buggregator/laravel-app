<?php

namespace App\Listeners\RoadRunner;

use Tightenco\Ziggy\BladeRouteGenerator;

class ResetGeneratedRoutesListener
{
    /**
     * {@inheritdoc}
     */
    public function handle($event): void
    {
        BladeRouteGenerator::$generated = false;
    }
}
