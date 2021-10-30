<?php
declare(strict_types=1);

namespace Modules\Monolog\Console;

use App\Attributes\Console\Stream;
use Interfaces\Console\Handler;
use Carbon\Carbon;
use Termwind\HtmlRenderer;

#[Stream(name: 'monolog')]
class StreamHandler implements Handler
{
    public function __construct(
        private StreamHandlerConfig $config,
        private HtmlRenderer $renderer
    )
    {
    }

    public function handle(array $payload): void
    {
        $levelColor = match (strtolower($payload['data']['level_name'])) {
            'notice' , 'info' => 'blue',
            'warning' => 'yellow',
            'critical', 'error', 'alert', 'emergency' => 'red',
            default => 'gray'
        };

        $this->renderer->render(
            (string) view('monolog::console.output', [
                'date' => Carbon::parse($payload['data']['datetime'])->format('r'),
                'channel' => $payload['data']['channel'] ?? '',
                'levelColor' => $levelColor,
                'level' => $payload['data']['level_name'] . '' ?? 'DEBUG',
                'messages' => explode("\n", $payload['data']['message']),
            ])
        );

        // It can't be sent to HTML
        if (!empty($payload['data']['context'])) {
            dump($payload['data']['context']);
        }
    }

    public function shouldBeSkipped(array $payload): bool
    {
        if (!$this->config->isEnabled()) {
            return true;
        }

        return !isset($payload['data']['message']);
    }
}
