<?php

use Modules\Ray\Console\Handlers;

return [
    'ray' => [
        'http_check_status' => (int) env('HTTP_CHECK_STATUS', 200),
        'cli' => [
            'enabled' => (bool) env('CLI_RAY_STREAM', true),
            'handlers' => [
                'log' => Handlers\LogHandler::class,
                'table' => Handlers\TableHandler::class,
                'caller' => Handlers\CallerHandler::class,
                'trace' => Handlers\TraceHandler::class,
                'custom' => Handlers\CustomHandler::class,
                'exception' => Handlers\ExceptionHandler::class,
                'measure' => Handlers\MeasureHandler::class,
                'json_string' => Handlers\JsonHandler::class,
                'carbon' => Handlers\CarbonHandler::class,
                'eloquent_model' => Handlers\EloquentModelHandler::class,
                'executed_query' => Handlers\ExecutedQueryHandler::class,
                'job_event' => Handlers\JobEventHandler::class,
                'event' => Handlers\EventHandler::class,
                'view' => Handlers\ViewHandler::class,
                'notify' => Handlers\NotifyHandler::class,
            ],
        ],
    ],
    'sentry' => [
        'http' => [
            'index' => 'Sentry/Index',
            'show' => 'Sentry/Show',
        ],
        'cli' => [
            'enabled' => (bool) env('CLI_SENTRY_STREAM', true),
        ],
    ],
    'smtp' => [
        'http' => [
            'index' => 'Smtp/Index',
            'show' => 'Smtp/Show',
        ],
        'cli' => [
            'enabled' => (bool) env('CLI_SMTP_STREAM', true),
        ],
    ],
    'var-dumper' => [
        'cli' => [
            'enabled' => (bool) env('CLI_VAR_DUMPER_STREAM', true),
        ],
    ],
    'monolog' => [
        'cli' => [
            'enabled' => (bool) env('CLI_MONOLOG_STREAM', true),
        ],
    ],
    'inspector' => [
        'http' => [
            'index' => 'Inspector/Index',
            'show' => 'Inspector/Show',
        ],
        'cli' => [
            'enabled' => (bool) env('CLI_INSPECTOR_STREAM', true),
        ],
    ],
];
