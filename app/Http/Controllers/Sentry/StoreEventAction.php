<?php
declare(strict_types=1);

namespace App\Http\Controllers\Sentry;

use App\EventsRepository;
use App\Http\Controllers\Controller;
use App\Sentry\Contracts\EventHandler;
use App\Websocket\Server as WebsocketServer;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Output\ConsoleOutput;

class StoreEventAction extends Controller
{
    public function __invoke(
        Request          $request,
        WebsocketServer  $server,
        EventsRepository $events,
        EventHandler     $handler,
        ConsoleOutput    $output
    ): void
    {
        $stream = new \Http\Message\Encoding\GzipDecodeStream(
            new \GuzzleHttp\Psr7\Stream($request->getContent(true))
        );

        $event = $handler->handle(json_decode($stream->getContents(), true));
        $event = ['type' => 'sentry', 'uuid' => Uuid::uuid4()->toString(), 'data' => $event];

        $events->store($event);
        $server->sendEvent($event);

        $output->writeln(json_encode($event, JSON_FORCE_OBJECT | JSON_HEX_TAG));
    }
}

