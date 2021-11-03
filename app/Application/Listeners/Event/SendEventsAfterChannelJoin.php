<?php
declare(strict_types=1);

namespace App\Listeners\Event;

use App\Contracts\EventsRepository;
use App\Events\EventReceived;
use App\Events\Websocket\Joined;
use Illuminate\Contracts\Broadcasting\Broadcaster;

class SendEventsAfterChannelJoin
{
    public function __construct(
        private EventsRepository $events,
        private Broadcaster      $broadcaster,
    )
    {
    }

    public function handle(Joined $joined)
    {
        if ($joined->channel !== 'event') {
            return;
        }

        foreach ($this->events->all() as $e) {
            $this->broadcaster->broadcast([$joined->channel], EventReceived::class, $e);
        }
    }
}
