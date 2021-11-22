<?php

declare(strict_types=1);

namespace Modules\Events\Application\Queries\FindEventByUuid;

use App\Commands\FindEventByUuid;
use App\Contracts\Query\QueryHandler;
use App\Exceptions\EntityNotFoundException;
use Modules\Events\Application\Resources\EventResource;
use Modules\Events\Domain\EventRepository;

class Handler implements QueryHandler
{
    public function __construct(private EventRepository $events)
    {
    }

    #[\App\Attributes\QueryBus\QueryHandler]
    public function __invoke(FindEventByUuid $query): EventResource
    {
        $event = $this->events->findByPK((string) $query->uuid);
        if (! $event) {
            throw new EntityNotFoundException('Event with given uuid is not found.');
        }

        return new EventResource($event);
    }
}
