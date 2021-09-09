<?php
declare(strict_types=1);

namespace Modules\Ray\Console\Handlers;


class ContextHandler extends AbstractHandler
{
    public function handle(array $payload): void
    {
        if (!isset($payload['origin'])) {
            return;
        }

        $this->output->table([], [
            ['date', date('r')],
            ['source', sprintf('%s on line %s', class_basename($payload['origin']['file']), $payload['origin']['line_number'])],
            ['file', $this->getFilePath($payload['origin']['file'])]
        ]);
    }

    public function printTitle(array $payload): void
    {
    }


    public function shouldBeSkipped(array $payload): bool
    {
        return empty($payload['origin']);
    }

    public function getFilePath(string $path): string
    {
        if (($pos = strpos($path, 'vendor')) !== false) {
            return '~/' . substr($path, $pos, strlen($path));
        }

        return $path;
    }
}
