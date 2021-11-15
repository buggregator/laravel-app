<?php
declare(strict_types=1);

namespace Modules\Events\Application\Commands\StoreEvent;

use App\Contracts\Command\CommandHandler;
use App\Domain\Entity\Json;
use Cycle\ORM\TransactionInterface;
use Modules\Events\Domain\Event;

class Handler implements CommandHandler
{
    public function __construct(
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
}
