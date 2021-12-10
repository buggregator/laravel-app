<?php

declare(strict_types=1);

namespace Modules\User\Application\Commands\CreateUser;

use App\Domain\ValueObjects\Uuid;

class Command implements \App\Contracts\Command\Command
{
    public Uuid $uuid;

    // TODO: use readonly property
    public function __construct(
        public string $username,
        public string $password,
    ) {
        $this->uuid = Uuid::generate();
    }
}
