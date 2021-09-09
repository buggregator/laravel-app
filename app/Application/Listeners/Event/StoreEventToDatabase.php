<?php
declare(strict_types=1);

namespace App\Listeners\Event;

use App\Events\EventReceived;
use App\Contracts\EventsRepository;

class StoreEventToDatabase
{
    public function __construct(
        private EventsRepository $events,
    )
    {
    }


    public function handle(EventReceived $event)
    {
        $this->events->store($event->payload);
    }
}
