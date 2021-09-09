<?php
declare(strict_types=1);

namespace App\Websocket;

use Illuminate\Foundation\Application;
use Laravel\Octane\ApplicationGateway;
use Laravel\Octane\CurrentApplication;
use Laravel\Octane\DispatchesEvents;
use Laravel\Octane\Events\WorkerErrorOccurred;

class Worker
{
    use DispatchesEvents;

    protected $requestHandledCallbacks = [];

    public function __construct(protected Application $app)
    {
    }

    public function handle(string $event, ...$params): void
    {
        // We will clone the application instance so that we have a clean copy to switch
        // back to once the request has been handled. This allows us to easily delete
        // certain instances that got resolved / mutated during a previous request.
        CurrentApplication::set($sandbox = clone $this->app);

        $gateway = new ApplicationGateway($this->app, $sandbox);

        try {
            $this->invokeRequestHandledCallbacks($sandbox);

            $this->dispatchEvent($sandbox, new $event($this->app, $sandbox, ...$params));

            $sandbox->terminate();
        } catch (\Throwable $e) {
            $this->handleWorkerError($e, $sandbox);
        } finally {
            $sandbox->flush();

            // After the request handling process has completed we will unset some variables
            // plus reset the current application state back to its original state before
            // it was cloned. Then we will be ready for the next worker iteration loop.
            unset($gateway, $sandbox);

            CurrentApplication::set($this->app);
        }
    }

    /**
     * Invoke the request handled callbacks.
     *
     * @param \Illuminate\Foundation\Application $sandbox
     * @return void
     */
    protected function invokeRequestHandledCallbacks($sandbox): void
    {
        foreach ($this->requestHandledCallbacks as $callback) {
            $callback($sandbox);
        }
    }

    /**
     * Handle an uncaught exception from the worker.
     *
     * @param \Throwable $e
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function handleWorkerError(
        \Throwable  $e,
        Application $app
    ): void
    {
        $this->dispatchEvent($app, new WorkerErrorOccurred($e, $app));
    }
}
