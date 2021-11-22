<?php

declare(strict_types=1);

namespace Modules\User\Persistence;

use Infrastructure\CycleOrm\Repository;
use Modules\User\Domain\UserRepository;

class CycleOrmUserRepository extends Repository implements UserRepository
{
}
