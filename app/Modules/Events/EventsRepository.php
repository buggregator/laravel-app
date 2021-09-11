<?php
declare(strict_types=1);

namespace Modules\Events;

use App\Contracts\EventsRepository as EventsRepositoryContract;
use Modules\Events\Models\Event;

class EventsRepository implements EventsRepositoryContract
{
    public function store(array $event): void
    {
        Event::create([
            'id' => $event['payload']['uuid'],
            'event' => $event['event'],
            'payload' => $event['payload']
        ]);
    }

    public function delete(string $uuid): void
    {
        Event::where('id', $uuid)->delete();
    }

    public function all(): array
    {
        return Event::oldest()->get()->map(function (Event $event) {
            return [
                'event' => $event->event,
                'payload' => array_merge($event->payload, ['timestamp' => $event->created_at->timestamp])
            ];
        })->all();
    }

    public function clear(): void
    {
        Event::query()->truncate();
    }
}
