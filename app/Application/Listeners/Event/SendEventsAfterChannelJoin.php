<?php

declare(strict_types=1);

namespace App\Listeners\Event;

use App\Events\EventReceived;
use App\Events\Websocket\Joined;
use Illuminate\Contracts\Broadcasting\Broadcaster;

class SendEventsAfterChannelJoin
{
    public function __construct(
        private EventRepository $events,
        private Broadcaster $broadcaster,
    ) {
    }

    public function handle(Joined $joined)
    {
        if ($joined->channel !== 'event') {
            return;
        }

        foreach ($this->events->findAll() as $e) {
            $this->broadcaster->broadcast([$joined->channel], EventReceived::class, $e);
        }
    }
}
