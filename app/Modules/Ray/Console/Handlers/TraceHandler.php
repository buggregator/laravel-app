<?php
declare(strict_types=1);

namespace Modules\Ray\Console\Handlers;

use Illuminate\Support\Arr;

class TraceHandler extends AbstractHandler
{
    public function handle(array $payload): void
    {
        $frames = $payload['content']['frames'];
        array_shift($frames);

        $this->renderTrace($frames);
    }

    public function printTitle(array $payload): void
    {
        parent::printTitle($payload);

        $frame = Arr::first($payload['content']['frames']);

        if ($frame) {
            $class = empty($frame['class']) ? '' : $frame['class'] . '::';
            $function = $frame['method'];

            $this->output->writeln(sprintf(
                ' <fg=default;options=bold>%s:%s</> on line %s',
                $class,
                $function,
                $frame['line_number']
            ));
            $this->output->writeln(sprintf(' %s', $this->color->apply('dark_gray', $frame['file_name'])));

            $this->output->newline();
        }
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

            $this->output->writeln("  <fg=yellow>$pos</><fg=default;options=bold>$file</>:<fg=default;options=bold>$line</>");
            $this->output->writeln("  <fg=white>    $class$function</>");
            $this->output->newline();
        }
    }
}
