<?php
declare(strict_types=1);

namespace App\Ray\Console\Handlers;

use App\Ray\Console\VariableCleaner;

class CustomHandler extends AbstractHandler
{
    public function handle(array $payload): void
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

            foreach (explode("\n", $value) as $line) {
                $this->output->writeln($line);
            }
        } else {
            dump($value);
        }
    }
}
