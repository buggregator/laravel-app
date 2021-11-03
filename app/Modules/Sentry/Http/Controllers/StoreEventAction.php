<?php
declare(strict_types=1);

namespace Modules\Sentry\Http\Controllers;

use App\Events\EventReceived;
use Illuminate\Contracts\Events\Dispatcher;
use Interfaces\Http\Controllers\Controller;
use Modules\Sentry\Contracts\EventHandler;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Output\ConsoleOutput;

class StoreEventAction extends Controller
{
    public function __invoke(
        Request       $request,
        Dispatcher    $events,
        EventHandler  $handler,
        ConsoleOutput $output
    ): void
    {
        $stream = new \Http\Message\Encoding\GzipDecodeStream(
            new \GuzzleHttp\Psr7\Stream($request->getContent(true))
        );

        $event = $handler->handle(json_decode($stream->getContents(), true));

        $events->dispatch(
            new EventReceived('sentry', $event, true)
        );
    }
}

