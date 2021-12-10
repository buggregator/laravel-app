<?php

declare(strict_types=1);

namespace Modules\User\Application\Queries\FindByUsername;

use App\Commands\FinUserByUsername;
use App\Contracts\Query\QueryHandler;
use App\Exceptions\EntityNotFoundException;
use Modules\User\Application\Resources\UserResource;
use Modules\User\Domain\UserRepository;

class Handler implements QueryHandler
{
    public function __construct(private UserRepository $users)
    {
    }

    #[\App\Attributes\QueryBus\QueryHandler]
    public function __invoke(FinUserByUsername $query): UserResource
    {
        $user = $this->users->findOne(['name' => $query->username]);
        if (! $user) {
            throw new EntityNotFoundException(
                sprintf('User with given username [%s] was not found.', $query->username)
            );
        }

        return new UserResource($user);
    }
}
