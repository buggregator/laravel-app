<?php

namespace Modules\Project\Application\Queries\FindAllProjects;

use App\Commands\FindAllProjects;
use App\Contracts\Query\QueryHandler;
use Modules\Project\Application\Resources\ProjectCollection;
use Modules\Project\Domain\ProjectRepository;

class Handler implements QueryHandler
{
    public function __construct(
        private ProjectRepository $projects
    ) {
    }

    #[\App\Attributes\QueryBus\QueryHandler]
    public function __invoke(FindAllProjects $query): iterable
    {
        return ProjectCollection::make(
            $this->projects->findAll()
        );
    }
}
