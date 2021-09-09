<?php
declare(strict_types=1);

namespace Modules\Ray\Console;

use Illuminate\Contracts\Config\Repository;

class StreamHandlerConfig
{
    public function __construct(private Repository $config)
    {
    }

    public function isEnabled(): bool
    {
        return (bool)$this->config->get('server.ray.cli.enabled');
    }

    public function getHandlers(): array
    {
        return (array)$this->config->get('server.ray.cli.handlers');
    }
}
