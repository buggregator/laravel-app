<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use Illuminate\Support\Facades\Hash;
use Modules\User\Domain\User;
use Tests\DatabaseTestCase;

class AuthControllerTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('auth.enabled', true);
    }

    public function testGuestShouldBeRedirectedToLoginPage()
    {
        $this->get(route('events'))
            ->assertRedirect(route('login'));
    }

    public function testUserShouldBeAuthenticated()
    {
        $this->persistEntity(
            new User(
                name: 'guest',
                password: Hash::make('secret')
            )
        );

        $this->post(route('login'), ['name' => 'guest', 'password' => 'secret'])
            ->assertRedirect(route('events'));
    }

    public function testAuthenticatedUserCanLogout()
    {
        $this->persistEntity(
            $user = new User(
                name: 'guest',
                password: Hash::make('secret')
            )
        );

        $this->be($user);

        $this->get(route('events'))->assertOk();

        $this->post(route('logout'))
            ->assertRedirect('/');

        $this->get(route('events'))
            ->assertRedirect(route('login'));
    }
}
