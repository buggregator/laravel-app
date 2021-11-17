<?php

declare(strict_types=1);

namespace Infrastructure\CycleOrm\Auth;

use Cycle\ORM\ORMInterface;
use Cycle\ORM\RepositoryInterface;
use Cycle\ORM\TransactionInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Str;

final class UserProvider implements \Illuminate\Contracts\Auth\UserProvider
{
    public function __construct(
        private ORMInterface $orm,
        private TransactionInterface $transaction,
        private string $model,
        private Hasher $hasher
    ) {
    }

    public function retrieveById($identifier)
    {
        return $this->getRepository()->findByPK($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {
        return $this->getRepository()->findOne([
            'uuid' => $identifier,
            'remember_token' => $token,
        ]);
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // TODO: use command handler

        $user->setRememberToken($token);
        $this->transaction->persist($user);
        $this->transaction->run();
    }

    public function retrieveByCredentials(array $credentials)
    {
        $criteria = [];
        foreach ($credentials as $key => $value) {
            if (! Str::contains($key, 'password')) {
                $criteria[$key] = $value;
            }
        }

        return $this->getRepository()->findOne($criteria);
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return $this->hasher->check($credentials['password'], $user->getAuthPassword());
    }

    protected function getRepository(): RepositoryInterface
    {
        return $this->orm->getRepository($this->model);
    }
}
