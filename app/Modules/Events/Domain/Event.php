<?php
declare(strict_types=1);

namespace Modules\Events\Domain;

use App\Domain\Entity\Json;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Modules\Events\Persistance\CycleOrmEventRepository;
use Ramsey\Uuid\UuidInterface;

#[Entity(
    repository: CycleOrmEventRepository::class
)]
class Event
{
    /**  @internal */
    public function __construct(
        #[Column(primary: true, type: 'string(36)', typecast: 'uuid')]
        private UuidInterface $uuid,

        #[Column(type: 'string')]
        private string $event,

        #[Column(type: 'json', typecast: Json::class)]
        private Json $payload,

        #[Column(type: 'datetime')]
        private \DateTimeImmutable $date
    )
    {
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getEvent(): string
    {
        return $this->event;
    }

    public function getPayload(): Json
    {
        return $this->payload;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }
}
