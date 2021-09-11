<?php

namespace Interfaces\Websocket\Controllers;

use App\Contracts\EventsRepository;
use App\Events\EventReceived;
use Illuminate\Contracts\Broadcasting\Broadcaster;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Interfaces\Http\Controllers\Controller;

class ConnectAction extends Controller
{
    public function __invoke(
        Request          $request,
        EventsRepository $events,
        Broadcaster      $broadcaster,
        Application      $app
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

//            $app->terminating(static function () use ($channelName, $events, $broadcaster) {
//                foreach ($events->all() as $e) {
//                    $broadcaster->broadcast([$channelName], EventReceived::class, $e);
//                }
//            });
        }

        return $response;
    }
}
