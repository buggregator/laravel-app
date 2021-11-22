<?php

declare(strict_types=1);

namespace Infrastructure\Bus\Query;

use App\Contracts\Query\Query;
use RuntimeException;

final class QueryNotRegisteredException extends RuntimeException
{
    public function __construct(Query $query)
    {
        $queryClass = get_class($query);

        parent::__construct("The query <$queryClass> hasn't a query handler associated");
    }
}
