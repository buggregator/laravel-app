<?php
declare(strict_types=1);

namespace Interfaces\Http\Controllers;

use Inertia\Inertia;

class ShowEventsAction extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Events', [
            'version' => config('app.version'),
            'name' => config('app.name') ?? 'Buggregator',
        ]);
    }
}
