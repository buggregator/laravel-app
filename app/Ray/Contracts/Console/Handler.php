<?php
declare(strict_types=1);

namespace App\Ray\Contracts\Console;

interface Handler
{
    public function handle(array $payload): void;
    public function shouldBeSkipped(array $payload): bool;
}
