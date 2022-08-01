<?php

declare(strict_types=1);

namespace Modules\Project;

use App\Providers\DomainServiceProvider;
use Cycle\ORM\ORMInterface;
use Modules\Project\Domain\Project;
use Modules\Project\Domain\ProjectRepository;

final class ServiceProvider extends DomainServiceProvider
{
    public function boot()
    {
        $this->app->bind(ProjectRepository::class, function () {
            return clone $this->app[ORMInterface::class]->getRepository(Project::class);
        });
    }
}
