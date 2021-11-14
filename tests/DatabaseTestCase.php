<?php
declare(strict_types=1);

namespace Tests;

use Cycle\ORM\ORMInterface;
use Cycle\ORM\RepositoryInterface;
use Illuminate\Contracts\Console\Kernel;
use Symfony\Component\Console\Output\BufferedOutput;

class DatabaseTestCase extends TestCase
{
    use DatabaseTransactions;

    protected static $dbInitialized = false;

    protected function setUp(): void
    {
        $this->afterApplicationCreated(function () {
            if (!static::$dbInitialized) {
                $response = new BufferedOutput();

                $this->app[Kernel::class]->call('db:wipe', [], $response);
                $this->app[Kernel::class]->call('cycle:schema:migrate', [], $response);

                echo $response->fetch();
                static::$dbInitialized = true;
            }
        });

        parent::setUp();
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
