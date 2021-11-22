<?php

declare(strict_types=1);

namespace App\Commands;

use App\Contracts\Query\Query;
use App\Domain\ValueObjects\Uuid;

class FindEventByUuid implements Query
{
    // TODO: use readonly property
    public function __construct(
        public Uuid $uuid
    ) {
    }
}
