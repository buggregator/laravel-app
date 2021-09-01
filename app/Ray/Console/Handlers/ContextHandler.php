<?php
declare(strict_types=1);

namespace App\Ray\Console\Handlers;


class ContextHandler extends AbstractHandler
{
    public function handle(array $payload): void
    {
        if (!isset($payload['origin'])) {
            return;
        }

        $this->output->table([], [
            ['date', date('r')],
            ['source', sprintf('%s on line %s', class_basename($payload['origin']['file']), $payload['origin']['line_number'])],
            ['file', $payload['origin']['file']]
        ]);
    }

    public function printTitle(array $payload): void
    {
    }
}
