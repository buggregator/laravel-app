<?php
declare(strict_types=1);

namespace App\Contracts;

interface EventsRepository
{
    public function store(array $event): void;
    public function delete(string $uuid): void;
    public function all(): array;
    public function clear(): void;
}
