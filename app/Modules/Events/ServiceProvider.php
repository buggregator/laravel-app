<?php

declare(strict_types=1);

namespace Modules\Events;

use App\Providers\DomainServiceProvider;
use Cycle\ORM\ORMInterface;
use Modules\Events\Domain\Event;
use Modules\Events\Domain\EventRepository;

final class ServiceProvider extends DomainServiceProvider
{
    public function boot()
    {
        $this->app->bind(EventRepository::class, function () {
            return clone $this->app[ORMInterface::class]->getRepository(Event::class);
        });
    }
}
