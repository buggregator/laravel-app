<?php

declare(strict_types=1);

namespace Infrastructure\RoadRunner\Broadcast;

use App\Contracts\WebsocketClient;
use Illuminate\Broadcasting\Broadcasters\Broadcaster;
use Illuminate\Broadcasting\Broadcasters\UsePusherChannelConventions;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

final class RoadRunnerBroadcaster extends Broadcaster
{
    use UsePusherChannelConventions;

    public function __construct(private WebsocketClient $ws)
    {
    }

    public function auth($request): string
    {
        $channelName = $this->normalizeChannelName($request->channel_name);

        if (empty($request->channel_name)
            || ($this->isGuardedChannel($request->channel_name)
                && ! $this->retrieveUser($request, $channelName))) {
            throw new AccessDeniedHttpException();
        }

        return parent::verifyUserCanAccessChannel(
            $request,
            $channelName
        );
    }

    public function validAuthenticationResponse($request, $result): string
    {
        if (is_bool($result)) {
            return json_encode($result);
        }

        $channelName = $this->normalizeChannelName($request->channel_name);

        $user = $this->retrieveUser($request, $channelName);

        $broadcastIdentifier = method_exists($user, 'getAuthIdentifierForBroadcasting')
            ? $user->getAuthIdentifierForBroadcasting()
            : $user->getAuthIdentifier();

        return json_encode([
            'channel_data' => [
                'user_id' => $broadcastIdentifier,
                'user_info' => $result,
            ],
        ]);
    }

    public function broadcast(array $channels, $event, array $payload = []): void
    {
        if (empty($channels)) {
            return;
        }

        $payload = [
            'event' => $event,
            'payload' => $payload,
        ];

        foreach ($this->formatChannels($channels) as $channel) {
            $this->ws->sendEvent($channel, $payload);
        }
    }
}
