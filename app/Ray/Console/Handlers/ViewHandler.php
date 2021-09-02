<?php
declare(strict_types=1);

namespace App\Ray\Console\Handlers;

use App\Ray\Console\VariableCleaner;

class ViewHandler extends AbstractHandler
{
    public function handle(array $payload): void
    {
        $this->output->writeln(
            VariableCleaner::clean($payload['content']['data'], 0)
        );
    }

    public function printTitle(array $payload): void
    {
        parent::printTitle($payload);

        $this->output->writeln(sprintf(' <error> %s </error>  ', $payload['content']['view_path_relative_to_project_root']));
        $this->output->newline();
    }
}
