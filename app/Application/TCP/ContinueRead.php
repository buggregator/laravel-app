<?php

declare(strict_types=1);

namespace App\TCP;

use App\Contracts\TCP\Response;
use Spiral\RoadRunner\Tcp\TcpWorkerInterface;

class ContinueRead implements Response
{
    public function getContext(): string
    {
        return TcpWorkerInterface::TCP_READ;
    }

    public function getBody(): string
    {
        return '';
    }
}
