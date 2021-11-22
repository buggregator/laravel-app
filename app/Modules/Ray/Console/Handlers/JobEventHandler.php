<?php

declare(strict_types=1);

namespace Modules\Ray\Console\Handlers;

use Modules\Ray\Console\VariableCleaner;

class JobEventHandler extends AbstractHandler
{
    protected function makeData(array $payload): array
    {
        return [
            'event' => $payload['content']['event_name'],
            'job' => VariableCleaner::clean($payload['content']['job'], 0),
        ];
    }
}
