<?php
declare(strict_types=1);

namespace Modules\Smtp\Http\Controllers;

use App\Contracts\EventsRepository;
use Inertia\Inertia;
use Interfaces\Http\Controllers\Controller;
use Ramsey\Uuid\UuidInterface;

class ShowAction extends Controller
{
    public function __invoke(EventsRepository $events, UuidInterface $uuid)
    {
        $event = $events->find($uuid);
        if (!$event) {
            abort(404);
        }

        return Inertia::render('Smtp/Show', [
            'event' => $event,
            'events' => $events->all('smtp'),
        ]);
    }
}
