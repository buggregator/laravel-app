<?php
declare(strict_types=1);

namespace Modules\Ray\Console\Handlers;

class DebugHandler extends AbstractHandler
{

    protected function makeData(array $payload): array
    {
        return [];
    }
    public function handle(array $payload): void
    {
        dump($payload);
    }
}
