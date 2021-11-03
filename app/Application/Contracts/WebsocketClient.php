<?php
declare(strict_types=1);

namespace App\Contracts;

interface WebsocketClient
{
    public function sendEvent(string $topic, array $event): void;
}
