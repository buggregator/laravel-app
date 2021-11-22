<?php

declare(strict_types=1);

namespace Modules\IncommingEvents\Domain\Events;

use App\Contracts\EventSource\Event;
use App\Domain\ValueObjects\Uuid;
use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

final class EventWasReceived implements Event, ShouldBroadcastNow
{
    // TODO: use readonly property
    public function __construct(
        public Uuid $uuid,
        public string $type,
        public array $payload,
        public int $timestamp,
        public bool $sendToConsole = false
    ) {
    }

    public function toPayload(): array
    {
        return [
            'uuid' => (string) $this->uuid,
            'type' => $this->type,
            'payload' => $this->payload,
            'timestamp' => $this->timestamp,
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
