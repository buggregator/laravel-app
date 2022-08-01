<?php

declare(strict_types=1);

namespace Modules\Project\Application\Queries\FindByName;

use App\Commands\FindProjectByName;
use App\Contracts\Query\QueryHandler;
use App\Exceptions\EntityNotFoundException;
use Modules\Project\Application\Resources\ProjectResource;
use Modules\Project\Domain\ProjectRepository;

class Handler implements QueryHandler
{
    public function __construct(private ProjectRepository $projectRepository)
    {
    }

    #[\App\Attributes\QueryBus\QueryHandler]
    public function __invoke(FindProjectByName $query): ProjectResource
    {
        $project = $this->projectRepository->findOne(['name' => $query->name]);
        if (! $project) {
            throw new EntityNotFoundException(
                sprintf('Project with given name [%s] was not found.', $query->name)
            );
        }

        return new ProjectResource($project);
    }
}
