<?php

declare(strict_types=1);

namespace Interfaces\Websocket\Controllers;

use Illuminate\Contracts\Broadcasting\Broadcaster;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Interfaces\Http\Controllers\Controller;
use Interfaces\Websocket\Middleware\ChannelJoined;
use Spatie\RouteAttributes\Attributes\Get;

class ConnectAction extends Controller
{
    #[Get(uri: '/ws', name: 'ws.join', middleware: [ChannelJoined::class])]
    public function __invoke(
        Request $request,
        Broadcaster $broadcaster,
    ): JsonResponse {
        $response = new JsonResponse();

        $channelName = $request->attributes->get('ws:joinTopics') ?? $request->header('X-WS-Join-Topics');
        if ($channelName) {
            if ($broadcaster->isGuardedChannel($channelName)) {
                $request->offsetSet('channel_name', $channelName);
                $response->setContent(
                    $broadcaster->auth($request)
                );
            }
        }

        return $response;
    }
}
