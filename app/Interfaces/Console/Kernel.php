<?php

declare(strict_types=1);

namespace Interfaces\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Interfaces\Console\Commands\ConfigureApp;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        ConfigureApp::class,
    ];

    protected function schedule(Schedule $schedule)
    {
    }
}
