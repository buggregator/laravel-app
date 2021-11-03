<?php
declare(strict_types=1);

namespace Modules\Smtp\Http\Controllers;

use App\Contracts\EventsRepository;
use Inertia\Inertia;
use Interfaces\Http\Controllers\Controller;

class ListAction extends Controller
{
    public function __invoke(EventsRepository $events)
    {
        return Inertia::render('Smtp/Index', [
            'events' => $events->all('smtp'),
        ]);
    }
}
