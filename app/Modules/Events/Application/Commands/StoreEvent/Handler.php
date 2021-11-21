<?php

declare(strict_types=1);

namespace Modules\Events\Application\Commands\StoreEvent;

use App\Commands\HandleReceivedEvent;
use App\Contracts\Command\CommandHandler;
use App\Domain\Entity\Json;
use Cycle\ORM\TransactionInterface;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Carbon;
use Modules\Events\Domain\Event;
use Modules\IncommingEvents\Domain\Events\EventWasReceived;

class Handler implements CommandHandler
{
    public function __construct(
        private Dispatcher $dispatcher,
        private TransactionInterface $transaction,
    ) {
    }

    #[\App\Attributes\CommandBus\CommandHandler]
    public function handle(Command $command): void
    {
        $this->transaction->persist(
            new Event(
                $command->uuid,
                $command->type,
                new Json($command->payload),
                $command->date
            )
        );

        $this->transaction->run();
    }

    #[\App\Attributes\CommandBus\CommandHandler]
    public function __invoke(HandleReceivedEvent $command): void
    {
        $this->handle(
            new Command(
                type: $command->type,
                uuid: $command->uuid,
                date: Carbon::createFromTimestamp($command->timestamp)->toDateTimeImmutable(),
                payload: $command->payload
            )
        );

        $this->dispatcher->dispatch(
            new EventWasReceived(
                $command->uuid, $command->type, $command->payload, $command->timestamp, $command->sendToConsole
            )
        );
    }
}
