<?php

declare(strict_types=1);

namespace Modules\Events\Interfaces\Http\Controllers;

use App\Commands\ClearEvents;
use App\Contracts\Command\CommandBus;
use Illuminate\Http\Request;
use Interfaces\Http\Controllers\Controller;
use Spatie\RouteAttributes\Attributes\Delete;

class ClearEventsAction extends Controller
{
    #[Delete(uri: '/events', name: 'events.clear')]
    public function __invoke(Request $request, CommandBus $bus): void
    {
        $bus->dispatch(
            new ClearEvents(type: $request->input('type'))
        );
    }
}
