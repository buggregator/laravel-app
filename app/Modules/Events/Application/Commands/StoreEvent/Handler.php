<?php
declare(strict_types=1);

namespace Modules\Events\Application\Commands\StoreEvent;

use App\Contracts\Command\CommandHandler;
use App\Domain\Entity\Json;
use Modules\Events\Domain\Event;
use Modules\Events\Domain\EventRepository;

class Handler implements CommandHandler
{
    public function __construct(private EventRepository $events)
    {}

    #[\App\Attributes\CommandBus\CommandHandler]
    public function handle(Command $command): void
    {
        $this->events->store(
            new Event(
                $command->uuid,
                $command->type,
                new Json($command->payload),
                $command->date
            )
        );
    }
}
