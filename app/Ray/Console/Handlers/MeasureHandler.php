<?php
declare(strict_types=1);

namespace App\Ray\Console\Handlers;

use Symfony\Component\Console\Helper\Helper;

class MeasureHandler extends AbstractHandler
{
    public function handle(array $payload): void
    {
        if ($payload['content']['is_new_timer']) {
            return;
        }

        $this->output->table([], [
            ['Total time', $payload['content']['total_time'] . ' ms'],
            ['Maximum memory usage', Helper::formatMemory((int) $payload['content']['max_memory_usage_during_total_time'])],
            ['Time since last call', Helper::formatMemory((int) $payload['content']['time_since_last_call'])]
        ]);
    }

    public function printTitle(array $payload): void
    {
        parent::printTitle($payload);


        if ($payload['content']['is_new_timer']) {
            $this->output->writeln(' <fg=default;options=bold>Start measuring performance...</>');
        }
    }
}
