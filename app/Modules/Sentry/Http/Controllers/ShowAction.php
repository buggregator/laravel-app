<?php
declare(strict_types=1);

namespace Modules\Sentry\Http\Controllers;

use App\Contracts\EventsRepository;
use Inertia\Inertia;
use Interfaces\Http\Controllers\Controller;
use Ramsey\Uuid\UuidInterface;

class ShowAction extends Controller
{
    public function __invoke(EventsRepository $events, UuidInterface $uuid)
    {
        $event = $events->findByPK($uuid);
        if (!$event) {
            abort(404);
        }

        return Inertia::render('Sentry/Show', [
            'event' => $event,
            'events' => $events->findAll('sentry'),
        ]);
    }
}
