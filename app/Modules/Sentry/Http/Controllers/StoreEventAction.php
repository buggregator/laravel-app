<?php
declare(strict_types=1);

namespace Modules\Sentry\Http\Controllers;

use App\Commands\HandleReceivedEvent;
use App\Contracts\Command\CommandBus;
use Interfaces\Http\Controllers\Controller;
use Modules\Sentry\Contracts\EventHandler;
use Illuminate\Http\Request;
use Symfony\Component\Console\Output\ConsoleOutput;

class StoreEventAction extends Controller
{
    public function __invoke(
        Request       $request,
        CommandBus    $commands,
        EventHandler  $handler,
        ConsoleOutput $output
    ): void
    {
        $stream = new \Http\Message\Encoding\GzipDecodeStream(
            new \GuzzleHttp\Psr7\Stream($request->getContent(true))
        );

        $event = $handler->handle(json_decode($stream->getContents(), true));

        $commands->dispatch(
            new HandleReceivedEvent('sentry', $event, true)
        );
    }
}

