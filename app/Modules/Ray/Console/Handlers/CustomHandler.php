<?php
declare(strict_types=1);

namespace Modules\Ray\Console\Handlers;

use Modules\Ray\Console\VariableCleaner;

class CustomHandler extends AbstractHandler
{
    protected function makeData(array $payload): array
    {
        $value = $payload['content']['content'];

        if (is_string($value)) {
            if (preg_match('/(sf\-dump\-[0-9]+)/i', $value)) {
                $value = VariableCleaner::clean($value);
            } else {
                $value = str_replace('&nbsp;', ' ', $value);
                $value = str_replace(['<br>', '<br />'], "\n", $value);
                $value = htmlspecialchars_decode($value);
            }
        } else {
            $value = json_encode($value);
        }

        return [
            'string' => $value
        ];
    }
}
