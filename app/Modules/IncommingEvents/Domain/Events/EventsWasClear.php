<?php

declare(strict_types=1);

namespace Modules\IncommingEvents\Domain\Events;

use App\Contracts\EventSource\Event;
use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class EventsWasClear implements Event, ShouldBroadcastNow
{
    // TODO: use readonly property
    public function __construct(
        public ?string $type
    ) {
    }

    public function toPayload(): array
    {
        return [
            'type' => $this->type,
        ];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new static(...$payload);
    }

    public function broadcastOn()
    {
        return new Channel('event');
    }

    public function broadcastWith(): array
    {
        return $this->toPayload();
    }
}
