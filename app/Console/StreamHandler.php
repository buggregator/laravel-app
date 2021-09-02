<?php
declare(strict_types=1);

namespace App\Console;

use Symfony\Component\Console\Output\OutputInterface;

class StreamHandler
{
    private array $handlers = [
        'ray' => \App\Ray\Console\StreamHandler::class,
        'sentry' => \App\Sentry\Console\StreamHandler::class
    ];

    public function __construct(private OutputInterface $output)
    {
    }

    public function __invoke(array $stream, $verbosity = null): void
    {
        if (!isset($stream['type'])) {
            return;
        }

        if (isset($this->handlers[$stream['type']])) {

            try {
                $handler = $this->handlers[$stream['type']];
                (new $handler($this->output))->handle($stream);
            } catch (\Throwable $e) {
                $this->output->error($e);
            }
        }
    }
}
