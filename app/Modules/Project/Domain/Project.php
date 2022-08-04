<?php

namespace Modules\Project\Domain;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Modules\Project\Persistence\CycleOrmProjectRepository;

#[Entity(
    repository: CycleOrmProjectRepository::class
)]
class Project
{
    /**  @internal */
    public function __construct(
        #[Column(type: 'integer', primary: true)]
        private ?int $id,

        #[Column(type: 'string')]
        private string $name
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
