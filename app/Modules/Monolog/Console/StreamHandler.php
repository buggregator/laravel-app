<?php

declare(strict_types=1);

namespace Modules\Monolog\Console;

use App\Attributes\Console\Stream;
use Carbon\Carbon;
use Interfaces\Console\Handler;
use Termwind\HtmlRenderer;

#[Stream(name: 'monolog')]
class StreamHandler implements Handler
{
    public function __construct(
        private StreamHandlerConfig $config,
        private HtmlRenderer $renderer
    ) {
    }

    public function handle(array $payload): void
    {
        $levelColor = match (strtolower($payload['payload']['level_name'])) {
            'notice', 'info' => 'blue',
            'warning' => 'yellow',
            'critical', 'error', 'alert', 'emergency' => 'red',
            default => 'gray'
        };

        $this->renderer->render(
            (string) view('monolog::console.output', [
                'date' => Carbon::parse($payload['payload']['datetime'])->format('r'),
                'channel' => $payload['payload']['channel'] ?? '',
                'levelColor' => $levelColor,
                'level' => $payload['payload']['level_name'].'' ?? 'DEBUG',
                'messages' => explode("\n", $payload['payload']['message']),
            ])
        );

        // It can't be sent to HTML
        if (! empty($payload['payload']['context'])) {
            dump($payload['payload']['context']);
        }
    }

    public function shouldBeSkipped(array $payload): bool
    {
        if (! $this->config->isEnabled()) {
            return true;
        }

        return ! isset($payload['payload']['message']);
    }
}
