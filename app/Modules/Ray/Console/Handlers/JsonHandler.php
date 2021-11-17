<?php

declare(strict_types=1);

namespace Modules\Ray\Console\Handlers;

class JsonHandler extends AbstractHandler
{
    protected function makeData(array $payload): array
    {
        return [
            'json' => json_encode(json_decode($payload['content']['value']), JSON_PRETTY_PRINT),
        ];
    }
}
