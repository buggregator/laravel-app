<?php
declare(strict_types=1);

namespace App\Broadcasting\Broadcasters;

use App\Contracts\WebsocketClient;
use Illuminate\Broadcasting\Broadcasters\Broadcaster;

class SwooleBroadcaster extends Broadcaster
{
    public function __construct(private WebsocketClient $ws)
    {
    }

    public function auth($request)
    {
        return true;
    }

    public function validAuthenticationResponse($request, $result)
    {
        return true;
    }

    public function broadcast(array $channels, $event, array $payload = [])
    {
        $this->ws->sendEvent($payload);
    }
}
