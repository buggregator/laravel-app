<?php

declare(strict_types=1);

namespace Tests;

use App\Commands\FindTransactionByName;
use App\Commands\HandleReceivedEvent;
use App\Contracts\Command\CommandBus;
use App\Contracts\Query\QueryBus;
use App\Exceptions\EntityNotFoundException;
use Cycle\ORM\EntityManager;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\RepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Events\Domain\Event;
use Modules\Transaction\Domain\Transaction;

class DatabaseTestCase extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed('Database\\Seeders\\ProjectSeeder');
    }

    public function findOrCreateTransaction($transactionName): int
    {
        try {
            $transaction = $this->app[QueryBus::class]->ask(new FindTransactionByName($transactionName));
        } catch (EntityNotFoundException) {
            $transaction = new Transaction($transactionName);
            $this->persistEntity($transaction);
        }

        return $transaction->getId();
    }

    public function createEvent(string $type, array $payload, $transactionId = null): Event
    {
        $this->app[CommandBus::class]->dispatch(
            $command = new HandleReceivedEvent(
                projectId: 1,
                type: $type,
                payload: $payload,
                transactionId: $transactionId
            )
        );

        return $this->getRepositoryFor(Event::class)->findByPK($command->uuid->toString());
    }

    public function getOrm(): ORMInterface
    {
        return $this->app[ORMInterface::class];
    }

    public function getRepositoryFor($entity): RepositoryInterface
    {
        return $this->getOrm()->getRepository($entity);
    }

    public function detachEntityFromIdentityMap($entity)
    {
        $this->getOrm()->getHeap()->detach($entity);
    }

    public function cleanIdentityMap()
    {
        $this->getOrm()->getHeap()->clean();
    }

    public function persistEntity($entity)
    {
        $t = new EntityManager($this->getOrm());
        $t->persist($entity);
        $t->run();
    }
}
