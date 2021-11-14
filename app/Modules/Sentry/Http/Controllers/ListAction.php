<?php
declare(strict_types=1);

namespace Modules\Sentry\Http\Controllers;

use App\Commands\FindAllEvents;
use App\Contracts\Query\QueryBus;
use Inertia\Inertia;
use Interfaces\Http\Controllers\Controller;

class ListAction extends Controller
{
    public function __invoke(QueryBus $bus)
    {
        $events = $bus->ask(new FindAllEvents(type: 'sentry'));

        return Inertia::render('Sentry/Index', [
            'events' => $events,
        ]);
    }
}
