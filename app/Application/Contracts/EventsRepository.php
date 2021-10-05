<?php
declare(strict_types=1);

namespace App\Contracts;

interface EventsRepository
{
    public function find(string $uuid): ?array;

    public function store(array $event): string|int;

    public function delete(string $uuid): void;

    public function all(string ...$type): array;

    public function clear(): void;
}
