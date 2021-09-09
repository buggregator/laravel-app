<?php
declare(strict_types=1);

namespace Modules\Ray\Console\Handlers;

class DebugHandler extends AbstractHandler
{
    public function handle(array $payload): void
    {
        dump($payload);
    }
}
