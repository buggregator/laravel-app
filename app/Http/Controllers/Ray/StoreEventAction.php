<?php
declare(strict_types=1);

namespace App\Http\Controllers\Ray;

use App\EventsRepository;
use App\Http\Controllers\Controller;
use App\Ray\Contracts\EventHandler;
use App\WebsocketServer;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class StoreEventAction extends Controller
{
    public function __invoke(
        Request          $request,
        WebsocketServer  $server,
        Repository       $cache,
        EventsRepository $events,
        EventHandler     $handler
    ): void
    {
        $type = $request->input('payloads.0.type');

        if ($type === 'create_lock') {
            $hash = $request->input('payloads.0.content.name');
            $cache->put($hash, 1, now()->addMinutes(5));
        } elseif ($type === 'clear_all') {
            $events->clear();
        }

        $event = $handler->handle($request->all());
        $event = ['type' => 'ray', 'uuid' => Uuid::uuid4()->toString(), 'data' => $event];

        $events->store($event);
        $server->sendEvent($event);
    }
}
