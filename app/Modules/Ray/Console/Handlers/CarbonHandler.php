<?php
declare(strict_types=1);

namespace Modules\Ray\Console\Handlers;

class CarbonHandler extends AbstractHandler
{
    public function handle(array $payload): void
    {
        $this->output->table([], [
            ['Formatted', $payload['content']['formatted']],
            ['Timezone', $payload['content']['timezone']],
            ['Timestamp', $payload['content']['timestamp']]
        ]);
    }
}
