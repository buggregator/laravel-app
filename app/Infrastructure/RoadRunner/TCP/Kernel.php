<?php

declare(strict_types=1);

namespace Infrastructure\RoadRunner\TCP;

use App\Contracts\TCP\Response;
use Illuminate\Contracts\Foundation\Application;
use Spiral\RoadRunner\Tcp\Request;
use Symfony\Component\Console\Output\OutputInterface;

final class Kernel implements \App\Contracts\TCP\Kernel
{
    /**
     * @var array<non-empty-string, non-empty-string>
     */
    private array $handlers = [];

    /**
     * The bootstrap classes for the application.
     *
     * @var string[]
     */
    protected $bootstrappers = [
        \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
        \Illuminate\Foundation\Bootstrap\LoadConfiguration::class,
        \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
        \Illuminate\Foundation\Bootstrap\RegisterFacades::class,
        \Illuminate\Foundation\Bootstrap\RegisterProviders::class,
        \Illuminate\Foundation\Bootstrap\BootProviders::class,
    ];

    /**
     * Create a new TCP kernel instance.
     */
    public function __construct(private Application $app)
    {
    }

    /**
     * Bootstrap the application for TCP requests.
     */
    public function bootstrap(): void
    {
        if (! $this->app->hasBeenBootstrapped()) {
            $this->app->bootstrapWith($this->bootstrappers());
        }
    }

    public function addHandler(string $server, string $handler): void
    {
        $this->handlers[$server] = $handler;
    }

    /**
     * Handle an incoming TCP request.
     */
    public function handle(Request $request, OutputInterface $output): ?Response
    {
        $this->app->instance(Request::class, $request);
        $this->bootstrap();

        if (! isset($this->handlers[$request->server])) {
            throw new \RuntimeException("Handler for server [$request->server] not found.");
        }

        $handler = $this->app->make($this->handlers[$request->server]);

        return $handler->handle($request, $output);
    }

    /**
     * Call the terminate method.
     */
    public function terminate(Request $request, Response $response): void
    {
        $this->app->terminate();
    }

    /**
     * Get the bootstrap classes for the application.
     */
    protected function bootstrappers(): array
    {
        return $this->bootstrappers;
    }
}
