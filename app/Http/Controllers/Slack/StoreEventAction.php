<?php
declare(strict_types=1);

namespace App\Http\Controllers\Slack;

use App\EventsRepository;
use App\Http\Controllers\Controller;
use App\Websocket\ConnectionsRepository;
use Illuminate\Http\Request;
use Swoole\Http\Server;

class StoreEventAction extends Controller
{
    public function __invoke(
        Request               $request,
        Server                $server,
        EventsRepository      $events,
        ConnectionsRepository $connections
    ): void
    {
        $event = $request->all();
        $event = ['type' => 'slack', 'data' => $event];

        $events->store($event);

        foreach ($connections->all() as $client => $connection) {
            $server->push($client, json_encode($event));
        }
    }
}

