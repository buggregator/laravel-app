<?php

declare(strict_types=1);

namespace App\Contracts\Query;

interface QueryBus
{
    public function ask(Query $query);
}
