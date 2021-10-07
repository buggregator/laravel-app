<?php
declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class EventReceived extends ShouldBeStored implements ShouldBroadcastNow, Arrayable
{
    public function __construct(
        public string $type,
        public array  $payload,
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
            'uuid' => Str::uuid()->toString(),
            'timestamp' => time()
        ];
    }
}
