<?php
declare(strict_types=1);

namespace Modules\Events\Persistance;

use Infrastructure\CycleOrm\Repository;
use Modules\Events\Domain\EventRepository;

class CycleOrmEventRepository extends Repository implements EventRepository
{
    public function deleteAll(?string $type = null): void
    {
        // TODO: Implement deleteAll() method.
    }
}
