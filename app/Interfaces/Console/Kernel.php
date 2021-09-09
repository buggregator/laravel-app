<?php

namespace Interfaces\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Interfaces\Console\Commands\Server\StartServerCommand;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        StartServerCommand::class,

        // ...
    ];

    protected function schedule(Schedule $schedule)
    {

    }
}
