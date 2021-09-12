<?php

namespace App\Events\Websocket;

class Joined
{
    public function __construct(public string $channel)
    {
    }
}
