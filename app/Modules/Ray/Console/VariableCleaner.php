<?php
declare(strict_types=1);

namespace Modules\Ray\Console;

class VariableCleaner
{
    const MAX_WIDTH = 130;

    public static function clean($value, int $maxWidth = self::MAX_WIDTH): string
    {
        $value = (string)$value;

        if (preg_match('/(sf\-dump\-[0-9]+)/i', $value)) {
            $value = preg_replace('/<(style|script)\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/(style|script)>/i', '', $value);
        }

        $value = strip_tags($value);

        if ($maxWidth > 0) {
            return implode(PHP_EOL, str_split($value, $maxWidth));
        }

        return $value;
    }
}
