<?php

declare(strict_types=1);

namespace App\Listeners\Event;

use Interfaces\Console\Handler;
use Modules\IncommingEvents\Domain\Events\EventWasReceived;

class SendToConsole
{
    public function handle(EventWasReceived $event)
    {
        if ($event->sendToConsole && app()->has(Handler::class)) {
            app(Handler::class)->handle($event->toPayload());
        }
    }
}
