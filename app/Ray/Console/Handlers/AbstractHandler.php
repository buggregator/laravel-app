<?php
declare(strict_types=1);

namespace App\Ray\Console\Handlers;

use App\Console\Handler;
use NunoMaduro\Collision\ConsoleColor;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractHandler implements Handler
{
    protected ConsoleColor $color;

    public function __construct(protected OutputInterface $output)
    {
        $this->color = new ConsoleColor();
    }

    public function printTitle(array $payload): void
    {
        $title = sprintf(
            ' <fg=white;bg=green;options=bold> %s </><fg=white;bg=black;options=bold> %s </>',
            'RAY',
            ucfirst(str_replace(['-', '_'], ' ', $payload['type']))
        );

        if (isset($payload['content']['label'])) {
            $title .= sprintf(' <fg=white;bg=blue> %s </>  ', $payload['content']['label']);
        }

        $this->output->newline();
        $this->output->writeln($title);
    }

    public function printContext(array $payload): void
    {
        $handler = new ContextHandler($this->output);
        if (!$handler->shouldBeSkipped($payload)) {
            $handler->handle($payload);
        }
    }

    public function shouldBeSkipped(array $payload): bool
    {
        return false;
    }
}
