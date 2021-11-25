<?php

declare(strict_types=1);

namespace Modules\User\Interfaces\Console\Commands;

use Cycle\ORM\ORMInterface;
use Cycle\ORM\TransactionInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\User\Domain\User;

class CreateUser extends Command
{
    protected $signature = 'user:create {username?} {password?}';

    public function handle(TransactionInterface $transaction, ORMInterface $orm)
    {
        if (! config('auth.enabled')) {
            $this->error('Authentication is disabled.');

            return;
        }

        $username = $this->argument('username') ?? env('AUTH_USERNAME', 'admin');
        $password = $this->argument('password') ?? env('AUTH_PASSWORD', Str::random(8));


        $user = $orm->getRepository(User::class)->findOne(['name' => $username]);
        if ($user) {
            $this->error(sprintf('User with given username [%s] exists. Try another name.', $username));

            return;
        }

        $transaction->persist(new User($username, Hash::make($password)));
        $transaction->run();

        $this->info('User created.');
        $this->table([], [
            ['Username', $username],
            ['Password', $password],
        ]);
    }
}
