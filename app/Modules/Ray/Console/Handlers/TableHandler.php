<?php
declare(strict_types=1);

namespace Modules\Ray\Console\Handlers;

use Modules\Ray\Console\VariableCleaner;

class TableHandler extends AbstractHandler
{
    public function handle(array $payload): void
    {
        $this->output->table(['key', 'value'], collect((array)$payload['content']['values'])->map(function ($value, $key) {
            return [$key, VariableCleaner::clean($value)];
        })->toArray());
    }
}
