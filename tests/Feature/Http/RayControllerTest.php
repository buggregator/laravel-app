<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use Illuminate\Contracts\Cache\Repository;
use Modules\Events\Domain\Event;
use Tests\DatabaseTestCase;

class RayControllerTest extends DatabaseTestCase
{
    public function testEventShouldBeStored()
    {
        $this->postJson(
            route('ray.event.store'),
            $payload = [
                'payloads' => [
                    [
                        'type' => 'test',
                        'content' => ['foo' => 'bar'],
                    ],
                ],
            ]
        );

        /** @var \Modules\Events\Domain\Event $event */
        $event = $this->getRepositoryFor(Event::class)->findAll()->first();

        $this->assertSame('ray', $event->getType());
        $this->assertSame($payload, $event->getPayload()->toArray());
    }

    public function testCheckAvailabilityAction()
    {
        $this->get(route('ray.availability_check'))
            ->assertStatus(config('server.ray.http_check_status'));
    }

    public function testCheckLockActionShouldReturn404IfHashNotFound()
    {
        $this->getJson(route('ray.lock.check', 'test'))
            ->assertNotFound();
    }

    public function testCheckLockActionShouldReturnStatusIfHashFound()
    {
        $cache = $this->mock(Repository::class);
        $cache->shouldReceive('get')->once()->with('test')->andReturn(['foo' => 'bar']);
        $cache->shouldReceive('forget')->once()->with('test');

        $this->getJson(route('ray.lock.check', 'test'))
            ->assertJson(['foo' => 'bar']);
    }

    public function testCheckLockActionShouldReturnDefaultStatus()
    {
        $cache = $this->mock(Repository::class);
        $cache->shouldReceive('get')->once()->with('test')->andReturn(true);

        $this->getJson(route('ray.lock.check', 'test'))
            ->assertJson(['active' => true, 'stop_execution' => false]);
    }

    public function testDeleteLockAction()
    {
        $cache = $this->mock(Repository::class);
        $cache->shouldReceive('put')->once()->with('test', [
            'active' => false,
            'stop_execution' => true,
        ]);

        $this->deleteJson(route('ray.lock.delete', 'test'), ['stop_execution' => true])
            ->assertOk();
    }
}
