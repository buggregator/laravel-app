<?php
declare(strict_types=1);

namespace Modules\Ray\Console\Handlers;

class CarbonHandler extends AbstractHandler
{
    protected function makeData(array $payload): array
    {
        return [
            'color' => 'magenta',
            'data' => [
                'Formatted' => $payload['content']['formatted'],
                'Timezone' => $payload['content']['timezone'],
                'Timestamp' => $payload['content']['timestamp']
            ]
        ];
    }
}
