<?php

declare(strict_types=1);

namespace Modules\IncommingEvents\Domain;

use App\Domain\ValueObjects\Uuid;
use DateTimeImmutable;
use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;
use Illuminate\Support\Carbon;
use Modules\IncommingEvents\Domain\Events\EventWasDeleted;
use Modules\IncommingEvents\Domain\Events\EventWasReceived;

final class EventProcess implements AggregateRoot
{
    use AggregateRootBehaviour;

    private string $type;
    private array $payload;
    private DateTimeImmutable $date;
    private bool $deleted = false;

    public static function received(Uuid $uuid, string $type, array $payload, int $timestamp): static
    {
        $process = new static($uuid);
        $process->recordThat(
            new EventWasReceived($uuid, $type, $payload, $timestamp)
        );

        return $process;
    }

    public function applyEventWasReceived(EventWasReceived $event): void
    {
        $this->type = $event->type;
        $this->payload = $event->payload;
        $this->date = Carbon::createFromTimestamp($event->timestamp)->toDateTimeImmutable();
    }

    public function delete(): self
    {
        $this->recordThat(
            new EventWasDeleted($this->aggregateRootId())
        );

        return $this;
    }

    public function applyEventWasDeleted(EventWasDeleted $event): void
    {
        $this->deleted = true;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function payload(): array
    {
        return $this->payload;
    }

    public function date(): DateTimeImmutable
    {
        return $this->date;
    }
}
