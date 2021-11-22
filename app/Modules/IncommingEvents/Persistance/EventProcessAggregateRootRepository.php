<?php

declare(strict_types=1);

namespace Modules\IncommingEvents\Persistance;

use EventSauce\EventSourcing\AggregateRootId;
use Infrastructure\EventSauce\AggregateRootRepository;
use Modules\IncommingEvents\Domain\EventProcess;

/**
 * @method EventProcess retrieve(AggregateRootId $id)
 * @method void persist(EventProcess $event)
 */
class EventProcessAggregateRootRepository extends AggregateRootRepository
{
    protected string $aggregateRoot = EventProcess::class;

    public function retrieveAll(): object
    {
    }
}
