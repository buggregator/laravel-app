<?php
declare(strict_types=1);

namespace Modules\Inspector\Http\Controllers;

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

        return Inertia::render('Inspector/Show', [
            'events' => $events->all('inspector'),
            'event' => $event
        ]);
    }
}
