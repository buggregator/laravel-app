<?php
declare(strict_types=1);

namespace App\Http\Controllers;
use Inertia\Inertia;

class ShowEventsAction extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Logs');
    }
}
