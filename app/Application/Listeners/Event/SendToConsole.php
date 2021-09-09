<?php
declare(strict_types=1);

namespace App\Listeners\Event;

use App\Events\EventReceived;
use Symfony\Component\Console\Output\ConsoleOutput;

class SendToConsole
{
    public function __construct(private ConsoleOutput $output)
    {
    }

    public function handle(EventReceived $event)
    {
        if ($event->sendToConsole) {
            $this->output->writeln(json_encode($event->payload, JSON_FORCE_OBJECT | JSON_HEX_TAG));
        }
    }
}
