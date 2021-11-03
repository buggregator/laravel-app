<?php
declare(strict_types=1);

namespace Modules\Ray\Contracts;

interface EventHandler
{
    public function handle(array $event): array;
}
