<?php
declare(strict_types=1);

namespace Modules\Events\Application\Commands\ClearEvents;

use App\Contracts\Command\CommandHandler;
use Modules\Events\Domain\EventRepository;

class Handler implements CommandHandler
{
    public function __construct(private EventRepository $events)
    {
    }

    #[\App\Attributes\CommandBus\CommandHandler]
    public function __invoke(Command $command): void
    {
        $this->events->deleteAll($command->type);
    }
}
