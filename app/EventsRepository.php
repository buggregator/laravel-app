<?php
declare(strict_types=1);

namespace App;

use App\Models\Event;

class EventsRepository
{
    public function store(array $event): void
    {
        Event::create([
            'id' => $event['uuid'],
            'event' => $event
        ]);
    }

    public function delete(string $uuid): void
    {
        Event::where('id', $uuid)->delete();
    }

    public function all(): array
    {
        return Event::oldest()->get()->map(function (Event $event) {
            return array_merge($event->event, ['timestamp' => $event->created_at->timestamp]);
        })->all();
    }

    public function clear(): void
    {
        Event::query()->truncate();
    }
}
