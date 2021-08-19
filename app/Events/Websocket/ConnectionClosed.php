<?php

namespace App\Events\Websocket;

use Illuminate\Foundation\Application;

class ConnectionClosed
{
    public function __construct(
        public Application $app,
        public Application $sandbox,
        public int $fd
    ){
    }
}
