<?php
declare(strict_types=1);

namespace App\Ray\Console\Handlers;

class TraceHandler extends AbstractHandler
{
    public function handle(array $payload): void
    {
        $data = [];

        foreach ($payload['content']['frames'] as $i => $frame) {
            $data[] = [($i + 1) . '.', sprintf('%s:%s on line %s', $frame['class'] ?? 'null', $frame['method'], $frame['line_number'])];
            $data[] = ['', $frame['file_name']];
        }

        $this->output->table([], $data);
    }
}
