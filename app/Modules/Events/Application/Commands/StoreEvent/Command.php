<?php

declare(strict_types=1);

namespace Modules\Events\Application\Commands\StoreEvent;

use App\Domain\ValueObjects\Uuid;
use DateTimeImmutable;

class Command implements \App\Contracts\Command\Command
{
    // TODO: use readonly property
    public function __construct(
        public string $type,
        public Uuid $uuid,
        public DateTimeImmutable $date,
        public array $payload,
    ) {
    }
}
