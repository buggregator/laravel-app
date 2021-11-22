<?php

declare(strict_types=1);

namespace App\Contracts\TCP;

use Spiral\RoadRunner\Tcp\Request;

interface Kernel extends Handler
{
    public function addHandler(string $server, string $handler): void;

    /**
     * Call the terminate method.
     */
    public function terminate(Request $request, Response $response): void;
}
