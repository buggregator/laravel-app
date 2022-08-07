<?php

namespace Modules\Transaction\Persistence;

use Infrastructure\CycleOrm\Repository;
use Modules\Transaction\Domain\TransactionRepository;

class CycleOrmTransactionRepository extends Repository implements TransactionRepository
{
}
