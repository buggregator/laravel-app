<?php
declare(strict_types=1);

namespace Interfaces\Websocket\Middleware;

use App\Events\Websocket\Joined;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ChannelJoined
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    /**
     * @param Request $request
     * @param Response $response
     */
    public function terminate(Request $request, $response): void
    {
        if (!$request->attributes->has('ws:joinTopics')) {
            return;
        }

        if ($response->isOk()) {
            event(new Joined($request->attributes->get('ws:joinTopics')));
        }
    }
}
