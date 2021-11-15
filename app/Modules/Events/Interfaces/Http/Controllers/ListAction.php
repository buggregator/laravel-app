<?php
declare(strict_types=1);

namespace Modules\Events\Interfaces\Http\Controllers;

use App\Commands\FindAllEvents;
use App\Contracts\Query\QueryBus;
use Inertia\Inertia;
use Interfaces\Http\Controllers\Controller;
use Modules\Events\Interfaces\Http\Resources\EventResource;

class ListAction extends Controller
{
    public function __invoke(QueryBus $bus)
    {
        return Inertia::render('Events', [
            'events' => $bus->ask(new FindAllEvents()),
            'version' => config('app.version'),
            'name' => config('app.name') ?? 'Buggregator',
        ]);
    }
}
