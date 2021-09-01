<?php
declare(strict_types=1);

namespace App\Ray\Console\Handlers;

class DebugHandler extends AbstractHandler
{
    public function handle(array $payload): void
    {
        dump($payload);
    }
}
