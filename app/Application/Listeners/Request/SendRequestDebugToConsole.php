<?php
declare(strict_types=1);

namespace App\Listeners\Request;

use Spiral\RoadRunnerLaravel\Events\Contracts\WithHttpRequest;
use Spiral\RoadRunnerLaravel\Events\Contracts\WithHttpResponse;
use Spiral\RoadRunnerLaravel\Listeners\ListenerInterface;
use function Termwind\render;

class SendRequestDebugToConsole implements ListenerInterface
{
    public function handle($event): void
    {
        if (!config('app.debug')) {
            return;
        }

        if ($event instanceof WithHttpRequest && $event instanceof WithHttpResponse) {
            $request = $event->httpRequest();

            if ($request instanceof \Illuminate\Http\Request) {
                $statusCode = $event->httpResponse()->getStatusCode();

                render((string)view('console.http.request', [
                    'request' => $request,
                    'response' => $event->httpResponse(),
                    'color' => match ($request->method()) {
                        'GET' => 'magenta',
                        'POST' => 'blue',
                        'PUT' => 'yellow',
                        'DELETE' => 'red',
                    },
                    'responseColor' => match (true) {
                        $statusCode >= 500 => 'red',
                        $statusCode >= 400 => 'yellow',
                        $statusCode >= 300 => 'cyan',
                        $statusCode >= 100 => 'green',
                        default => 'white',
                    },
                    'duration' => number_format(round(microtime(true) - $request->getTimestamp(), 4), 4, '.', ''),
                    'memory' => number_format($request->getAllocatedMemory() / 1024 / 1204, 2, '.', ''),
                ]));
            }
        }
    }
}
