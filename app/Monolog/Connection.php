<?php
declare(strict_types=1);

namespace App\Monolog;

use App\EventsRepository;
use App\Websocket\Client;
use Closure;
use Ramsey\Uuid\Uuid;

class Connection
{
    private \Swoole\Coroutine\Server\Connection $connection;

    public function __construct(
        private EventsRepository $events
    )
    {
    }

    public function clientId(): int
    {
        return $this->connection->exportSocket()->fd;
    }

    public function ready(\Swoole\Coroutine\Server\Connection $connection)
    {
        $this->connection = $connection;
    }

    public function close(): void
    {
        $this->connection->close();
    }

    public function handleMessage(string $content, Closure $onMessage, Closure $onError): void
    {
        while (true) {
            $data = $this->connection->recv(1);

            while (!empty($data)) {
                $content .= $data;
                continue 2;
            }

            break;
        }

        $messages = array_filter(explode("\n", $content));

        foreach ($messages as $message) {
            $payload = json_decode($message, true);

            // Impossible to decode the message, give up.
            if (false === $payload) {
                $onError("Unable to decode a message from [{$this->clientId()}] client.");
                continue;
            }

            $onMessage($event = [
                'type' => 'monolog',
                'timestamp' => time(),
                'uuid' => Uuid::uuid4()->toString(),
                'data' => $payload
            ]);

            $this->events->store($event);
            (new Client())->sendEvent($event);
        }
    }
}
