<?php
declare(strict_types=1);

namespace App\Http\Controllers\Slack;

use App\Http\Controllers\Controller;
use App\Websocket\ConnectionsRepository;
use Illuminate\Http\Request;
use Swoole\Http\Server;

class StoreEventAction extends Controller
{
    public function __invoke(
        Request               $request,
        Server                $server,
        ConnectionsRepository $connections
    ): void
    {
        $event = $request->all();

        foreach ($connections->all() as $client => $connection) {
            $server->push($client, json_encode([
                'type' => 'slack',
                'data' => $event
            ]));
        }
    }
}

