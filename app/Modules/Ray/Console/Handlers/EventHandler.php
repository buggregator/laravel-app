<?php
declare(strict_types=1);

namespace Modules\Ray\Console\Handlers;

use Modules\Ray\Console\VariableCleaner;

class EventHandler extends AbstractHandler
{
    protected function makeData(array $payload): array
    {
        return [
            'event' => isset($payload['content']['event']) ? VariableCleaner::clean($payload['content']['event'], 0) : null,
            'payload' => isset($payload['content']['payload']) ? VariableCleaner::clean($payload['content']['payload'], 0) : null,
        ];
    }

    public function printTitle(array $payload): void
    {
        parent::printTitle($payload);

        $this->output->writeln(sprintf(' <error> %s </error>  ', $payload['content']['name']));
        $this->output->newline();
    }
}
