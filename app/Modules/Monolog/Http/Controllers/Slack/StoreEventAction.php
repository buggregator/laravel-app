<?php
declare(strict_types=1);

namespace Modules\Monolog\Http\Controllers\Slack;

use App\Commands\HandleReceivedEvent;
use App\Contracts\Command\CommandBus;
use Interfaces\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreEventAction extends Controller
{
    public function __invoke(
        Request    $request,
        CommandBus $commands,
    ): void
    {
        $commands->dispatch(
            new HandleReceivedEvent('slack', $request->all())
        );
    }
}

