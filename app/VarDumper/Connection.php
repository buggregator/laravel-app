<?php
declare(strict_types=1);

namespace App\VarDumper;

use App\EventsRepository;
use App\Websocket\Client;
use Closure;
use Ramsey\Uuid\Uuid;
use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\VarDumper\Cloner\Stub;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

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
            $payload = @unserialize(base64_decode($message), ['allowed_classes' => [Data::class, Stub::class]]);

            // Impossible to decode the message, give up.
            if (false === $payload) {
                $onError("Unable to decode a message from [{$this->clientId()}] client.");
                continue;
            }

            if (!is_array($payload) || count($payload) < 2 || !$payload[0] instanceof Data || !is_array($payload[1])) {
                $onError('Invalid payload from [' . $this->clientId() . '] client. Expected an array of two elements (Data $data, array $context)');
                continue;
            }

            $onMessage($payload);

            $this->events->store($event = [
                'type' => 'var-dump',
                'uuid' => Uuid::uuid4()->toString(),
                'data' => [
                    'payload' => [
                        'type' => $payload[0]->getType(),
                        'value' => $this->convertToPrimitive($payload[0])
                    ],
                    'context' => $payload[1]
                ]
            ]);

            (new Client())->sendEvent($event);
        }
    }

    public static function convertToPrimitive(Data $data)
    {
        if (in_array($data->getType(), ['string', 'boolean'])) {
            return $data->getValue();
        }

        $dumper = new HtmlDumper();
        return $dumper->dump($data, true);

    }
}
