<?php
declare(strict_types=1);

namespace App\Ray\Console\Handlers;

class CallerHandler extends AbstractHandler
{
    public function handle(array $payload): void
    {
    }

    public function printTitle(array $payload): void
    {
        parent::printTitle($payload);

        $frame = $payload['content']['frame'];

        $this->output->writeln(sprintf(
            ' <fg=default;options=bold>%s:%s</> on line %s',
            class_basename($frame['class'] ?? ''),
            $frame['method'],
            $frame['line_number']
        ));
        $this->output->writeln(sprintf(' %s', $this->color->apply('dark_gray', $frame['file_name'])));
    }
}
