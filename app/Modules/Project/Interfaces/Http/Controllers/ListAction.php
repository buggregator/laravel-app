<?php

namespace Modules\Project\Interfaces\Http\Controllers;

use App\Commands\FindAllProjects;
use App\Contracts\Query\QueryBus;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Interfaces\Http\Controllers\Controller;
use Spatie\RouteAttributes\Attributes\Get;

class ListAction extends Controller
{
    #[Get(uri: '/projects', name: 'projects', middleware: 'auth')]
    public function projectList(Request $request, QueryBus $bus): \Inertia\Response
    {
        return Inertia::render('Projects/Index', [
            'projects' => $bus->ask(new FindAllProjects()),
        ]);
    }
}
