<?php

declare(strict_types=1);

namespace Interfaces\Console;

interface Handler
{
    public function handle(array $payload): void;

    public function shouldBeSkipped(array $payload): bool;
}
