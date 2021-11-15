<?php
declare(strict_types=1);

namespace Infrastructure\CycleOrm;

use Cycle\ORM\Select\Repository as BaseRepository;
use Cycle\ORM\Transaction;
use Cycle\ORM\TransactionInterface;
use Cycle\ORM\Select;
use Illuminate\Support\Collection;

class Repository extends BaseRepository
{
    public function __construct(
        Select                       $select,
        private TransactionInterface $transaction
    )
    {
        parent::__construct($select);
    }

    public function findAll(array $scope = [], array $orderBy = []): Collection
    {
        return $this->newCollection(
            $this->select()->where($scope)->orderBy($orderBy)->fetchAll()
        );
    }

    private function newCollection(array $items): Collection
    {
        return new Collection($items);
    }

    public function store(object $entity, bool $cascade = true, bool $run = true): void
    {
        $this->transaction->persist(
            $entity,
            $cascade ? Transaction::MODE_CASCADE : Transaction::MODE_ENTITY_ONLY
        );

        if ($run) {
            $this->transaction->run(); // transaction is clean after run
        }
    }

    public function delete(object $entity, bool $cascade = true, bool $run = true)
    {
        $this->transaction->delete(
            $entity,
            $cascade ? Transaction::MODE_CASCADE : Transaction::MODE_ENTITY_ONLY
        );

        if ($run) {
            $this->transaction->run(); // transaction is clean after run
        }
    }

}
