<?php

declare(strict_types=1);

namespace Infrastructure\CycleOrm;

use Cycle\Database\Driver\DriverInterface;
use Cycle\Database\LoggerFactoryInterface;
use Illuminate\Log\LogManager;
use Psr\Log\LoggerInterface;

class LoggerFactory implements LoggerFactoryInterface
{
    public function __construct(
        private LogManager $logManager,
        private array $config
    ) {
    }

    public function getLogger(DriverInterface $driver = null): LoggerInterface
    {
        return $this->logManager->driver(
            $this->config['drivers'][strtolower($driver->getType())]
            ?? $this->config['default'] ?? 'null'
        );
    }
}
