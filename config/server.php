<?php

use App\Ray\Console\Handlers\CallerHandler;
use App\Ray\Console\Handlers\CarbonHandler;
use App\Ray\Console\Handlers\CustomHandler;
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

return [
    'ray' => [
        'cli' => [
            'enabled' => (bool)env('CLI_RAY_STREAM', true),
            'handlers' => [
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
            ]
        ]
    ],
    'sentry' => [
        'cli' => [
            'enabled' => (bool)env('CLI_SENTRY_STREAM', true),
        ]
    ],
    'smtp' => [
        'cli' => [
            'enabled' => (bool)env('CLI_SMTP_STREAM', true),
        ]
    ],
    'var-dumper' => [
        'cli' => [
            'enabled' => (bool)env('CLI_VAR_DUMPER_STREAM', true),
        ]
    ]
];
