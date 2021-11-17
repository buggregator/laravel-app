<?php

declare(strict_types=1);

namespace Infrastructure\Bus\Query;

use App\Contracts\Query\Query;
use App\Contracts\Query\QueryBus;
use Infrastructure\Bus\MessageBusExceptionTrait;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class MessengerQueryBus implements QueryBus
{
    use MessageBusExceptionTrait;

    public function __construct(
        private MessageBusInterface $bus
    ) {
    }

    public function ask(Query $query)
    {
        try {
            $envelope = $this->bus->dispatch($query);

            /** @var HandledStamp $stamp */
            $stamp = $envelope->last(HandledStamp::class);

            return $stamp->getResult();
        } catch (NoHandlerForMessageException) {
            throw new QueryNotRegisteredException($query);
        } catch (HandlerFailedException $e) {
            $this->throwException($e);
        }
    }
}
