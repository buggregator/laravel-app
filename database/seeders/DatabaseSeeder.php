<?php

namespace Database\Seeders;

use Cycle\ORM\EntityManagerInterface;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\User\Domain\User;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        if (! config('auth.enabled')) {
            return;
        }

        $entityManager = $this->container[EntityManagerInterface::class];
        $username = env('AUTH_USERNAME', 'admin');
        $password = env('AUTH_PASSWORD', Str::random(8));

        $entityManager->persist(new User($username, Hash::make($password)));
        $entityManager->run();

        echo 'Username: '.$username."\n";
        echo 'Password: '.$password."\n";
    }
}
