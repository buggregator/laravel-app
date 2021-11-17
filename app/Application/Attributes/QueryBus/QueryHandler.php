<?php

declare(strict_types=1);

namespace App\Attributes\QueryBus;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class QueryHandler
{
}
