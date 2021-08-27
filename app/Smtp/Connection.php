<?php
declare(strict_types=1);

namespace App\Smtp;

use App\EventsRepository;
use Ramsey\Uuid\Uuid;
use Swoole\Coroutine\Server\Connection as SwooleConnection;

class Connection
{
    const READY = 220;
    const OK = 250;
    const CLOSING = 221;
    const START_MAIL_INPUT = 354;

    private string $messageBody = '';
    private bool $collectingData = false;

    /** @var string[] */
    private array $recipients = [];

    public function __construct(
        private EventsRepository $events,
        private SwooleConnection $connection
    )
    {
    }

    public function handle(string $data): void
    {
        if (preg_match("/^(EHLO|HELO|MAIL FROM:)/", $data)) {
            $this->send(static::OK);
        } elseif (preg_match("/^RCPT TO:<(.*)>/", $data, $matches)) {
            $this->addRecipient($matches[0]);
            $this->send(static::OK);
        } elseif ($data === "QUIT\r\n") {
            $this->send(static::CLOSING);
        } elseif ($data === "DATA\r\n") {
            $this->collectingData = true;
            $this->send(static::START_MAIL_INPUT);
        } elseif ($this->collectingData) {
            if ($this->endOfContentDetected($data)) {
                $this->addToMessageBody($data);
                $this->send(static::OK);
                $this->dispatchMessage();
                $this->collectingData = false;
            } else {
                $this->addToMessageBody($data);
            }
        }
    }

    public function ready(): void
    {
        $this->collectingData = false;
        $this->send(static::READY, 'mailamie');
    }

    private function endOfContentDetected(string $data): bool
    {
        return (bool)preg_match("/\r\n\.\r\n$/", $data);
    }

    private function dispatchMessage(): void
    {
        $parser = new MailParser();
        $message = $parser->parse($this->messageBody);

        $client = new \WebSocket\Client("ws://127.0.0.1:8000");
        $event = [
            'type' => 'smtp',
            'uuid' => Uuid::uuid4()->toString(),
            'data' => $message->jsonSerialize()
        ];

        $this->events->store($event);
        $client->text(json_encode($event));
        $client->close();
    }

    private function addRecipient(string $recipient): void
    {
        $this->recipients[] = preg_replace('/^RCPT TO:<(.*)>/', '$1', $recipient);
    }

    private function addToMessageBody(string $content): void
    {
        /**
         * Remove double dots from rows start
         * @see https://github.com/micc83/mailamie/issues/13
         */
        $content = preg_replace("/^(\.\.)/m", ".", $content);

        $this->messageBody .= $content;
    }

    private function send(int $statusCode, string $comment = null): void
    {
        $response = implode(" ", array_filter([$statusCode, $comment]));
        $this->connection->send("{$response} \r\n");
    }
}
