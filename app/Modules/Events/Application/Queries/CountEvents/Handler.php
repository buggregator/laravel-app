<?php

declare(strict_types=1);

namespace Modules\Events\Application\Queries\CountEvents;

use App\Commands\CountEvents;
use App\Contracts\Query\QueryHandler;
use Modules\Events\Application\Queries\EventsHandler;
use Modules\Events\Domain\EventRepository;

class Handler extends EventsHandler implements QueryHandler
{
    public function __construct(
        private EventRepository $events
    ) {
    }

    #[\App\Attributes\QueryBus\QueryHandler]
    public function __invoke(CountEvents $query): int
    {
        return $this->events->count(self::getScopeFromFindEvents($query));
    }
}
