<?php

namespace Modules\Transaction;

use App\Providers\DomainServiceProvider;
use Cycle\ORM\ORMInterface;
use Modules\Transaction\Domain\Transaction;
use Modules\Transaction\Domain\TransactionRepository;

class ServiceProvider extends DomainServiceProvider
{
    public function boot()
    {
        $this->app->bind(TransactionRepository::class, function () {
            return clone $this->app[ORMInterface::class]->getRepository(Transaction::class);
        });
    }
}
