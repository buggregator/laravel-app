<?php
declare(strict_types=1);

namespace App;

use Laravel\Octane\Octane;
use Ramsey\Uuid\Uuid;

class EventsRepository
{
    private Octane $octane;

    public function __construct(Octane $octane)
    {
        $this->octane = $octane;
    }

    public function store(array $event): void
    {
//        $uuid = Uuid::uuid4();

//        $this->octane->table('events')->set($uuid->toString(), [
//            'timestamp' => microtime(true),
//            'event' => json_encode($event)
//        ]);
    }

    public function all(): \Generator
    {
        foreach ($this->octane->table('events') as $row) {
            yield json_decode($row['event']);
        }
    }
}
