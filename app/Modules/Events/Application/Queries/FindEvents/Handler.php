<?php

declare(strict_types=1);

namespace Modules\Events\Application\Queries\FindEvents;

use App\Commands\FindEvents;
use App\Contracts\Query\QueryHandler;
use Modules\Events\Application\Queries\EventsHandler;
use Modules\Events\Application\Resources\EventCollection;
use Modules\Events\Domain\EventRepository;

class Handler extends EventsHandler implements QueryHandler
{
    public function __construct(
        private EventRepository $events
    ) {
    }

    #[\App\Attributes\QueryBus\QueryHandler]
    public function __invoke(FindEvents $query): iterable
    {
        return EventCollection::make(
            $this->events->findAll(
                self::getScopeFromFindEvents($query),
                ['date' => 'asc'],
                $query->limit,
                $query->offset,
            )
        );
    }
}
