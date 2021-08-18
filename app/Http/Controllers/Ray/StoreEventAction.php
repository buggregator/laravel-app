<?php
declare(strict_types=1);

namespace App\Http\Controllers\Ray;

use App\EventsRepository;
use App\Http\Controllers\Controller;
use App\Ray\Contracts\EventHandler;
use App\Websocket\ConnectionsRepository;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Http\Request;
use Swoole\Http\Server;

class StoreEventAction extends Controller
{
    public function __invoke(
        Request               $request,
        Server                $server,
        Repository            $cache,
        ConnectionsRepository $connections,
        EventsRepository      $events,
        EventHandler          $handler
    ): void
    {
        $type = $request->input('payloads.0.type');

        if ($type === 'create_lock') {
            $hash = $request->input('payloads.0.content.name');
            $cache->put($hash, 1, now()->addMinutes(5));
        }

        $event = $handler->handle($request->all());

        $event = ['type' => 'ray', 'data' => $event];
        $events->store($event);

        foreach ($connections->all() as $client => $connection) {
            $server->push($client, json_encode($event));
        }
    }
}
