<?php
declare(strict_types=1);

namespace App\TCP;

use Closure;
use Swoole\Process;
use Swoole\Coroutine\Server\Connection;
use Symfony\Component\Console\Output\OutputInterface;

class Server
{
    /** @var array<Closure> */
    private array $onReceiveCallbacks = [];

    /** @var array<Closure> */
    private array $onConnectCallbacks = [];

    /** @var array<Closure> */
    private array $onCloseCallbacks = [];

    public function __construct(
        private string $host,
        private int    $port,
        private string $name,
        private int    $pool = 1
    )
    {
    }

    public function onReceive(Closure $callback): self
    {
        $this->onReceiveCallbacks[] = $callback;
        return $this;
    }

    public function onConnect(Closure $callback): self
    {
        $this->onConnectCallbacks[] = $callback;
        return $this;
    }

    public function onClose(Closure $callback): self
    {
        $this->onCloseCallbacks[] = $callback;
        return $this;
    }

    public function run(OutputInterface $output)
    {
        // We use a process pool to create a Multi-process management module
        $pool = new Process\Pool($this->pool);

        // By enabling this, each callback to WorkerStart will automatically create a coroutine for you
        $pool->set(['enable_coroutine' => true]);

        // Creates a Co\run context for you because enable_coroutine = true...
        $pool->on('WorkerStart', function ($pool, $id) use ($output) {
            $output->info(sprintf('%s server started...', $this->name ?? 'TCP'));

            $server = new \Swoole\Coroutine\Server($this->host, $this->port, false, true);

            Process::signal(SIGTERM, function () use ($server) {
                $server->shutdown();
            });

            // Receive a new connection request and automatically create a coroutine context
            $server->handle(function (Connection $connection) {
                $start = microtime(true);

                foreach ($this->onConnectCallbacks as $callback) {
                    $callback($connection, $start);
                }

                while (true) {
                    $data = $connection->recv(1);

                    if ($data === '' || $data === false) {
                        foreach ($this->onCloseCallbacks as $callback) {
                            $callback($connection, $start);
                        }

                        $connection->close();
                        break;
                    }

                    foreach ($this->onReceiveCallbacks as $callback) {
                        $callback($connection, $data, $start);
                    }
                }
            });

            $server->start();
        });

        $pool->start();
    }
}
