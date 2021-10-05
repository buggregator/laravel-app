<?php
declare(strict_types=1);

namespace Interfaces\Http\Controllers;

use App\Contracts\EventsRepository;
use Inertia\Inertia;

class ShowEventsAction extends Controller
{
    public function __invoke(EventsRepository $events)
    {
        return Inertia::render('Events', [
            'events' => $events->all('smtp'),
            'version' => config('app.version'),
            'name' => config('app.name') ?? 'Buggregator',
        ]);
    }
}
