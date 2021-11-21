<?php

declare(strict_types=1);

namespace Modules\Ray\Console\Handlers;

use Generator;
use Illuminate\Support\Arr;

class TraceHandler extends AbstractHandler
{
    protected function makeData(array $payload): array
    {
        $frames = $payload['content']['frames'];
        $frame = Arr::first($payload['content']['frames']);

        $frame = $frame ? [
            'class' => empty($frame['class']) ? '' : $frame['class'].'::',
            'method' => $frame['method'],
            'line' => $frame['line_number'],
            'file' => $frame['file_name'],
        ] : null;

        return [
            'frame' => $frame,
            'trace' => iterator_to_array($this->prepareTrace($frames)),
        ];
    }

    /**
     * Renders the trace of the exception.
     */
    protected function prepareTrace(array $frames): Generator
    {
        foreach ($frames as $i => $frame) {
            $file = $frame['file_name'];
            $line = $frame['line_number'];
            $class = empty($frame['class']) ? '' : $frame['class'].'::';
            $function = $frame['method'];
            $pos = str_pad((string) ((int) $i + 1), 4, ' ');

            yield $pos => [
                'file' => $file,
                'line' => $line,
                'class' => $class,
                'function' => $function,
            ];

            if ($i >= 10) {
                yield $pos => '+ more '.count($frames) - 10 .' frames';

                break;
            }
        }
    }
}
