<?php

declare(strict_types=1);

namespace Modules\User\Application\Commands\CreateUser;

use App\Contracts\Command\CommandHandler;
use App\Contracts\Event\EventBus;
use Cycle\ORM\EntityManagerInterface;
use Illuminate\Contracts\Hashing\Hasher;
use Modules\User\Domain\Events\UserWasCreated;
use Modules\User\Domain\User;

class Handler implements CommandHandler
{
    public function __construct(
        private EventBus $eventBus,
        private EntityManagerInterface $entityManager,
        private Hasher $hasher
    ) {
    }

    #[\App\Attributes\CommandBus\CommandHandler]
    public function handle(Command $command): void
    {
        $this->entityManager->persist(
            new User(
                $command->username,
                $this->hasher->make($command->password),
                $command->uuid
            )
        );

        $this->entityManager->run();

        $this->eventBus->publish(
            new UserWasCreated(
                $command->uuid,
                $command->username,
                $command->password
            )
        );
    }
}
