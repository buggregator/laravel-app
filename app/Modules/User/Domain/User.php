<?php

declare(strict_types=1);

namespace Modules\User\Domain;

use App\Domain\ValueObjects\Uuid;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\User\Persistence\CycleOrmUserRepository;

#[Entity(
    repository: CycleOrmUserRepository::class
)]
class User implements Authenticatable
{
    #[Column(type: 'string')]
    private string $rememberToken = '';

    /**  @internal */
    public function __construct(
        private Uuid $uuid,
        private string $name,
        private string $password
    ) {
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getAuthIdentifierName(): string
    {
        return 'uuid';
    }

    public function getAuthIdentifier(): string
    {
        return $this->uuid->toString();
    }

    public function getAuthPassword(): string
    {
        return $this->password;
    }

    public function getRememberToken(): string
    {
        return $this->rememberToken;
    }

    public function setRememberToken($value): void
    {
        $this->rememberToken = $value;
    }

    public function getRememberTokenName(): string
    {
        return 'remember_token';
    }
}
