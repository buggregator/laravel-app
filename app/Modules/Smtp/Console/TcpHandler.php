<?php

declare(strict_types=1);

namespace Modules\Smtp\Console;

use App\Commands\HandleReceivedEvent;
use App\Contracts\Command\CommandBus;
use App\Contracts\TCP\Handler;
use App\Contracts\TCP\Response;
use App\TCP\CloseConnection;
use App\TCP\RespondMessage;
use Illuminate\Contracts\Cache\Repository;
use Modules\Smtp\Mail\Parser;
use Spiral\RoadRunner\Tcp\Request;
use Spiral\RoadRunner\Tcp\TcpWorkerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TcpHandler implements Handler
{
    public const READY = 220;
    public const OK = 250;
    public const CLOSING = 221;
    public const START_MAIL_INPUT = 354;

    public function __construct(
        private CommandBus $commands,
        private \Interfaces\Console\StreamHandler $streamHandler,
        private Repository $cache
    ) {
    }

    public function handle(Request $request, OutputInterface $output): Response
    {
        if ($request->event === TcpWorkerInterface::EVENT_CONNECTED) {
            return $this->send(static::READY, 'mailamie');
        }

        $cacheKey = 'smtp:'.$request->connectionUuid;
        $message = $this->cache->get($cacheKey, []);

        $response = new CloseConnection();

        if ($request->event === TcpWorkerInterface::EVENT_CLOSED) {
            $this->cache->delete($cacheKey);

            return new CloseConnection();
        } elseif (preg_match('/^(EHLO|HELO|MAIL FROM:)/', $request->body)) {
            $response = $this->send(static::OK);
        } elseif (preg_match('/^RCPT TO:<(.*)>/', $request->body, $matches)) {
            $message['recipients'][] = $matches[0];
            $response = $this->send(static::OK);
        } elseif (str_starts_with($request->body, 'QUIT')) {
            $response = $this->send(static::CLOSING, null, true);
        } elseif ($request->body === "DATA\r\n") {
            $response = $this->send(static::START_MAIL_INPUT);
            $message['collecting'] = true;
        } elseif ($message['collecting'] ?? false) {
            $content = $message['content'] ?? '';
            $response = $this->send(static::OK);
            $content .= preg_replace("/^(\.\.)/m", '.', $request->body);

            if ($this->endOfContentDetected($request->body)) {
                $messages = array_filter(explode("\r\n.\r\n", $content));

                if (count($messages) === 1) {
                    $this->dispatchMessage($content);
                } elseif (! empty($messages[1])) {
                    $this->dispatchMessage($messages[0]);
                    $response = $this->send(static::CLOSING, null, true);
                }
            }

            $message['content'] = $content;
        }

        $this->cache->set($cacheKey, $message);

        return $response;
    }

    private function dispatchMessage(string $message): void
    {
        $message = (new Parser())->parse($message);

        $data = $message->jsonSerialize();
        $this->commands->dispatch(
            $command = new HandleReceivedEvent('smtp', $data)
        );
        $this->streamHandler->handle($command->toArray());
    }

    private function endOfContentDetected(string $data): bool
    {
        return str_ends_with($data, "\r\n.\r\n");
    }

    private function send(int $statusCode, string|null $comment = null, bool $close = false): RespondMessage
    {
        $response = implode(' ', array_filter([$statusCode, $comment]));

        return new RespondMessage("{$response} \r\n", $close);
    }
}
