<?php
declare(strict_types=1);

namespace Modules\Sentry\Http\Controllers;

use App\Commands\FindAllEvents;
use App\Commands\FindEventByUuid;
use App\Contracts\Query\QueryBus;
use App\Domain\ValueObjects\Uuid;
use Inertia\Inertia;
use Interfaces\Http\Controllers\Controller;
use Ramsey\Uuid\UuidInterface;

class ShowAction extends Controller
{
    public function __invoke(QueryBus $bus, UuidInterface $uuid)
    {
        $event = $bus->ask(new FindEventByUuid(new Uuid($uuid)));
        if (!$event) {
            abort(404);
        }

        return Inertia::render('Sentry/Show', [
            'event' => $event,
            'events' => $bus->ask(new FindAllEvents('sentry')),
        ]);
    }
}
