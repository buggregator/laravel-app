<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\EventsRepository;
use Illuminate\Console\Command;
use Laravel\Octane\Commands\Concerns\InteractsWithIO;
use Swoole\Process;
use Swoole\Coroutine\Server\Connection;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\VarDumper\Command\Descriptor\CliDescriptor;
use Symfony\Component\VarDumper\Dumper\CliDumper;

class StartDumpServerCommand extends Command
{
    use InteractsWithIO;

    protected $signature = 'dump-server:start
                    {--host= : The IP address the server should bind to}
                    {--port= : The port the server should be available on}';

    protected $description = 'Run a websocket server';

    public function handle(EventsRepository $events)
    {
        // We use a process pool to create a Multi-process management module
        $pool = new Process\Pool(1);
        // By enabling this, each callback to WorkerStart will automatically create a coroutine for you
        $pool->set(['enable_coroutine' => true]);

        $descriptor = new CliDescriptor(new CliDumper());

        // Creates a Co\run context for you because enable_coroutine = true...
        $pool->on('WorkerStart', function ($pool, $id) use ($events, $descriptor) {
            $this->info('Var dumper server started...');

            $port = $this->option('port') ?: 9912;
            $host = $this->option('host') ?: '0.0.0.0';

            $server = new \Swoole\Coroutine\Server($host, $port, false, true);

            Process::signal(SIGTERM, function () use ($server) {
                $server->shutdown();
            });

            // Receive a new connection request and automatically create a coroutine context
            $server->handle(function (Connection $connection) use ($events, $descriptor) {
                $start = microtime(true);

                $connection = new \App\VarDumper\Connection($events, $connection);
                $clientId = $connection->clientId();

                if (app()->environment('local', 'testing')) {
                    $this->requestInfo([
                        'type' => 'request',
                        'url' => $clientId,
                        'method' => 'VAR-DUMP:CONNECTED',
                        'duration' => (microtime(true) - $start) * 1000,
                        'statusCode' => 200,
                        'memory' => memory_get_usage(),
                    ]);
                }

                $connection->handleMessage(function (array $payload) use ($descriptor, $clientId, $start) {
                    try {
                        [$data, $context] = $payload;
                        $descriptor->describe(new SymfonyStyle($this->input, $this->output), $data, $context, $clientId);
                    } catch (\Throwable $e) {
                        $this->error($e->getMessage());
                    }
                }, function (string $error) {
                    $this->error($error);
                });


                $connection->close();
                if (app()->environment('local', 'testing')) {
                    $this->requestInfo([
                        'type' => 'request',
                        'url' => '',
                        'method' => 'VAR-DUMP:CLOSED',
                        'duration' => (microtime(true) - $start) * 1000,
                        'statusCode' => 200,
                        'memory' => memory_get_usage(),
                    ]);
                }
            });

            $server->start();
        });

        $pool->start();
    }

    private function parseData(string $data): array
    {
        return array_filter(explode("\n", $data));
    }
}
