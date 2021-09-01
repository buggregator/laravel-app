<?php
declare(strict_types=1);

namespace App\Ray\Console\Handlers;

use App\Ray\Console\VariableCleaner;

class EloquentModelHandler extends AbstractHandler
{
    public function handle(array $payload): void
    {
        if (empty($payload['content'])) {
            $this->output->writeln('Empty data.');
            return;
        }

        $this->output->table([], [
            ['Class', $payload['content']['class_name']],
            ['Attributes', VariableCleaner::clean($payload['content']['attributes'])]
        ]);
    }
}
