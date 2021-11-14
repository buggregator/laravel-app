<?php
declare(strict_types=1);

namespace App\Commands;

use App\Contracts\Query\Query;

final class FindAllEvents implements Query
{
    /**
     * TODO: should be read only
     */
    public function __construct(
        public ?string $type = null
    )
    {

    }
}
