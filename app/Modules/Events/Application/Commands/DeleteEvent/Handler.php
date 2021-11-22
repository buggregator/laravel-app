<?php

declare(strict_types=1);

namespace Modules\Events\Application\Commands\DeleteEvent;

use App\Commands\DeleteEvent;
use App\Contracts\Command\CommandHandler;
use App\Exceptions\EntityNotFoundException;
use Cycle\ORM\TransactionInterface;
use Illuminate\Contracts\Events\Dispatcher;
use Modules\Events\Domain\EventRepository;
use Modules\IncommingEvents\Domain\Events\EventWasDeleted;

class Handler implements CommandHandler
{
    public function __construct(
        private EventRepository $events,
        private Dispatcher $dispatcher,
        private TransactionInterface $transaction
    ) {
    }

    #[\App\Attributes\CommandBus\CommandHandler]
    public function __invoke(Command $command): void
    {
        $event = $this->events->findByPK((string) $command->uuid);

        if (! $event) {
            throw new EntityNotFoundException('Event with given uuid is not found.');
        }

        $this->transaction->delete($event);
        $this->transaction->run();
    }

    #[\App\Attributes\CommandBus\CommandHandler]
    public function handle(DeleteEvent $command): void
    {
        $this->__invoke(new Command(uuid: $command->uuid));
        $this->dispatcher->dispatch(new EventWasDeleted($command->uuid));
    }
}
