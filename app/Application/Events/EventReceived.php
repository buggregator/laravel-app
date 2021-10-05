<?php
declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class EventReceived extends ShouldBeStored implements ShouldBroadcastNow
{
    public function __construct(public array $payload, public bool $sendToConsole = false)
    {
    }

    public function broadcastWith(): array
    {
        return $this->payload;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('event');
    }
}
