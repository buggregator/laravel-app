<?php

namespace Modules\Terminal\Http\Controllers;

use App\Contracts\EventsRepository;
use Inertia\Inertia;

class ListAction
{
    public function __invoke(EventsRepository $events)
    {
        return Inertia::render('Terminal/Index');
    }
}
