<?php

declare(strict_types=1);

namespace Modules\IncommingEvents\Application\Commands\HandleReceivedEvent;

use App\Commands\HandleReceivedEvent;
use App\Contracts\Command\CommandHandler;
use Modules\IncommingEvents\Domain\EventProcess;
use Modules\IncommingEvents\Persistance\EventProcessAggregateRootRepository;

final class Handler implements CommandHandler
{
    public function __construct(private EventProcessAggregateRootRepository $repository)
    {
    }

    // #[\App\Attributes\CommandBus\CommandHandler]
    public function __invoke(HandleReceivedEvent $command): void
    {
        $this->repository->persist(
            EventProcess::received(
                $command->uuid,
                $command->type,
                $command->payload,
                $command->timestamp
            )
        );
    }
}
