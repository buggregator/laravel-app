<?php
declare(strict_types=1);

namespace App\TCP;

use Spiral\RoadRunner\Tcp\TcpWorkerInterface;

class RespondMessage implements Response
{
    public function __construct(private string $body, private bool $close = false)
    {
    }

    public function getContext(): string
    {
        if ($this->close) {
            return TcpWorkerInterface::TCP_RESPOND_CLOSE;
        }

        return TcpWorkerInterface::TCP_RESPOND;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
