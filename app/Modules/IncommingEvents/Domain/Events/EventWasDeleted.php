<?php

declare(strict_types=1);

namespace Modules\IncommingEvents\Domain\Events;

use App\Contracts\EventSource\Event;
use App\Domain\ValueObjects\Uuid;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

class EventWasDeleted implements Event
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
}
