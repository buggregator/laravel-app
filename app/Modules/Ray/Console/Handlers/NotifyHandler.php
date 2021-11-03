<?php
declare(strict_types=1);

namespace Modules\Ray\Console\Handlers;

class NotifyHandler extends AbstractHandler
{
    protected function makeData(array $payload): array
    {
        $len = strlen($payload['content']['value']);
        return [
            'message' => [
                str_pad('', $len, '.'),
                $payload['content']['value'],
                str_pad('', $len, '.')
            ]
        ];
    }
}
