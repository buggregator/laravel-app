<?php
declare(strict_types=1);

namespace Modules\Monolog\Console;

use App\Events\EventReceived;
use App\TCP\CloseConnection;
use App\TCP\ContinueRead;
use App\TCP\Handler;
use App\TCP\RespondMessage;
use App\TCP\Response;
use Illuminate\Contracts\Events\Dispatcher;
use Spiral\RoadRunner\Tcp\Request;
use Spiral\RoadRunner\Tcp\TcpWorkerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TcpHandler implements Handler
{
    public function __construct(
        private Dispatcher                        $events,
        private \Interfaces\Console\StreamHandler $streamHandler
    )
    {
    }

    public function handle(Request $request, OutputInterface $output): Response
    {
        if ($request->event === TcpWorkerInterface::EVENT_CONNECTED) {
            return new ContinueRead();
        }

        $messages = array_filter(explode("\n", $request->body));

        foreach ($messages as $message) {
            $payload = json_decode($message, true);

            // Impossible to decode the message, give up.
            if (!$payload) {
                throw new \RuntimeException("Unable to decode a message from [{$request->connectionUuid}] client.");
            }

            $this->events->dispatch($event = new EventReceived('monolog', $payload));
            $this->streamHandler->handle($event->toArray());
        }

        return new CloseConnection();
    }
}
