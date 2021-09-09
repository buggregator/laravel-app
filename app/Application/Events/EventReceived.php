<?php
declare(strict_types=1);

namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class EventReceived implements ShouldBroadcastNow
{
    public function __construct(public array $payload, public bool $sendToConsole = false)
    {
    }

    public function broadcastWith(): array
    {
        return $this->payload;
    }

    public function broadcastOn(): string
    {
        return 'event';
    }
}
