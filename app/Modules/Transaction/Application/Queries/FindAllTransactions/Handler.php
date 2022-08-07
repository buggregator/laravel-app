<?php

namespace Modules\Transaction\Application\Queries\FindAllTransactions;

use App\Commands\FindAllTransactions;
use App\Contracts\Query\QueryHandler;
use Modules\Transaction\Application\Resources\TransactionCollection;
use Modules\Transaction\Domain\TransactionRepository;

class Handler implements QueryHandler
{
    public function __construct(
        private TransactionRepository $transactions
    ) {
    }

    #[\App\Attributes\QueryBus\QueryHandler]
    public function __invoke(FindAllTransactions $query): iterable
    {
        return TransactionCollection::make(
            $this->transactions->findAll()
        );
    }
}
