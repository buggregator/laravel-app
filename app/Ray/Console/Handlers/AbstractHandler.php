<?php
declare(strict_types=1);

namespace App\Ray\Console\Handlers;

use App\Ray\Contracts\Console\Handler;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractHandler implements Handler
{
    public function __construct(protected OutputInterface $output)
    {
    }

    public function printTitle(array $payload): void
    {
        $this->output->info(
            ucfirst(str_replace(['-', '_'], ' ', $payload['type']))
        );
    }

    public function printContext(array $payload): void
    {
        if (isset($payload['origin'])) {
            (new ContextHandler($this->output))->handle($payload);
        }
    }
}
