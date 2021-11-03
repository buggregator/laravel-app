<?php
declare(strict_types=1);

namespace Interfaces\Websocket\Controllers;

use Illuminate\Contracts\Broadcasting\Broadcaster;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Interfaces\Http\Controllers\Controller;

class ConnectAction extends Controller
{
    public function __invoke(
        Request     $request,
        Broadcaster $broadcaster,
    ): JsonResponse
    {
        $response = new JsonResponse([]);

        if ($request->attributes->has('ws:joinTopics')) {
            $channelName = $request->attributes->get('ws:joinTopics');

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
