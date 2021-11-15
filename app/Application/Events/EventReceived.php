<?php
declare(strict_types=1);

namespace App\Events;

use App\Domain\ValueObjects\Uuid;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Support\Arrayable;

class EventReceived implements ShouldBroadcastNow, Arrayable
{
    public function __construct(
        public Uuid   $uuid,
        public string $type,
        public array  $payload,
        public int    $timestamp,
        public bool   $sendToConsole = false
    )
    {
    }

    public function broadcastWith(): array
    {
        return $this->toArray();
    }

    public function broadcastOn(): Channel
    {
        return new Channel('event');
    }

    public function toArray()
    {
        return [
            'type' => $this->type,
            'data' => $this->payload,
            'uuid' => (string) $this->uuid,
            'timestamp' => $this->timestamp
        ];
    }
}
