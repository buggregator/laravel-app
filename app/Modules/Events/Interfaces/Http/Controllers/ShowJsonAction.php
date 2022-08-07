<?php

declare(strict_types=1);

namespace Modules\Events\Interfaces\Http\Controllers;

use App\Commands\FindEventByUuid;
use App\Commands\FindEvents;
use App\Contracts\Query\QueryBus;
use App\Domain\ValueObjects\Uuid;
use App\Exceptions\EntityNotFoundException;
use Interfaces\Http\Controllers\Controller;
use Spatie\RouteAttributes\Attributes\Get;

class ShowJsonAction extends Controller
{
    #[Get(uri: '/event/{uuid}/json', name: 'event.show.json', middleware: 'auth')]
    public function __invoke(QueryBus $bus, Uuid $uuid)
    {
        try {
            return $bus->ask(new FindEventByUuid($uuid));
        } catch (EntityNotFoundException $e) {
            abort(404, $e->getMessage());
        }
    }

    #[Get(uri: '/events/{type}/{transactionId}/{projectId}/{offset}/{limit}/json', name: 'events.show.json', middleware: 'auth')]
    public function eventList(QueryBus $bus, string $type, int $transactionId, int $projectId, int $offset, int $limit)
    {
        return $bus->ask(new FindEvents(
            type: $type, projectId: $projectId, transactionId: $transactionId, offset: $offset, limit: $limit
        ));
    }
}
