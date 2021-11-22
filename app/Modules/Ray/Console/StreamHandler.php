<?php

declare(strict_types=1);

namespace Modules\Ray\Console;

use App\Attributes\Console\Stream;
use Interfaces\Console\Handler;
use Termwind\HtmlRenderer;

#[Stream(name: 'ray')]
class StreamHandler implements Handler
{
    private array $payloadHandlers = [];

    public function __construct(
        private StreamHandlerConfig $config,
        private HtmlRenderer $renderer
    ) {
        $this->payloadHandlers = $config->getHandlers();
    }

    public function handle(array $stream): void
    {
        foreach ($stream['payload']['payloads'] as $payload) {
            if (! isset($this->payloadHandlers[$payload['type']])) {
                //$handler = new DebugHandler($this->renderer);
                continue;
            } else {
                $handler = $this->payloadHandlers[$payload['type']];
                $handler = new $handler($this->renderer);
            }

            if ($handler->shouldBeSkipped($payload)) {
                continue;
            }

            $handler->handle($payload);
        }
    }

    public function shouldBeSkipped(array $stream): bool
    {
        if (! $this->config->isEnabled()) {
            return true;
        }

        return ! isset($stream['payload']['payloads']);
    }
}
