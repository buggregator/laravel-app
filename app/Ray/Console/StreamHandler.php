<?php
declare(strict_types=1);

namespace App\Ray\Console;

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
use App\Ray\Contracts\Console\Handler;
use Symfony\Component\Console\Output\OutputInterface;

class StreamHandler implements Handler
{
    private array $payloadHandlers = [
        'log' => LogHandler::class,
        'table' => TableHandler::class,
        'caller' => CallerHandler::class,
        'trace' => TraceHandler::class,
        'custom' => CustomHandler::class,
        'exception' => ExceptionHandler::class,
        'measure' => MeasureHandler::class,
        'json_string' => JsonHandler::class,
        'carbon' => CarbonHandler::class,
        'eloquent_model' => EloquentModelHandler::class,
        'executed_query' => ExecutedQueryHandler::class,
        'job_event' => JobEventHandler::class,
        'event' => EventHandler::class,
        'view' => ViewHandler::class,
    ];

    public function __construct(private OutputInterface $output)
    {
    }

    public function handle(array $stream): void
    {
        if (!isset($stream['data']['payloads'])) {
            return;
        }

        foreach ($stream['data']['payloads'] as $payload) {
            if (!isset($this->payloadHandlers[$payload['type']])) {
                $handler = new DebugHandler($this->output);
            } else {
                $handler = $this->payloadHandlers[$payload['type']];
                $handler = new $handler($this->output);
            }

            if ($handler->shouldBeSkipped($payload)) {
                continue;
            }

            $handler->printTitle($payload);
            $handler->handle($payload);
            $handler->printContext($payload);
        }
    }

    public function shouldBeSkipped(array $stream): bool
    {
        return false;
    }
}
