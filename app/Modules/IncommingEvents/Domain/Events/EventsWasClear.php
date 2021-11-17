<?php

declare(strict_types=1);

namespace Modules\IncommingEvents\Domain\Events;

use App\Contracts\EventSource\Event;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

class EventsWasClear implements Event
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
}
