<?php
declare(strict_types=1);

namespace App\Ray\Console\Handlers;

class CallerHandler extends AbstractHandler
{
    public function handle(array $payload): void
    {
        $frame = $payload['content']['frame'];

        $this->output->table([], [
            ['Source', sprintf('%s on line %s', class_basename($frame['file_name']), $frame['line_number'])],
            ['Class', $frame['class'] ?? ''],
            ['Method', $frame['method']],
            ['File', $frame['file_name']]
        ]);
    }
}
