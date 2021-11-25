<?php

declare(strict_types=1);

namespace Modules\User\Domain;

use App\Domain\ValueObjects\Uuid;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Table\Index;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\User\Persistence\CycleOrmUserRepository;

#[Entity(repository: CycleOrmUserRepository::class)]
#[Index(columns: ['name'], unique: true)]
class User implements Authenticatable
{
    #[Column(type: 'string')]
    private string $rememberToken = '';

    #[Column(type: 'string', primary: true, typecast: 'uuid')]
    private Uuid $uuid;

    /**  @internal */
    public function __construct(
        #[Column(type: 'string(32)')]
        private string $name,
        #[Column(type: 'string(32)')]
        private string $password,
        ?Uuid $uuid = null,
    ) {
        $this->uuid = $uuid ?? Uuid::generate();
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

    public function getName(): string
    {
        return $this->name;
    }
}
