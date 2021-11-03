<?php
declare(strict_types=1);

namespace Modules\Ray\Console\Handlers;

use NunoMaduro\Collision\Highlighter;

class ExceptionHandler extends AbstractHandler
{

    protected function makeData(array $payload): array
    {
        $frames = $payload['content']['frames'];
        $editorFrame = reset($frames);

        return [
            'type' => $payload['content']['class'],
            'message' => $payload['content']['message'],
            'trace' => iterator_to_array($this->prepareTrace($frames)),
            'codeSnippet' => $this->renderCodeSnippet($editorFrame),
        ];
    }

    /**
     * Renders the trace of the exception.
     */
    protected function prepareTrace(array $frames): \Generator
    {
        foreach ($frames as $i => $frame) {
            $file = $frame['file_name'];
            $line = $frame['line_number'];
            $class = empty($frame['class']) ? '' : $frame['class'] . '::';
            $function = $frame['method'];
            $pos = str_pad((string)((int)$i + 1), 4, ' ');

            yield $pos => [
                'file' => $file,
                'line' => $line,
                'class' => $class,
                'function' => $function
            ];

            if ($i >= 10) {
                yield $pos => '+ more ' . count($frames) - 10 . ' frames';

                break;
            }
        }
    }

    /**
     * Renders the editor containing the code that was the
     * origin of the exception.
     */
    protected function renderCodeSnippet(array $frame): array
    {
        $file = $frame['file_name'];
        $line = (int)$frame['line_number'];

        $content = '';
        $startLine = $frame['snippet'][0]['line_number'] ?? $line;
        foreach ($frame['snippet'] as $row) {
            $content .= $row['text'] . "\n";
        }

        return [
            'file' => $file,
            'line' => $line,
            'start_line' =>  $startLine,
            'content' => $content
        ];
    }
}
