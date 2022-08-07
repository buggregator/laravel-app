<?php

declare(strict_types=1);

namespace Modules\Events\Interfaces\Http\Controllers;

use App\Commands\CountEvents;
use App\Commands\FindEvents;
use App\Contracts\Query\QueryBus;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Interfaces\Http\Controllers\Controller;
use Modules\Events\Exceptions\ActionNotFoundException;
use Modules\Events\Interfaces\Http\ActionMap;
use Spatie\RouteAttributes\Attributes\Get;

class ListAction extends Controller
{
    #[Get(uri: '/', name: 'events', middleware: 'auth')]
    public function eventList(Request $request, QueryBus $bus, ActionMap $actionMap, ?string $type = null, ?int $projectId = null, ?int $transactionId = null)
    {
        $action = 'Events';
        if ($type) {
            try {
                $action = $actionMap->getForType($type, 'index');
            } catch (ActionNotFoundException $e) {
                abort(403, $e->getMessage());
            }
        }

        $props = [
            'version' => config('app.version'),
            'name' => config('app.name'),
        ];

        if ($action == 'SentryTransaction/Index') {
            $props = array_merge($props, [
                'eventsCount' => $bus->ask(new CountEvents(
                    type: $type, projectId: $projectId, transactionId: $transactionId
                )),
                'type' => $type,
                'projectId' => $projectId,
                'transactionId' => $transactionId,
            ]);
        } else {
            $props = array_merge($props, [
                'events' => $bus->ask(new FindEvents(type: $type, projectId: $projectId)),
            ]);
        }

        return Inertia::render($action, $props);
    }

    #[Get(uri: '/events/type/{type}/{projectId?}', name: 'events.type')]
    public function eventListByType(Request $request, QueryBus $bus, ActionMap $actionMap, string $type, ?int $projectId = null)
    {
        return $this->eventList($request, $bus, $actionMap, $type, $projectId);
    }

    #[Get(uri: '/events/transactions/{transactionId}/{projectId}', name: 'events.transactions', middleware: 'auth')]
    public function transactionEventList(Request $request, QueryBus $bus, ActionMap $actionMap, int $transactionId, int $projectId): \Inertia\Response
    {
        return $this->eventList($request, $bus, $actionMap, 'sentryTransaction', $projectId, $transactionId);
    }
}
