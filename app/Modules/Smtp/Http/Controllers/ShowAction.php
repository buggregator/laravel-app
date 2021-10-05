<?php
declare(strict_types=1);

namespace Modules\Smtp\Http\Controllers;

use App\Contracts\EventsRepository;
use Inertia\Inertia;
use Interfaces\Http\Controllers\Controller;

class ShowAction extends Controller
{
    public function __invoke(EventsRepository $events, string $uuid)
    {
        $event = $events->find($uuid);
        if (!$event) {
            abort(404);
        }

        return Inertia::render('Smtp/Mail', [
            'events' => fn() => $events->all('smtp'),
            'event' => $event
        ]);
    }
}
