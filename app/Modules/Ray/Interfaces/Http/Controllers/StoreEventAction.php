<?php

declare(strict_types=1);

namespace Modules\Ray\Interfaces\Http\Controllers;

use App\Commands\ClearEvents;
use App\Commands\HandleReceivedEvent;
use App\Contracts\Command\CommandBus;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Http\Request;
use Interfaces\Http\Controllers\Controller;
use Modules\Ray\Contracts\EventHandler;
use Modules\Ray\Contracts\Payload;
use Spatie\RouteAttributes\Attributes\Post;
use Symfony\Component\Console\Output\ConsoleOutput;

class StoreEventAction extends Controller
{
    #[Post(uri: '/', name: 'ray.event.store')]
    public function __invoke(
        Request $request,
        CommandBus $commands,
        Repository $cache,
        EventHandler $handler,
        ConsoleOutput $output
    ): void {
        $type = $request->input('payloads.0.type');

        if ($type === Payload::TYPE_CREATE_LOCK) {
            $hash = $request->input('payloads.0.content.name');
            $cache->put($hash, 1, now()->addMinutes(5));
        } elseif ($type === Payload::TYPE_CLEAR_ALL) {
            // TODO fix this
            $commands->dispatch(new ClearEvents('ray'));
        }

        $event = $handler->handle($request->all());

        $commands->dispatch(
            new HandleReceivedEvent('ray', $event, true)
        );
    }
}
