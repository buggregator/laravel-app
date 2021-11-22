<?php

declare(strict_types=1);

namespace Modules\Events\Interfaces\Http\Controllers;

use App\Commands\FindAllEvents;
use App\Commands\FindEventByUuid;
use App\Contracts\Query\QueryBus;
use App\Domain\ValueObjects\Uuid;
use App\Exceptions\EntityNotFoundException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Interfaces\Http\Controllers\Controller;
use Modules\Events\Exceptions\ActionNotFoundException;
use Modules\Events\Interfaces\Http\ActionMap;
use Spatie\RouteAttributes\Attributes\Get;

class ShowAction extends Controller
{
    #[Get(uri: '/events/{type}/{uuid}', name: 'event.show')]
    public function __invoke(Request $request, QueryBus $bus, ActionMap $actionMap, string $type, Uuid $uuid)
    {
        try {
            $action = $actionMap->getForType($type, 'show');
        } catch (ActionNotFoundException $e) {
            abort(403, $e->getMessage());
        }

        try {
            $event = $bus->ask(new FindEventByUuid($uuid));
        } catch (EntityNotFoundException $e) {
            abort(404, $e->getMessage());
        }

        return Inertia::render($action, [
            'event' => $event,
            'events' => $bus->ask(new FindAllEvents(type: $type)),
        ]);
    }
}
