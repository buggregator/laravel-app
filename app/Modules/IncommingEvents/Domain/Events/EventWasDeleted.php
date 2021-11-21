<?php

declare(strict_types=1);

namespace Modules\IncommingEvents\Domain\Events;

use App\Contracts\EventSource\Event;
use App\Domain\ValueObjects\Uuid;
use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class EventWasDeleted implements Event, ShouldBroadcastNow
{
    // TODO: use readonly property
    public function __construct(
        public Uuid $uuid
    ) {
    }

    public function toPayload(): array
    {
        return [
            'uuid' => (string) $this->uuid,
        ];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        $payload['uuid'] = Uuid::fromString($payload['uuid']);

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
