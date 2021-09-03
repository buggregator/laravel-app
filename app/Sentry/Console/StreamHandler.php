<?php
declare(strict_types=1);

namespace App\Sentry\Console;

use App\Attributes\Console\Stream;
use App\Console\Handler;
use NunoMaduro\Collision\Highlighter;
use Symfony\Component\Console\Output\OutputInterface;

#[Stream(name: 'sentry')]
class StreamHandler implements Handler
{
    public function __construct(private OutputInterface $output)
    {
    }

    public function handle(array $payload): void
    {
        foreach ($payload['data']['exception']['values'] as $exception) {
            $this->renderException($exception);
        }
    }

    /**
     * Renders the trace of the exception.
     */
    protected function renderTrace(array $frames): void
    {
        foreach ($frames as $i => $frame) {
            $file = $frame['filename'];
            $line = $frame['lineno'];
            $class = empty($frame['class']) ? '' : $frame['class'] . '::';
            $function = $frame['function'] ?? '';
            $pos = str_pad((string)((int)$i + 1), 4, ' ');

            $this->render("<fg=yellow>$pos</><fg=default;options=bold>$file</>:<fg=default;options=bold>$line</>");
            $this->render("<fg=white>    $class$function</>", false);
        }
    }

    /**
     * Renders the editor containing the code that was the
     * origin of the exception.
     */
    protected function renderEditor(array $frame): void
    {
        $highlighter = new Highlighter();

        $file = $frame['filename'];
        $line = (int)$frame['lineno'];

        $this->render('at <fg=green>' . $file . '</>' . ':<fg=green>' . $line . '</>');

        $content = "<?php" . str_repeat("\n", $frame['lineno'] - count($frame['pre_context']) - 1);
        foreach ($frame['pre_context'] as $row) {
            $content .= $row . "\n";
        }
        $content .= $frame['context_line'] . "\n";
        foreach ($frame['post_context'] as $row) {
            $content .= $row . "\n";
        }

        $this->output->writeln(
            $highlighter->highlight($content, $line)
        );
    }

    /**
     * Renders an message into the console.
     */
    protected function render(string $message, bool $break = true): void
    {
        if ($break) {
            $this->output->newline();
        }

        $this->output->writeln("  $message");
    }

    public function shouldBeSkipped(array $payload): bool
    {
        return !isset($payload['data']['exception']['values'][0]);
    }

    private function renderException(array $exception): void
    {
        $this->output->table([], [
            ['date', date('r')],
            ['class', $exception['type']],
        ]);

        $this->output->writeln(sprintf(
            '  <fg=white;bg=green;options=bold> %s </>',
            'Sentry'
        ));

        $this->output->newline();

        $this->output->writeln(sprintf('  <error> %s </error>  ', $exception['type']));
        $this->output->newline();
        $this->output->writeln("<fg=default;options=bold>  {$exception['value']}</>");
        $this->output->newline();

        $data = [];

        $frames = array_reverse($exception['stacktrace']['frames']);
        $editorFrame = array_shift($frames);

        $this->renderEditor($editorFrame);
        $this->renderTrace($frames);

        $this->output->writeln(implode("\n", $data));
    }
}
