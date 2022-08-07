<?php

namespace App\Commands;

use App\Contracts\Query\Query;

class FindTransactionByName implements Query
{
    public function __construct(
        public string $name
    ) {
    }
}
