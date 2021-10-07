<?php
declare(strict_types=1);

namespace Modules\Ray\Http\Controllers;

use App\Events\EventReceived;
use Illuminate\Contracts\Events\Dispatcher;
use Interfaces\Http\Controllers\Controller;
use Modules\Ray\Contracts\EventHandler;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Http\Request;
use Modules\Ray\Contracts\Payload;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Output\ConsoleOutput;

class StoreEventAction extends Controller
{
    public function __invoke(
        Request       $request,
        Dispatcher    $events,
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
            $events->clear();
        }

        $event = $handler->handle($request->all());

        $events->dispatch(
            new EventReceived('ray', $event, true)
        );
    }
}
