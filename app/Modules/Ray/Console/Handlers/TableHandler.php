<?php
declare(strict_types=1);

namespace Modules\Ray\Console\Handlers;

use Modules\Ray\Console\VariableCleaner;
use function Termwind\terminal;

class TableHandler extends AbstractHandler
{
    protected function makeData(array $payload): array
    {
        $maxWidth = terminal()->width();
        $keyWidth = round($maxWidth * 0.3);
        $valueWidth = round($maxWidth * 0.7);

        return [
            'label' => $payload['content']['label'] ?? null,
            'rows' => collect((array)$payload['content']['values'])->mapWithKeys(function ($value, $key) use($keyWidth, $valueWidth) {
                $key = nl2br(VariableCleaner::clean($key, (int) $keyWidth));
                $value =  nl2br(VariableCleaner::clean($value, (int) $valueWidth));

                return [
                    $key => $value
                ];
            })->toArray()
        ];
    }
}
