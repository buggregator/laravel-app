<?php
declare(strict_types=1);

namespace Modules\Events\Application\Queries\FindAllEvents;

use App\Commands\FindAllEvents;
use App\Contracts\Query\QueryHandler;
use Modules\Events\Domain\EventRepository;

class Handler implements QueryHandler
{
    public function __construct(private EventRepository $events)
    {

    }

    #[\App\Attributes\QueryBus\QueryHandler]
    public function __invoke(FindAllEvents $query): iterable
    {
        $scope = [];
        if ($query->type) {
            $scope['event'] = $query->type;
        }

        return $this->events->findAll($scope);
    }
}
