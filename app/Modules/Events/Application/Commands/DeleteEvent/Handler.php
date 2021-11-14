<?php
declare(strict_types=1);

namespace Modules\Events\Application\Commands\DeleteEvent;

use App\Contracts\Command\CommandHandler;
use Modules\Events\Domain\EventRepository;
use Modules\Events\Exceptions\EventNotFoundException;

class Handler implements CommandHandler
{
    public function __construct(private EventRepository $events)
    {
    }

    #[\App\Attributes\CommandBus\CommandHandler]
    public function __invoke(Command $command): void
    {
        $event = $this->events->findByPK($command->uuid->toString());
        if (!$event) {
            throw new EventNotFoundException();
        }

        $this->events->delete($event);
    }
}
