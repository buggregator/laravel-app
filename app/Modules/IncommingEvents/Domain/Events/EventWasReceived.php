<?php
declare(strict_types=1);

namespace Modules\IncommingEvents\Domain\Events;

use App\Domain\ValueObjects\Uuid;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class EventWasReceived implements SerializablePayload
{
    // TODO: use readonly property
    public function __construct(
        public Uuid   $uuid,
        public string $type,
        public array  $payload,
        public int    $timestamp
    ) {
    }

    public function toPayload(): array
    {
        return [
            'uuid' => (string) $this->uuid,
            'type' => $this->type,
            'payload' => $this->payload,
            'timestamp' => $this->timestamp
        ];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        $payload['uuid'] = Uuid::fromString($payload['uuid']);

        return new static(...$payload);
    }
}
