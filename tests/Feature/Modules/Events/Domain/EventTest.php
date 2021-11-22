<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Events\Domain;

use App\Domain\Entity\Json;
use App\Domain\ValueObjects\Uuid;
use Modules\Events\Domain\Event;
use Tests\DatabaseTestCase;

class EventTest extends DatabaseTestCase
{
    public function testPersistEntity()
    {
        $repository = $this->getRepositoryFor(Event::class);

        $uuid = Uuid::generate();

        $event = new Event($uuid, 'test', new Json([
            'foo' => 'bar',
        ]), new \DateTimeImmutable());

        $this->persistEntity($event);
        $this->cleanIdentityMap();

        $foundEvent = $repository->findByPK($uuid->toString());

        $this->assertTrue(
            $uuid->equals($foundEvent->getUuid())
        );

        $this->assertSame($event->getType(), $foundEvent->getType());
        $this->assertSame((string) $event->getPayload(), (string) $foundEvent->getPayload());
    }
}
