<?php

declare(strict_types=1);

namespace Infrastructure\Bus\Command;

use App\Contracts\Command\Command;
use App\Contracts\Command\CommandBus;
use Infrastructure\Bus\MessageBusExceptionTrait;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Throwable;

final class MessengerCommandBus implements CommandBus
{
    use MessageBusExceptionTrait;

    public function __construct(
        private MessageBusInterface $bus
    ) {
    }

    /**
     * @throws Throwable
     */
    public function dispatch(Command $command)
    {
        try {
            $envelope = $this->bus->dispatch($command);

            /** @var HandledStamp $stamp */
            $stamp = $envelope->last(HandledStamp::class);

            return $stamp->getResult();
        } catch (NoHandlerForMessageException) {
            throw new CommandNotRegisteredException($command);
        } catch (HandlerFailedException $e) {
            $this->throwException($e);
        }
    }
}
