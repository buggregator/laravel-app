<?php

declare(strict_types=1);

namespace Tests;

use App\Commands\HandleReceivedEvent;
use App\Contracts\Command\CommandBus;
use App\Domain\Entity\Json;
use App\Domain\ValueObjects\Uuid;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\RepositoryInterface;
use Cycle\ORM\TransactionInterface;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Infrastructure\EventSauce\Persistance\CycleOrmMessageRepository;
use Modules\Events\Domain\Event;
use Symfony\Component\Console\Output\BufferedOutput;

class DatabaseTestCase extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected static $dbInitialized = false;

    protected function setUp(): void
    {
        $this->afterApplicationCreated(function () {
            if (!static::$dbInitialized) {
                $response = new BufferedOutput();

                $this->app[Kernel::class]->call('db:wipe', [], $response);

                static::$dbInitialized = true;
            }
        });

        parent::setUp();
    }

    public function createEvent(string $type, array $payload): Event
    {
        $this->app[CommandBus::class]->dispatch($command = new HandleReceivedEvent(
            type: $type,
            payload: $payload
        ));

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
        $this->getRepositoryFor($entity)->store($entity);
    }
}
