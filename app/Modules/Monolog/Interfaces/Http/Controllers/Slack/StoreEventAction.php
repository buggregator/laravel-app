<?php

declare(strict_types=1);

namespace Modules\Monolog\Interfaces\Http\Controllers\Slack;

use App\Commands\HandleReceivedEvent;
use App\Contracts\Command\CommandBus;
use Illuminate\Http\Request;
use Interfaces\Http\Controllers\Controller;
use Spatie\RouteAttributes\Attributes\Post;

class StoreEventAction extends Controller
{
    #[Post(uri: 'slack', name: 'monolog.slack.event.store')]
    public function __invoke(
        Request $request,
        CommandBus $commands,
    ): void {
        $commands->dispatch(
            new HandleReceivedEvent('slack', $request->all())
        );
    }
}
