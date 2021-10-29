<?php
declare(strict_types=1);

namespace Modules\Sentry\Console;

use App\Attributes\Console\Stream;
use Interfaces\Console\Handler;
use Termwind\HtmlRenderer;

#[Stream(name: 'sentry')]
class StreamHandler implements Handler
{
    public function __construct(
        private StreamHandlerConfig $config,
        private HtmlRenderer $renderer
    ){
    }

    public function handle(array $payload): void
    {
        foreach ($payload['data']['exception']['values'] as $exception) {
            $this->renderException($exception);
        }
    }

    private function renderException(array $exception): void
    {
        $frames = array_reverse($exception['stacktrace']['frames']);
        $editorFrame = array_shift($frames);

        $this->renderer->render(
            (string) view('sentry::console.output', [
                'date' => date('r'),
                'type' => $exception['type'],
                'message' => $exception['value'],
                'trace' => iterator_to_array($this->prepareTrace($frames)),
                'codeSnippet' => $this->renderCodeSnippet($editorFrame),
                'line' => (int)$editorFrame['lineno']
            ])
        );
    }

    /**
     * Renders the trace of the exception.
     */
    protected function prepareTrace(array $frames): \Generator
    {
        foreach ($frames as $i => $frame) {
            $file = $frame['filename'];
            $line = $frame['lineno'];
            $class = empty($frame['class']) ? '' : $frame['class'] . '::';
            $function = $frame['function'] ?? '';
            $pos = ((int) $i + 1);

            yield $pos => ['file' => $file, 'line' => $line, 'class' => $class, 'function' => $function];
        }
    }

    /**
     * Renders the editor containing the code that was the
     * origin of the exception.
     */
    protected function renderCodeSnippet(array $frame): string
    {
        $content = "<?php" . str_repeat("\n", $frame['lineno'] - count($frame['pre_context']) - 1);
        foreach ($frame['pre_context'] as $row) {
            $content .= $row . "\n";
        }
        $content .= $frame['context_line'] . "\n";
        foreach ($frame['post_context'] as $row) {
            $content .= $row . "\n";
        }

        return $content;
    }

    public function shouldBeSkipped(array $payload): bool
    {
        if (!$this->config->isEnabled()) {
            return true;
        }

        return !isset($payload['data']['exception']['values'][0]);
    }
}
