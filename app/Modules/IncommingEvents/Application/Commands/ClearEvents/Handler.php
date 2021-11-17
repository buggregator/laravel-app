<?php

declare(strict_types=1);

namespace Modules\IncommingEvents\Application\Commands\ClearEvents;

use App\Commands\ClearEvents;
use App\Contracts\Command\CommandHandler;
use Modules\IncommingEvents\Persistance\EventProcessAggregateRootRepository;

class Handler implements CommandHandler
{
    public function __construct(
        private EventProcessAggregateRootRepository $repository
    ) {
    }

    // #[\App\Attributes\CommandBus\CommandHandler]
    public function __invoke(ClearEvents $command): void
    {
    }
}
