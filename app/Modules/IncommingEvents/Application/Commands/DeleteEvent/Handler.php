<?php

declare(strict_types=1);

namespace Modules\IncommingEvents\Application\Commands\DeleteEvent;

use App\Commands\DeleteEvent;
use App\Contracts\Command\CommandHandler;
use Modules\IncommingEvents\Persistance\EventProcessAggregateRootRepository;

class Handler implements CommandHandler
{
    public function __construct(private EventProcessAggregateRootRepository $repository)
    {
    }

    // #[\App\Attributes\CommandBus\CommandHandler]
    public function __invoke(DeleteEvent $command): void
    {
        $event = $this->repository->retrieve($command->uuid);
        $event->delete();
        $this->repository->persist($event);
    }
}
