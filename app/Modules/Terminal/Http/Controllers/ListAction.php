<?php

namespace Modules\Terminal\Http\Controllers;

use Inertia\Inertia;
use Interfaces\Http\Controllers\Controller;
use Spatie\RouteAttributes\Attributes\Get;

class ListAction extends Controller
{
    #[Get('/terminal', name: 'terminal')]
    public function __invoke()
    {
        return Inertia::render('Terminal/Index');
    }
}
