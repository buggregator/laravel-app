<?php
declare(strict_types=1);

namespace App\Ray\Console\Handlers;

class MeasureHandler extends AbstractHandler
{
    public function handle(array $payload): void
    {
        if ($payload['content']['is_new_timer']) {
            return;
        }

        $this->output->table([], [
            ['Total time', $payload['content']['total_time']],
            ['Maximum memory usage', $payload['content']['max_memory_usage_during_total_time']],
            ['Time since last call', $payload['content']['time_since_last_call']]
        ]);
    }

    public function printTitle(array $payload): void
    {
        if ($payload['content']['is_new_timer']) {
            $this->output->info('Start measuring performance...');
            return;
        }

        parent::printTitle($payload);
    }
}
