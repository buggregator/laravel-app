<?php

declare(strict_types=1);

namespace Modules\Events\Persistence;

use Infrastructure\CycleOrm\Repository;
use Modules\Events\Domain\EventRepository;

class CycleOrmEventRepository extends Repository implements EventRepository
{
}
