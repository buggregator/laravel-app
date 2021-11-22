<?php

declare(strict_types=1);

namespace Infrastructure\Bus;

use App\Attributes\CommandBus\CommandHandler;
use App\Attributes\Locator;
use App\Attributes\QueryBus\QueryHandler;
use App\Contracts\Command\Command;
use App\Contracts\Query\Query;
use function get_class;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use ReflectionMethod;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\Handler\HandlersLocatorInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

final class HandlersLocator implements HandlersLocatorInterface
{
    private array $commandHandlers = [];
    private array $queryHandlers = [];

    public function __construct(
        private Application $app,
        private Locator $attributesLocator,

    ) {
        foreach (
            $attributesLocator->findClassMethodsAttributes(
                'app',
                CommandHandler::class
            ) as $class => $attributes
        ) {
            $this->processCommandHandlerAttributes($class);
        }

        foreach ($attributesLocator->findClassMethodsAttributes('app', QueryHandler::class) as $class => $attributes) {
            $this->processQueryHandlerAttributes($class);
        }
    }

    /** @internal */
    public function getHandlers(Envelope $envelope): iterable
    {
        $seen = [];

        $handlers = $envelope->getMessage() instanceof Query
            ? $this->queryHandlers
            : $this->commandHandlers;

        foreach (self::listTypes($envelope) as $type) {
            foreach ($handlers[$type] ?? [] as $handler) {
                $handlerDescriptor = $this->buildHandlerDescriptor($handler);

                if (! $this->shouldHandle($envelope, $handlerDescriptor)) {
                    continue;
                }

                $name = $handlerDescriptor->getName();
                if (in_array($name, $seen)) {
                    continue;
                }

                $seen[] = $name;

                yield $handlerDescriptor;
            }
        }
    }

    /** @internal */
    public static function listTypes(Envelope $envelope): array
    {
        $class = get_class($envelope->getMessage());

        return [$class => $class]
            + class_parents($class)
            + class_implements($class)
            + ['*' => '*'];
    }

    private function shouldHandle(Envelope $envelope, HandlerDescriptor $handlerDescriptor): bool
    {
        if (null === $received = $envelope->last(ReceivedStamp::class)) {
            return true;
        }

        if (null === $expectedTransport = $handlerDescriptor->getOption('from_transport')) {
            return true;
        }

        return $received->getTransportName() === $expectedTransport;
    }

    /**
     * @param  array{0: class-string, 1: non-empty-string}  $handler
     *
     * @throws BindingResolutionException
     */
    private function buildHandlerDescriptor(array $handler): HandlerDescriptor
    {
        return new HandlerDescriptor([
            $this->app->make($handler[0]),
            $handler[1],
        ]);
    }

    /**
     * @throws BindingResolutionException
     */
    private function processCommandHandlerAttributes(ReflectionMethod $method): void
    {
        foreach ($method->getParameters() as $parameter) {
            if (is_a($parameter->getType()->getName(), Command::class, true)) {
                $this->commandHandlers[$parameter->getType()->getName()][] = [
                    $method->getDeclaringClass()->getName(),
                    $method->getName(),
                ];
            }
        }
    }

    /**
     * @throws BindingResolutionException
     */
    private function processQueryHandlerAttributes(ReflectionMethod $method): void
    {
        foreach ($method->getParameters() as $parameter) {
            if (is_a($parameter->getType()->getName(), Query::class, true)) {
                $this->queryHandlers[$parameter->getType()->getName()][] = [
                    $method->getDeclaringClass()->getName(),
                    $method->getName(),
                ];
            }
        }
    }
}
