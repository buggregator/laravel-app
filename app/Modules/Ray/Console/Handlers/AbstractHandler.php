<?php

declare(strict_types=1);

namespace Modules\Ray\Console\Handlers;

use Interfaces\Console\Handler;
use Termwind\HtmlRenderer;

abstract class AbstractHandler implements Handler
{
    public function __construct(protected HtmlRenderer $renderer)
    {
    }

    protected function getViewName(array $payload): string
    {
        return 'ray::console.'.$payload['type'];
    }

    public function handle(array $payload): void
    {
        $data = $this->makeData($payload);

        $this->renderer->render(
            (string) view('ray::console.output', [
                'date' => date('r'),
                'source' => sprintf(
                    '%s on line %s',
                    class_basename($payload['origin']['file']),
                    $payload['origin']['line_number']
                ),
                'file' => $this->getFilePath($payload['origin']['file']),
                'type' => ucfirst(str_replace(['-', '_'], ' ', $payload['type'])),
                'color' => $data['color'] ?? 'gray',
                'label' => $payload['content']['label'] ?? null,
                'content' => view($this->getViewName($payload), $data),
            ])
        );
    }

    public function shouldBeSkipped(array $payload): bool
    {
        return ! view()->exists($this->getViewName($payload));
    }

    abstract protected function makeData(array $payload): array;

    private function getFilePath(string $path): string
    {
        if (($pos = strpos($path, 'vendor')) !== false) {
            return '~/'.substr($path, $pos, strlen($path));
        }

        return $path;
    }
}
