<?php
declare(strict_types=1);

namespace Modules\Events\Domain;

use Cycle\ORM\RepositoryInterface;

interface EventRepository extends RepositoryInterface
{
    public function deleteAll(?string $type = null): void;
}
