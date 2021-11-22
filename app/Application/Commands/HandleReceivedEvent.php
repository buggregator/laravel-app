<?php

declare(strict_types=1);

namespace App\Commands;

use App\Contracts\Command\Command;
use App\Domain\ValueObjects\Uuid;

final class HandleReceivedEvent implements Command
{
    public Uuid $uuid;
    public int $timestamp;

    public function __construct(
        public string $type,
        public array $payload,
        public bool $sendToConsole = false
    ) {
        $this->uuid = Uuid::generate();
        $this->timestamp = time();
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'payload' => $this->payload,
            'uuid' => (string) $this->uuid,
            'timestamp' => $this->timestamp,
        ];
    }
}
