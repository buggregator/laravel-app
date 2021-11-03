<?php
declare(strict_types=1);

namespace App\Events\Websocket;

class Joined
{
    public function __construct(public string $channel)
    {
    }
}
