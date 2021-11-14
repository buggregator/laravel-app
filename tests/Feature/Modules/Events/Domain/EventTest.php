<?php
declare(strict_types=1);

namespace Tests\Feature\Modules\Events\Domain;

use App\Domain\Entity\Json;
use Modules\Events\Domain\Event;
use Ramsey\Uuid\Uuid;
use Tests\DatabaseTestCase;

class EventTest extends DatabaseTestCase
{
    public function testPersistEntity()
    {
        $repository = $this->getRepositoryFor(Event::class);

        $uuid = Uuid::uuid4();

        $event = new Event($uuid, 'test', new Json([
            'foo' => 'bar'
        ]), new \DateTimeImmutable());

        $this->persistEntity($event);
        $this->cleanIdentityMap();

        $foundEvent = $repository->findByPK($uuid->toString());

        $this->assertTrue(
            $uuid->equals($foundEvent->getUuid())
        );

        $this->assertSame($event->getEvent(), $foundEvent->getEvent());
        $this->assertSame((string) $event->getPayload(), (string) $foundEvent->getPayload());
    }

}
