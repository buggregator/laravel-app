<?php

declare(strict_types=1);

namespace App\Commands;

use App\Contracts\Query\Query;

final class FindAllEvents implements Query
{
    // TODO: use readonly property
    public function __construct(
        public ?string $type = null
    ) {
    }
}
