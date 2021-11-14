<?php
declare(strict_types=1);

namespace Modules\Events\Application\Commands\StoreEvent;

use Ramsey\Uuid\UuidInterface;

class Command implements \App\Contracts\Command\Command
{
    public function __construct(
        public string $type,
        public UuidInterface $uuid,
        public \DateTimeImmutable $date,
        public array $payload,
    )
    {
    }
}
