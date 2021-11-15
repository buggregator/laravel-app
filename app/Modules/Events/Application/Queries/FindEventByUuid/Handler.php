<?php
declare(strict_types=1);

namespace Modules\Events\Application\Queries\FindEventByUuid;

use App\Commands\FindEventByUuid;
use App\Contracts\Query\QueryHandler;
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
        return new EventResource(
            $this->events->findByPK((string)$query->uuid)
        );
    }
}
