<?php
declare(strict_types=1);

namespace Modules\Events;

use App\Contracts\EventsRepository as EventsRepositoryContract;
use Modules\Events\Models\Event;
use Ramsey\Uuid\UuidInterface;

class EventsRepository implements EventsRepositoryContract
{
    public function find(UuidInterface $uuid): ?array
    {
        $event = Event::find($uuid->toString());

        if (!$event) {
            return null;
        }

        return array_merge($event->payload, ['timestamp' => $event->created_at->timestamp]);
    }

    public function store(array $event): string
    {
        Event::create([
            'uuid' => $event['uuid'],
            'type' => $event['type'],
            'payload' => $event
        ]);

        return $event['uuid'];
    }

    public function delete(UuidInterface $uuid): void
    {
        Event::where('uuid', $uuid->toString())->delete();
    }

    public function all(string ...$type): array
    {
        return Event::oldest()
            ->when(!empty($type), fn ($query) => $query->where('type', $type))
            ->get()
            ->map(function (Event $event) {
                return array_merge($event->payload, ['timestamp' => $event->created_at->timestamp]);
            })
            ->toArray();
    }

    public function clear(): void
    {
        Event::query()->truncate();
    }
}
