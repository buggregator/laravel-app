<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\StreamHandler;
use App\EventsRepository;
use App\Smtp\Connection as SmtpConnection;
use Illuminate\Console\Command;
use Laravel\Octane\Commands\Concerns\InteractsWithIO;
use NunoMaduro\Collision\Adapters\Laravel\ExceptionHandler;
use Swoole\Process;
use Swoole\Coroutine\Server\Connection;

class StartSmtpServerCommand extends Command
{
    use InteractsWithIO;

    protected $signature = 'smtp:start
                    {--host= : The IP address the server should bind to}
                    {--port= : The port the server should be available on}';

    protected $description = 'Run a websocket server';

    private StreamHandler $streamsHandler;

    public function handle(EventsRepository $events)
    {
        $this->streamsHandler = new StreamHandler($this->output, $this->getLaravel());

        // We use a process pool to create a Multi-process management module
        $pool = new Process\Pool(2);
        // By enabling this, each callback to WorkerStart will automatically create a coroutine for you
        $pool->set(['enable_coroutine' => true]);

        // Creates a Co\run context for you because enable_coroutine = true...
        $pool->on('WorkerStart', function ($pool, $id) use ($events) {
            $this->info('SMTP server started...');

            $port = $this->option('port') ?: 1025;
            $host = $this->option('host') ?: '0.0.0.0';

            $server = new \Swoole\Coroutine\Server($host, $port, false, true);

            Process::signal(SIGTERM, function () use ($server) {
                $server->shutdown();
            });

            // Receive a new connection request and automatically create a coroutine context
            $server->handle(function (Connection $connection) use ($events) {
                $start = microtime(true);

                $conn = new SmtpConnection($events, $connection);
                $conn->ready();

                $this->handleStream([
                    'type' => 'request',
                    'url' => $connection->exportSocket()->fd,
                    'method' => 'SMTP:CONNECTED',
                    'duration' => (microtime(true) - $start) * 1000,
                    'statusCode' => 200,
                    'memory' => memory_get_usage(),
                ]);

                while (true) {
                    $data = $connection->recv(1);
                    $start = microtime(true);

                    if ($data === '' || $data === false) {
                        $this->handleStream([
                            'type' => 'request',
                            'url' => '',
                            'method' => 'SMTP:CLOSED',
                            'duration' => (microtime(true) - $start) * 1000,
                            'statusCode' => 200,
                            'memory' => memory_get_usage(),
                        ]);

                        $connection->close();
                        break;
                    }

                    $this->handleStream([
                        'type' => 'request',
                        'url' => '',
                        'method' => 'SMTP:REQUEST',
                        'duration' => (microtime(true) - $start) * 1000,
                        'statusCode' => 200,
                        'memory' => memory_get_usage(),
                    ]);

                    try {
                        $conn->handle($data, fn(array $event) => $this->handleStream($event));
                    } catch (\Throwable $e) {
                        app(ExceptionHandler::class)->renderForConsole($this->output, $e);
                    }

                }
            });

            $server->start();
        });

        $pool->start();
    }

    public function handleStream($stream, $verbosity = null)
    {
        match ($stream['type']) {
            'request' => !$this->getLaravel()->environment('local', 'testing') ?: $this->requestInfo($stream, $verbosity),
            default => $this->streamsHandler->shouldBeSkipped($stream) ?: $this->streamsHandler->handle($stream),
        };
    }
}
