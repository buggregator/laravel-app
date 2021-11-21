<?php

declare(strict_types=1);

namespace Modules\Events\Interfaces\Http\Controllers;

use App\Commands\FindAllEvents;
use App\Contracts\Query\QueryBus;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Interfaces\Http\Controllers\Controller;
use Modules\Events\Exceptions\ActionNotFoundException;
use Modules\Events\Interfaces\Http\ActionMap;
use Spatie\RouteAttributes\Attributes\Get;

class ListAction extends Controller
{
    #[Get(uri: '/', name: 'events')]
    public function eventList(Request $request, QueryBus $bus, ActionMap $actionMap, ?string $type = null)
    {
        $action = 'Events';
        if ($type) {
            try {
                $action = $actionMap->getForType($type, 'index');
            } catch (ActionNotFoundException $e) {
                abort(403, $e->getMessage());
            }
        }

        return Inertia::render($action, [
            'events' => $bus->ask(new FindAllEvents(type: $type)),
            'version' => config('app.version'),
            'name' => config('app.name'),
        ]);
    }

    #[Get(uri: '/events/{type}', name: 'events.type')]
    public function eventListByType(Request $request, QueryBus $bus, ActionMap $actionMap, string $type)
    {
        return $this->eventList($request, $bus, $actionMap, $type);
    }
}
