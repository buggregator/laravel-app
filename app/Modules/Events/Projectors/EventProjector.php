<?php

declare(strict_types=1);

namespace Modules\Events\Projectors;

use App\Contracts\Command\CommandBus;
use App\Contracts\EventSource\Projector;
use Illuminate\Support\Carbon;
use Modules\Events\Application\Commands\DeleteEvent\Command as DeleteEventCommand;
use Modules\Events\Application\Commands\StoreEvent\Command as StoreEventCommand;
use Modules\IncommingEvents\Domain\Events\EventWasDeleted;
use Modules\IncommingEvents\Domain\Events\EventWasReceived;

#[\App\Attributes\Projectors\Projector]
class EventProjector implements Projector
{
    public function __construct(private CommandBus $bus)
    {
    }

    public function onEventWasReceived(EventWasReceived $event): void
    {
        $this->bus->dispatch(
            new StoreEventCommand(
                $event->type,
                $event->uuid,
                Carbon::createFromTimestamp($event->timestamp)->toDateTimeImmutable(),
                $event->payload
            )
        );
    }

    public function onEventDeleted(EventWasDeleted $event): void
    {
        $this->bus->dispatch(
            new DeleteEventCommand($event->uuid)
        );
    }
}
