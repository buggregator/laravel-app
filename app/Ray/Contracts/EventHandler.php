<?php
declare(strict_types=1);

namespace App\Ray\Contracts;

interface EventHandler
{
    public function handle(array $event): array;
}
