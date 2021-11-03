<?php
declare(strict_types=1);

namespace Modules\Events\Projectors;

use App\Contracts\EventsRepository;
use App\Events\EventReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class EventReceivedProjector extends Projector implements ShouldQueue
{
    public function __construct(private EventsRepository $events)
    {}

    public function onEventReceived(EventReceived $event)
    {
        $this->events->store($event->toArray());
    }
}
