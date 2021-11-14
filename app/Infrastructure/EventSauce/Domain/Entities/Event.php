<?php
declare(strict_types=1);

namespace Infrastructure\EventSauce\Domain\Entities;

use App\Domain\Entity\Json;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Ramsey\Uuid\UuidInterface;

#[Entity(role: 'stored_events')]
class Event
{
    public function __construct(
        #[Column(name: 'event_id', type: 'string(36)', typecast: 'uuid', primary: true)]
        private UuidInterface $id,

        #[Column(name: 'event_type', type: 'string')]
        private string $type,

        #[Column(name: 'aggregate_root_id', type: 'string')]
        private string $aggregateId,

        #[Column(type: 'int')]
        private int $version,

        #[Column(type: 'json', typecast: Json::class)]
        private Json $payload
    )
    {}


    public function getPayload(): Json
    {
        return $this->payload;
    }
}
