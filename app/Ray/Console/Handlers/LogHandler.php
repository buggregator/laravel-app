<?php
declare(strict_types=1);

namespace App\Ray\Console\Handlers;

class LogHandler extends AbstractHandler
{
    public function handle(array $payload): void
    {
        foreach ($payload['content']['values'] as $value) {

            $value = preg_replace('/<(style|script)\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/(style|script)>/i', '', $value);
            $value = strip_tags($value);

            foreach (explode("\n", $value) as $line) {
                $this->output->writeln($line);
            }
        }
    }
}
