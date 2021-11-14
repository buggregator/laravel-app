<?php
declare(strict_types=1);

namespace Modules\Ray\Http\Controllers;

use App\Commands\HandleReceivedEvent;
use App\Contracts\Command\CommandBus;
use Interfaces\Http\Controllers\Controller;
use Modules\Ray\Contracts\EventHandler;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Http\Request;
use Modules\Ray\Contracts\Payload;
use Symfony\Component\Console\Output\ConsoleOutput;

class StoreEventAction extends Controller
{
    public function __invoke(
        Request       $request,
        CommandBus    $commands,
        Repository    $cache,
        EventHandler  $handler,
        ConsoleOutput $output
    ): void
    {
        $type = $request->input('payloads.0.type');

        if ($type === Payload::TYPE_CREATE_LOCK) {
            $hash = $request->input('payloads.0.content.name');
            $cache->put($hash, 1, now()->addMinutes(5));
        } elseif ($type === Payload::TYPE_CLEAR_ALL) {
            // TODO fix this
            // $events->clear();
        }

        $event = $handler->handle($request->all());

        $commands->dispatch(
            new HandleReceivedEvent('ray', $event, true)
        );
    }
}
