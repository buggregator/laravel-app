<?php

declare(strict_types=1);

namespace Modules\Sentry\Console;

use App\Attributes\Console\Stream;
use Generator;
use Interfaces\Console\Handler;
use Termwind\HtmlRenderer;

#[Stream(name: 'sentry')]
class StreamHandler implements Handler
{
    public function __construct(
        private StreamHandlerConfig $config,
        private HtmlRenderer $renderer
    ) {
    }

    public function handle(array $payload): void
    {
        foreach ($payload['payload']['exception']['values'] as $exception) {
            $this->renderException($exception);
        }
    }

    private function renderException(array $exception): void
    {
        $frames = array_reverse($exception['stacktrace']['frames']);
        $editorFrame = reset($frames);

        $this->renderer->render(
            (string) view('sentry::console.output', [
                'date' => date('r'),
                'type' => $exception['type'],
                'message' => $exception['value'],
                'trace' => iterator_to_array($this->prepareTrace($frames)),
                'codeSnippet' => $this->renderCodeSnippet($editorFrame),
            ])
        );
    }

    /**
     * Renders the trace of the exception.
     */
    protected function prepareTrace(array $frames): Generator
    {
        foreach ($frames as $i => $frame) {
            $file = $frame['filename'];
            $line = $frame['lineno'];
            $class = empty($frame['class']) ? '' : $frame['class'].'::';
            $function = $frame['function'] ?? '';
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

    /**
     * Renders the editor containing the code that was the
     * origin of the exception.
     */
    protected function renderCodeSnippet(array $frame): array
    {
        $line = (int) $frame['lineno'];
        $startLine = 0;
        $content = '';
        if (isset($frame['pre_context'])) {
            $startLine = $line - count($frame['pre_context']) + 1;
            foreach ($frame['pre_context'] as $row) {
                $content .= $row."\n";
            }
        }

        if (isset($frame['context_line'])) {
            $content .= $frame['context_line']."\n";
        }

        if (isset($frame['post_context'])) {
            foreach ($frame['post_context'] as $row) {
                $content .= $row."\n";
            }
        }

        return [
            'file' => $frame['filename'],
            'line' => $line,
            'start_line' => $startLine,
            'content' => $content,
        ];
    }

    public function shouldBeSkipped(array $payload): bool
    {
        if (! $this->config->isEnabled()) {
            return true;
        }

        return empty($payload['payload']['exception']['values']);
    }
}
