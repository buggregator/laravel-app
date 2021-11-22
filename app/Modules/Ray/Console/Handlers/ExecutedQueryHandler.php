<?php

declare(strict_types=1);

namespace Modules\Ray\Console\Handlers;

class ExecutedQueryHandler extends AbstractHandler
{
    protected function makeData(array $payload): array
    {
        $data = [];

        if (isset($payload['content']['connection_name'])) {
            $data['Connection'] = $payload['content']['connection_name'];
        }

        if (isset($payload['content']['time'])) {
            $data['Time'] = $payload['content']['time'].' ms';
        }

        return [
            'query' => vsprintf(
                str_replace('?', '%s', $payload['content']['sql']),
                collect($payload['content']['bindings'])->map(function ($binding) {
                    return is_numeric($binding) ? $binding : "'{$binding}'";
                })->toArray()
            ),
            'data' => $data,
        ];
    }
}
