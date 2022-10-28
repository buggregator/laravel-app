<?php

declare(strict_types=1);

namespace Modules\Events\Domain;

use App\Domain\Entity\Json;
use App\Domain\ValueObjects\Uuid;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use DateTimeImmutable;
use Modules\Events\Persistence\CycleOrmEventRepository;

#[Entity(
    repository: CycleOrmEventRepository::class
)]
class Event
{
    /**  @internal */
    public function __construct(
        #[Column(primary: true, type: 'string(36)', typecast: 'uuid')]
        private Uuid $uuid,

        #[Column(type: 'string')]
        private string $type,

        #[Column(type: 'json', typecast: 'json')]
        private Json $payload,

        #[Column(type: 'datetime')]
        private DateTimeImmutable $date,

        #[Column(type: 'integer', nullable: false)]
        private int $projectId,

        #[Column(type: 'integer', nullable: true)]
        private ?int $transactionId
    ) {
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPayload(): Json
    {
        return $this->payload;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function getTransactionId(): ?int
    {
        return $this->transactionId;
    }
}
