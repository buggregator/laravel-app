<?php

declare(strict_types=1);

namespace Infrastructure\CycleOrm;

use Cycle\ORM\Select\Repository as BaseRepository;
use Illuminate\Support\Collection;

class Repository extends BaseRepository
{
    public function findAll(array $scope = [], array $orderBy = [], $limit = null, $offset = null): Collection
    {
        $select = $this->select()->where($scope)->orderBy($orderBy);
        if (! is_null($limit)) {
            $select = $select->limit($limit);
        }
        if (! is_null($offset)) {
            $select = $select->offset($offset);
        }

        return $this->newCollection($select->fetchAll());
    }

    public function count(array $scope = []): int
    {
        return $this->select()->where($scope)->count();
    }

    private function newCollection(array $items): Collection
    {
        return new Collection($items);
    }
}
