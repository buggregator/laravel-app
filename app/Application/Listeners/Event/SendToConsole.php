<?php

declare(strict_types=1);

namespace App\Listeners\Event;

use App\Attributes\EventBus\Listener;
use Interfaces\Console\Handler;
use Modules\IncommingEvents\Domain\Events\EventWasReceived;

class SendToConsole
{
    #[Listener]
    public function handle(EventWasReceived $event): void
    {
        if ($event->sendToConsole && app()->has(Handler::class)) {
            app(Handler::class)->handle($event->toPayload());
        }
    }
}
