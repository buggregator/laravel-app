<?php

declare(strict_types=1);

namespace Modules\Events\Application\Commands\ClearEvents;

use App\Commands\ClearEvents;
use App\Contracts\Command\CommandHandler;
use Cycle\ORM\TransactionInterface;
use Illuminate\Contracts\Events\Dispatcher;
use Modules\Events\Domain\Event;
use Modules\Events\Domain\EventRepository;
use Modules\IncommingEvents\Domain\Events\EventsWasClear;

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
        $scope = [];
        if ($command->type) {
            $scope['type'] = $command->type;
        }

        // TODO: make more optimized
        $this->events->findAll($scope)->each(
            fn (Event $event) => $this->transaction->delete($event)
        );

        $this->transaction->run();
    }

    #[\App\Attributes\CommandBus\CommandHandler]
    public function handle(ClearEvents $command): void
    {
        $this->__invoke(new Command(type: $command->type));
        $this->dispatcher->dispatch(new EventsWasClear(type: $command->type));
    }
}
