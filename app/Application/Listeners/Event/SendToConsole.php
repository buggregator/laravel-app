<?php
declare(strict_types=1);

namespace App\Listeners\Event;

use App\Events\EventReceived;
use Interfaces\Console\Handler;

class SendToConsole
{
    public function handle(EventReceived $event)
    {
        if ($event->sendToConsole) {
            app(Handler::class)->handle($event->toArray());
        }
    }
}
