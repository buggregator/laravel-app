<?php

declare(strict_types=1);

namespace Console;

use Modules\User\Domain\User;
use Modules\User\Interfaces\Console\Commands\CreateUserConfig;
use Tests\DatabaseTestCase;

class CreateUserCommandTest extends DatabaseTestCase
{
    public function testUserWithGivenUsernameAndPasswordShouldBeCreated()
    {
        config()->set('auth.enabled', true);
        $this->artisan('user:create', [
            'username' => 'test',
            'password' => 'secret',
        ])
            ->expectsOutput('User created.')
            ->expectsTable([], [
                ['Username', 'test'],
                ['Password', 'secret'],
            ])
            ->assertSuccessful();

        $this->cleanIdentityMap();

        $user = $this->getRepositoryFor(User::class)->findOne(['name' => 'test']);
        $this->assertNotNull($user);
    }

    public function testUserWithExistsUsernameShouldNotBeCreated()
    {
        config()->set('auth.enabled', true);
        $this->persistEntity(new User('test', 'secret'));
        $this->artisan('user:create', [
            'username' => 'test',
            'password' => 'secret',
        ])
            ->expectsOutput('User with given username [test] exists. Try another name.');

        $this->assertCount(1, $this->getRepositoryFor(User::class)->findAll());
    }

    public function testIfAuthDisabledUserShouldNotBeCreated()
    {
        config()->set('auth.enabled', false);

        $this->artisan('user:create', [
            'username' => 'test',
            'password' => 'secret',
        ])
            ->expectsOutput('Authentication is disabled.');

        $this->assertCount(0, $this->getRepositoryFor(User::class)->findAll());
    }

    public function testUserWithUsernameAndPasswordFromConfigShouldBeCreated()
    {
        config()->set('auth.enabled', true);

        $this->swap(CreateUserConfig::class, $config = \Mockery::mock(CreateUserConfig::class));
        $config->shouldReceive('username')->once()->andReturn('foo');
        $config->shouldReceive('password')->once()->andReturn('bar');

        $this->artisan('user:create')
            ->expectsOutput('User created.')
            ->expectsTable([], [
                ['Username', 'foo'],
                ['Password', 'bar'],
            ])
            ->assertSuccessful();

        $this->cleanIdentityMap();

        $user = $this->getRepositoryFor(User::class)->findOne(['name' => 'foo']);
        $this->assertNotNull($user);
    }
}
