<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use App\Domain\ValueObjects\Uuid;
use App\Events\Websocket\Joined;
use Illuminate\Support\Facades\Event;
use Modules\User\Domain\User;
use Tests\DatabaseTestCase;

class WebsocketControllerTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('broadcasting.default', 'roadrunner');
    }

    public function testJoinToPrivateTopicWithoutAuthorization()
    {
        Event::fake([
            Joined::class,
        ]);

        $this->getJson(route('ws.join'), ['X-WS-Join-Topics' => 'private-test'])
            ->assertForbidden();
    }

    public function testJoinToPrivateTopicWithAuthorization()
    {
        $this->actingAs(
            $user = new User(
                uuid: Uuid::generate(),
                name: 'guest',
                password: 'secret'
            )
        );

        \Broadcast::channel('test', static function (User $u) use ($user) {
            return $user === $u;
        });

        $this->getJson(route('ws.join'), ['X-WS-Join-Topics' => 'private-test'])
            ->assertOk();
    }

    public function testJoinToPublicTopic()
    {
        Event::fake([
            Joined::class,
        ]);

        $this->getJson(route('ws.join'), ['X-WS-Join-Topics' => 'test'])
            ->assertOk();
    }
}
