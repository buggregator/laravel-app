<?php
declare(strict_types=1);

namespace App\Ray\Console;

use App\Attributes\Console\Stream;
use App\Console\Handler;
use App\Ray\Console\Handlers\CallerHandler;
use App\Ray\Console\Handlers\CarbonHandler;
use App\Ray\Console\Handlers\CustomHandler;
use App\Ray\Console\Handlers\DebugHandler;
use App\Ray\Console\Handlers\EloquentModelHandler;
use App\Ray\Console\Handlers\EventHandler;
use App\Ray\Console\Handlers\ExceptionHandler;
use App\Ray\Console\Handlers\ExecutedQueryHandler;
use App\Ray\Console\Handlers\JobEventHandler;
use App\Ray\Console\Handlers\JsonHandler;
use App\Ray\Console\Handlers\LogHandler;
use App\Ray\Console\Handlers\MeasureHandler;
use App\Ray\Console\Handlers\TableHandler;
use App\Ray\Console\Handlers\TraceHandler;
use App\Ray\Console\Handlers\ViewHandler;
use Symfony\Component\Console\Output\OutputInterface;

#[Stream(name: 'ray')]
class StreamHandler implements Handler
{
    private array $payloadHandlers = [];

    public function __construct(
        private StreamHandlerConfig $config,
        private OutputInterface     $output
    )
    {
        $this->payloadHandlers = $config->getHandlers();
    }

    public function handle(array $stream): void
    {
        foreach ($stream['data']['payloads'] as $payload) {
            if (!isset($this->payloadHandlers[$payload['type']])) {
                continue;

                $handler = new DebugHandler($this->output);
            } else {
                $handler = $this->payloadHandlers[$payload['type']];
                $handler = new $handler($this->output);
            }

            if ($handler->shouldBeSkipped($payload)) {
                continue;
            }

            $handler->printContext($payload);
            $handler->printTitle($payload);
            $handler->handle($payload);
        }
    }

    public function shouldBeSkipped(array $stream): bool
    {
        if (!$this->config->isEnabled()) {
            return true;
        }

        return !isset($stream['data']['payloads']);
    }
}
