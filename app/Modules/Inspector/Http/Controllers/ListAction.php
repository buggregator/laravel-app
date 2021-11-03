<?php
declare(strict_types=1);

namespace Modules\Inspector\Http\Controllers;

use App\Contracts\EventsRepository;
use Inertia\Inertia;
use Interfaces\Http\Controllers\Controller;

class ListAction extends Controller
{
    public function __invoke(EventsRepository $events)
    {
        return Inertia::render('Inspector/Index', [
            'events' => $events->all('inspector'),
        ]);
    }
}
