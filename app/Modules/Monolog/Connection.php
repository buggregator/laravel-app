<?php
declare(strict_types=1);

namespace Modules\Monolog;

use Closure;
use Ramsey\Uuid\Uuid;
use Swoole\Coroutine\Server\Connection as SwooleConnection;

class Connection
{
    private SwooleConnection $connection;

    public function clientId(): int
    {
        return $this->connection->exportSocket()->fd;
    }

    public function ready(SwooleConnection $connection)
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
        }
    }
}
