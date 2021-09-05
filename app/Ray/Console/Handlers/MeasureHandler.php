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
            ['Total time', $this->covertMsToSeconds($payload['content']['total_time']) . ' s'],
            ['Maximum memory usage', Helper::formatMemory((int)$payload['content']['max_memory_usage_during_total_time'])],
            ['Time since last call', $this->covertMsToSeconds($payload['content']['time_since_last_call']) . ' s']
        ]);
    }

    public function printTitle(array $payload): void
    {
        parent::printTitle($payload);


        if ($payload['content']['is_new_timer']) {
            $this->output->writeln(' <fg=default;options=bold>Start measuring performance...</>');
        }
    }

    private function covertMsToSeconds(int|float $ms): string
    {
        return number_format($ms / 1000, 4);
    }
}
