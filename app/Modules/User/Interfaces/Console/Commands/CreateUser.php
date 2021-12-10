<?php

declare(strict_types=1);

namespace Modules\User\Interfaces\Console\Commands;

use App\Commands\FinUserByUsername;
use App\Contracts\Command\CommandBus;
use App\Contracts\Query\QueryBus;
use App\Exceptions\EntityNotFoundException;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    protected $signature = 'user:create {username?} {password?}';

    public function handle(CommandBus $commandBus, QueryBus $queryBus, CreateUserConfig $config)
    {
        if (! config('auth.enabled')) {
            $this->error('Authentication is disabled.');

            return;
        }

        $username = $this->argument('username') ?? $config->username();
        $password = $this->argument('password') ?? $config->password();

        try {
            $queryBus->ask(new FinUserByUsername($username));
            $this->error(sprintf('User with given username [%s] exists. Try another name.', $username));
        } catch (EntityNotFoundException $e) {
            $commandBus->dispatch(
                new \Modules\User\Application\Commands\CreateUser\Command(
                    username: $username,
                    password: $password
                )
            );

            $this->info('User created.');
            $this->table([], [
                ['Username', $username],
                ['Password', $password],
            ]);
        }
    }
}
