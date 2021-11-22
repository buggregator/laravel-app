<?php

declare(strict_types=1);

namespace App\Listeners\RoadRunner;

use App\Attributes\Locator;
use Interfaces\Console\Handler;
use Interfaces\Console\StreamHandler;
use Spiral\RoadRunnerLaravel\Events\BeforeLoopStartedEvent;
use Symfony\Component\Console\Output\ConsoleOutput;

class RegisterConsoleStreamHandlerListener
{
    public function handle(BeforeLoopStartedEvent $event): void
    {
        $event->application()->instance(
            Handler::class,
            new StreamHandler(
                (new ConsoleOutput())->getErrorOutput(),
                $event->application(),
                new Locator(
                    $event->application()
                )
            )
        );
    }
}
