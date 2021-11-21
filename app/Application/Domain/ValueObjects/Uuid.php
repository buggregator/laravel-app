<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

use EventSauce\EventSourcing\AggregateRootId;
use Ramsey\Uuid\UuidInterface;
use Stringable;

final class Uuid implements AggregateRootId, Stringable
{
    public static function generate(): static
    {
        return new static();
    }

    public function __construct(private ?UuidInterface $uuid = null)
    {
        if (! $uuid) {
            // TODO use uuid with timestamp support
            $this->uuid = \Ramsey\Uuid\Uuid::uuid4();
        }
    }

    public function equals(self $uuid): bool
    {
        return $this->uuid->equals($uuid->uuid);
    }

    public function toObject(): UuidInterface
    {
        return $this->uuid;
    }

    public function toString(): string
    {
        return (string) $this;
    }

    public static function fromString(string $aggregateRootId): AggregateRootId
    {
        return new static(\Ramsey\Uuid\Uuid::fromString($aggregateRootId));
    }

    public function __toString()
    {
        return $this->uuid->toString();
    }
}
