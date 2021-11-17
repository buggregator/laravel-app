<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObjects\Uuid;
use Cycle\Database\DatabaseInterface;
use Cycle\ORM\Parser\TypecastInterface;

final class ExtendedTypecast implements TypecastInterface
{
    public function __construct(
        private array $rules,
        private DatabaseInterface $database
    ) {
    }

    public function cast(array $values): array
    {
        foreach ($this->rules as $column => $rule) {
            if (! isset($values[$column])) {
                continue;
            }

            if ($rule === 'uuid') {
                $values[$column] = Uuid::fromString($values[$column]);
            }
        }

        return $values;
    }
}
