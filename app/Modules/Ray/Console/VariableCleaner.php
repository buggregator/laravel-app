<?php

declare(strict_types=1);

namespace Modules\Ray\Console;

use Symfony\Component\VarDumper\Caster\ReflectionCaster;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use function Termwind\terminal;

class VariableCleaner
{
    public static function cleanWithFormat($value): string
    {
        $dumper = new  HtmlDumper();
        $cloner = new VarCloner();
        $cloner->addCasters(ReflectionCaster::UNSET_CLOSURE_FILE_INFO);

        return self::clean(
            $dumper->dump($cloner->cloneVar($value), true)
        );
    }

    public static function clean($value, int|null $maxWidth = null): string
    {
        $maxWidth = is_null($maxWidth) ? terminal()->width() : $maxWidth;
        $value = (string) $value;

        if (preg_match('/(sf\-dump\-[0-9]+)/i', $value)) {
            $value = preg_replace('/<(style|script)\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/(style|script)>/i', '', $value);
        }

        $value = strip_tags($value);

        if ($maxWidth > 0 && $maxWidth < strlen($value)) {
            return implode("\n", str_split($value, $maxWidth));
        }

        return $value;
    }
}
