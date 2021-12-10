<?php

declare(strict_types=1);

namespace Interfaces\Http\Controllers;

use Inertia\Inertia;
use Spatie\RouteAttributes\Attributes\Get;

class Settings extends Controller
{
    #[Get(uri: '/settings', name: 'settings')]
    public function __invoke()
    {
        return Inertia::render('Settings/Index');
    }
}
