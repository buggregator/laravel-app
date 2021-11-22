<?php

declare(strict_types=1);

namespace Infrastructure\CycleOrm;

use Cycle\ORM\Select\Repository as BaseRepository;
use Illuminate\Support\Collection;

class Repository extends BaseRepository
{
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
}
