<?php

namespace Modules\Transaction\Domain;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Modules\Transaction\Persistence\CycleOrmTransactionRepository;

#[Entity(
    repository: CycleOrmTransactionRepository::class
)]
class Transaction
{
    /**  @internal */
    public function __construct(
        #[Column(type: 'string')]
        private string $name,
        #[Column(type: 'integer', primary: true)]
        private ?int $id = null,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
