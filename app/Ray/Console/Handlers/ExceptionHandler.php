<?php
declare(strict_types=1);

namespace App\Ray\Console\Handlers;

use NunoMaduro\Collision\Highlighter;

class ExceptionHandler extends AbstractHandler
{
    public function handle(array $payload): void
    {
        $data = [];

        $frames = $payload['content']['frames'];
        $editorFrame = array_shift($frames);

        $this->renderEditor($editorFrame);
        $this->renderTrace($frames);

        $this->output->writeln(implode("\n", $data));
    }

    public function printTitle(array $payload): void
    {
        parent::printTitle($payload);

        $this->output->writeln(sprintf('  <error> %s </error>  ', $payload['content']['class']));
        $this->output->newline();
        $this->output->writeln("<fg=default;options=bold>  {$payload['content']['message']}</>");
        $this->output->newline();
    }

    /**
     * Renders the trace of the exception.
     */
    protected function renderTrace(array $frames): void
    {
        foreach ($frames as $i => $frame) {
            $file = $frame['file_name'];
            $line = $frame['line_number'];
            $class = empty($frame['class']) ? '' : $frame['class'] . '::';
            $function = $frame['method'];
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

        $file = $frame['file_name'];
        $line = (int)$frame['line_number'];

        $this->render('at <fg=green>' . $file . '</>' . ':<fg=green>' . $line . '</>');

        $content = "<?php" . str_repeat("\n", $frame['snippet'][0]['line_number'] - 1);
        foreach ($frame['snippet'] as $row) {
            $content .= $row['text'] . "\n";
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
}
