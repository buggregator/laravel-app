<?php
declare(strict_types=1);

namespace Modules\Ray\Console\Handlers;

use Symfony\Component\Console\Helper\Helper;

class MeasureHandler extends AbstractHandler
{
    protected function makeData(array $payload): array
    {
        return [
            'isNew' => $payload['content']['is_new_timer'],
            'name' => $payload['content']['name'],
            'totalTime' => $this->covertMsToSeconds($payload['content']['total_time']),
            'memoryUsage' => Helper::formatMemory((int)$payload['content']['max_memory_usage_during_total_time']),
            'timeSinceLastCall' => $this->covertMsToSeconds($payload['content']['time_since_last_call']),
            'memoryUsageSinceLastCall' => Helper::formatMemory((int)$payload['content']['max_memory_usage_since_last_call']),
        ];
    }

    private function covertMsToSeconds(int|float $ms): string
    {
        return number_format($ms / 1000, 4);
    }
}
