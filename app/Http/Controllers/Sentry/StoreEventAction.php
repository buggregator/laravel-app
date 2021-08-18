<?php
declare(strict_types=1);

namespace App\Http\Controllers\Sentry;

use App\EventsRepository;
use App\Http\Controllers\Controller;
use App\Sentry\Contracts\EventHandler;
use App\Websocket\ConnectionsRepository;
use Illuminate\Http\Request;
use Swoole\Http\Server;

class StoreEventAction extends Controller
{
    public function __invoke(
        Request               $request,
        Server                $server,
        ConnectionsRepository $connections,
        EventsRepository      $events,
        EventHandler          $handler
    ): void
    {
        $stream = new \Http\Message\Encoding\GzipDecodeStream(
            new \GuzzleHttp\Psr7\Stream($request->getContent(true))
        );

        $event = $handler->handle(json_decode($stream->getContents(), true));
        $event = ['type' => 'sentry', 'data' => $event];

        $events->store($event);

        foreach ($connections->all() as $client => $connection) {
            $server->push($client, json_encode($event));
        }
    }
}

