<?php
declare(strict_types=1);

namespace App\Http\Controllers\Slack;

use App\EventsRepository;
use App\Http\Controllers\Controller;
use App\WebsocketServer;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class StoreEventAction extends Controller
{
    public function __invoke(
        Request          $request,
        WebsocketServer  $server,
        EventsRepository $events
    ): void
    {
        $event = $request->all();
        $event = ['type' => 'slack', 'uuid' => Uuid::uuid4()->toString(), 'data' => $event];

        $events->store($event);
        $server->sendEvent($event);
    }
}

