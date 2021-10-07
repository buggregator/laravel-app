<?php
declare(strict_types=1);

namespace App\Contracts;

use Ramsey\Uuid\UuidInterface;

interface EventsRepository
{
    public function find(UuidInterface $uuid): ?array;

    public function store(array $event): string;

    public function delete(UuidInterface $uuid): void;

    public function all(string ...$type): array;

    public function clear(): void;
}
