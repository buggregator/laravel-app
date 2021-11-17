<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use Cycle\Database\DatabaseInterface;

final class Json
{
    public function __construct(private array $data = [])
    {
    }

    public function __toString(): string
    {
        return json_encode($this->data);
    }

    public static function cast(string $value, DatabaseInterface $db): self
    {
        return new static(json_decode($value, true));
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
