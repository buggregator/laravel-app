<?php

declare(strict_types=1);

namespace App\Commands;

use App\Contracts\Query\Query;

class FinUserByUsername implements Query
{
    // TODO: use readonly property
    public function __construct(
        public string $username
    ) {
    }
}
