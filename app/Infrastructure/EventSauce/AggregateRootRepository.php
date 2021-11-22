<?php

declare(strict_types=1);

namespace Infrastructure\EventSauce;

use App\Contracts\Event\EventBus;
use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootRepository as AggregateRootRepositoryContract;
use EventSauce\EventSourcing\EventSourcedAggregateRootRepository;
use EventSauce\EventSourcing\MessageDispatcher;
use EventSauce\EventSourcing\MessageDispatcherChain;
use EventSauce\EventSourcing\MessageRepository;
use Illuminate\Contracts\Bus\Dispatcher;
use LogicException;

abstract class AggregateRootRepository implements AggregateRootRepositoryContract
{
    protected string $aggregateRoot;
    protected string $queue = '';
    private AggregateRootRepositoryContract $repository;

    public function __construct(
        private MessageRepository $messageRepository,
        private EventBus $eventBus,
        private Dispatcher $queueBus,
        private ConsumerLocator $consumers,
    ) {
        if (! is_a($this->aggregateRoot, AggregateRoot::class, true)) {
            throw new LogicException('You have to set an aggregate root before the repository can be initialized.');
        }

        $this->repository = new EventSourcedAggregateRootRepository(
            $this->aggregateRoot,
            $this->messageRepository,
            new MessageDispatcherChain(
                $this->buildLaravelMessageDispatcher(),
                new EventMessageDispatcher($this->eventBus),
            ),
        );
    }

    public function retrieve(AggregateRootId $aggregateRootId): object
    {
        return $this->repository->retrieve($aggregateRootId);
    }

    public function persist(object $aggregateRoot): void
    {
        $this->repository->persist($aggregateRoot);
    }

    public function persistEvents(AggregateRootId $aggregateRootId, int $aggregateRootVersion, object ...$events): void
    {
        $this->repository->persistEvents($aggregateRootId, $aggregateRootVersion, ...$events);
    }

    private function buildLaravelMessageDispatcher(): MessageDispatcher
    {
        $dispatcher = new QueueMessageDispatcher(
            $this->queueBus,
            $this->consumers
        );

        if ($this->queue) {
            $dispatcher->onQueue($this->queue);
        }

        return $dispatcher;
    }
}
