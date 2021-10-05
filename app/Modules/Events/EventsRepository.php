<?php
declare(strict_types=1);

namespace Modules\Events;

use App\Contracts\EventsRepository as EventsRepositoryContract;
use Modules\Events\Models\Event;

class EventsRepository implements EventsRepositoryContract
{
    public function find(string $uuid): ?array
    {
        $event = Event::find($uuid);

        if (!$event) {
            return null;
        }

        return array_merge($event->payload, ['timestamp' => $event->created_at->timestamp]);
    }

    public function store(array $event): string|int
    {
        Event::create([
            'uuid' => $event['uuid'],
            'type' => $event['type'],
            'payload' => $event
        ]);

        return $event['uuid'];
    }

    public function delete(string $uuid): void
    {
        Event::where('uuid', $uuid)->delete();
    }

    public function all(string ...$type): array
    {
        return Event::oldest()
            ->whereIn('type', $type)
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
