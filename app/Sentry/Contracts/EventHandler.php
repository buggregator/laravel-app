<?php
declare(strict_types=1);

namespace App\Sentry\Contracts;

interface EventHandler
{
    public function handle(array $event): array;
}
