<?php

namespace App\Commands;

use App\Contracts\Query\Query;

abstract class AskEvents implements Query
{
    public function __construct(
        public ?string $type = null,
        public ?int $projectId = null,
        public ?int $transactionId = null,
    ) {
    }
}
