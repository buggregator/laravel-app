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
    public function __invoke(Request $request, QueryBus $bus, ActionMap $actionMap)
    {
        $request->validate([
            'type' => 'sometimes|required|alpha_dash',
        ]);

        $action = 'Events';
        if ($request->type) {
            try {
                $action = $actionMap->getForType($request->type, 'index');
            } catch (ActionNotFoundException $e) {
                abort(403, $e->getMessage());
            }
        }

        return Inertia::render($action, [
            'events' => $bus->ask(new FindAllEvents(type: $request->type)),
            'version' => config('app.version'),
            'name' => config('app.name'),
        ]);
    }
}
