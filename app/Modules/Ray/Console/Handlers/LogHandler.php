<?php
declare(strict_types=1);

namespace Modules\Ray\Console\Handlers;

class LogHandler extends AbstractHandler
{
    protected function makeData(array $payload): array
    {
        $rows = [];
        foreach ($payload['content']['values'] as $value) {
            if (is_string($value)) {
                $value = preg_replace('/<(style|script)\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/(style|script)>/i', '', $value);
                $value = strip_tags($value);

                $rows[] = $value;
                continue;
            }

            $rows[] = $value;
        }

        return [
            'rows' => $rows
        ];
    }
}
