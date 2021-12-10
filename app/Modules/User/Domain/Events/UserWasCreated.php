<?php

declare(strict_types=1);

namespace Modules\User\Domain\Events;

use App\Contracts\EventSource\Event;
use App\Domain\ValueObjects\Uuid;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class UserWasCreated implements Event
{

    public function __construct(
        public Uuid $uuid,
        public string $username,
        public string $password,
    ) {
    }

    public function toPayload(): array
    {
        return [
            'uuid' => (string)$this->uuid,
            'username' => $this->username,
            'password' => $this->password,
        ];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        $payload['uuid'] = Uuid::fromString($payload['uuid']);

        return new static(...$payload);
    }
}
