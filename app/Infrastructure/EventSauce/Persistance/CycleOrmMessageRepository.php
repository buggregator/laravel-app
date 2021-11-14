<?php
declare(strict_types=1);

namespace Infrastructure\EventSauce\Persistance;

use App\Domain\Entity\Json;
use Cycle\Database\DatabaseProviderInterface;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\TransactionInterface;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\Serialization\MessageSerializer;
use Generator;
use Infrastructure\EventSauce\Domain\Entities\Event;
use Infrastructure\EventSauce\Domain\EventRepository;
use Ramsey\Uuid\Uuid;

class CycleOrmMessageRepository implements EventRepository
{
    public function __construct(
        private ORMInterface         $orm,
        private TransactionInterface $transaction,
        private MessageSerializer    $serializer
    )
    {

    }

    public function persist(Message ...$messages): void
    {
        foreach ($messages as $message) {
            $payload = $this->serializer->serializeMessage($message);

            $eventId = isset($payload['headers'][Header::EVENT_ID])
                ? Uuid::fromString($payload['headers'][Header::EVENT_ID])
                : Uuid::uuid4();

            $this->orm->getRepository(Event::class)->store(
                entity: new Event(
                    $eventId,
                    $payload['headers'][Header::EVENT_TYPE],
                    $payload['headers'][Header::AGGREGATE_ROOT_ID] ?? null,
                    $payload['headers'][Header::AGGREGATE_ROOT_VERSION] ?? 0,
                    new Json($payload)
                ),
                run: false
            );
        }

        $this->transaction->run();
    }

    public function retrieveAll(AggregateRootId $id): Generator
    {
        $collection = $this->orm->getRepository(Event::class)
            ->findAll(['aggregateId' => $id->toString()], ['version' => 'ASC']);

        foreach ($collection as $event) {
            yield $this->serializer->unserializePayload($event->getPayload()->toArray());
        }

        return $collection->count();
    }

    public function retrieveAllAfterVersion(AggregateRootId $id, int $aggregateRootVersion): Generator
    {
        $collection = $this->orm->getRepository(Event::class)
            ->findAll([
                'aggregateId' => $id->toString(),
                'version' => ['>' => $aggregateRootVersion]
            ], ['version' => 'ASC']);

        foreach ($collection as $event) {
            yield $this->serializer->unserializePayload($event->getPayload()->toArray());
        }

        return $collection->count();
    }
}
