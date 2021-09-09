<?php
declare(strict_types=1);

namespace Modules\Monolog\Console;

use Illuminate\Contracts\Config\Repository;

class StreamHandlerConfig
{
    public function __construct(private Repository $config)
    {
    }

    public function isEnabled(): bool
    {
        return (bool)$this->config->get('server.monolog.cli.enabled');
    }
}
