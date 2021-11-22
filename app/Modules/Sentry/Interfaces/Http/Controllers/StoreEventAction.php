<?php

declare(strict_types=1);

namespace Modules\Sentry\Interfaces\Http\Controllers;

use App\Commands\HandleReceivedEvent;
use App\Contracts\Command\CommandBus;
use GuzzleHttp\Psr7\Stream;
use Http\Message\Encoding\GzipDecodeStream;
use Illuminate\Http\Request;
use Interfaces\Http\Controllers\Controller;
use Modules\Sentry\Contracts\EventHandler;
use Spatie\RouteAttributes\Attributes\Post;
use Symfony\Component\Console\Output\ConsoleOutput;

class StoreEventAction extends Controller
{
    #[Post(uri: 'api/{projectId}/store', name: 'sentry.event.store')]
    public function __invoke(
        Request $request,
        CommandBus $commands,
        EventHandler $handler,
        ConsoleOutput $output
    ): void {
        $stream = new GzipDecodeStream(
            new Stream($request->getContent(true))
        );

        $event = $handler->handle(json_decode($stream->getContents(), true));

        $commands->dispatch(
            new HandleReceivedEvent('sentry', $event, true)
        );
    }
}
