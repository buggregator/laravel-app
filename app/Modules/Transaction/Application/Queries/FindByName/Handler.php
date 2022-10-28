<?php

declare(strict_types=1);

namespace Modules\Transaction\Application\Queries\FindByName;

use App\Commands\FindTransactionByName;
use App\Contracts\Query\QueryHandler;
use App\Exceptions\EntityNotFoundException;
use Modules\Transaction\Application\Resources\TransactionResource;
use Modules\Transaction\Domain\TransactionRepository;

class Handler implements QueryHandler
{
    public function __construct(private TransactionRepository $transactionRepository)
    {
    }

    #[\App\Attributes\QueryBus\QueryHandler]
    public function __invoke(FindTransactionByName $query): TransactionResource
    {
        $transaction = $this->transactionRepository->findOne(['name' => $query->name]);
        if (! $transaction) {
            throw new EntityNotFoundException(
                sprintf('Transaction with given name [%s] was not found.', $query->name)
            );
        }

        return new TransactionResource($transaction);
    }
}
