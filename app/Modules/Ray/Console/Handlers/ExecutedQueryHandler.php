<?php
declare(strict_types=1);

namespace Modules\Ray\Console\Handlers;

class ExecutedQueryHandler extends AbstractHandler
{
    public function handle(array $payload): void
    {
        $query = vsprintf(str_replace('?', '%s', $payload['content']['sql']), collect($payload['content']['bindings'])->map(function ($binding) {
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray());

        $this->output->writeln(sprintf('  <comment>%s</comment>', $query));
        $this->output->newline();

        $data = [];

        if (isset($payload['content']['time'])) {
            $data[] = ['Time', $payload['content']['time'] . ' ms'];
        }

        if (isset($payload['content']['connection_name'])) {
            $data[] = ['Connection', $payload['content']['connection_name']];
        }

        $this->output->table([], $data);
    }
}
