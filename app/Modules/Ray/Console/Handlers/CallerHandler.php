<?php
declare(strict_types=1);

namespace Modules\Ray\Console\Handlers;

class CallerHandler extends AbstractHandler
{
    protected function makeData(array $payload): array
    {
        $frame = $payload['content']['frame'];

        return [
            'color' => 'red',
            'class' => class_basename($frame['class'] ?? ''),
            'method' => $frame['method'],
            'line' => $frame['line_number']
        ];
    }
}
