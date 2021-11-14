<?php
declare(strict_types=1);

namespace App\Domain\ValueObjects;

use EventSauce\EventSourcing\AggregateRootId;
use Ramsey\Uuid\UuidInterface;

final class Uuid implements AggregateRootId
{
    public static function generate(): static
    {
        return new static();
    }

    public function __construct(private ?UuidInterface $uuid = null)
    {
        if (!$uuid) {
            $this->uuid = \Ramsey\Uuid\Uuid::uuid4();
        }
    }

    public function toObject(): UuidInterface
    {
        return $this->uuid;
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }

    public static function fromString(string $aggregateRootId): AggregateRootId
    {
        return new static(\Ramsey\Uuid\Uuid::fromString($aggregateRootId));
    }
}
