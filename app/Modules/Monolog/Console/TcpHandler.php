<?php

declare(strict_types=1);

namespace Modules\Monolog\Console;

use App\Commands\HandleReceivedEvent;
use App\Contracts\Command\CommandBus;
use App\Contracts\TCP\Handler;
use App\Contracts\TCP\Response;
use App\TCP\CloseConnection;
use App\TCP\ContinueRead;
use RuntimeException;
use Spiral\RoadRunner\Tcp\Request;
use Spiral\RoadRunner\Tcp\TcpWorkerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TcpHandler implements Handler
{
    public function __construct(
        private CommandBus $commands,
        private \Interfaces\Console\StreamHandler $streamHandler
    ) {
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
            if (! $payload) {
                throw new RuntimeException("Unable to decode a message from [{$request->connectionUuid}] client.");
            }

            $this->commands->dispatch(
                $command = new HandleReceivedEvent('monolog', $payload)
            );

            $this->streamHandler->handle($command->toArray());
        }

        return new CloseConnection();
    }
}
