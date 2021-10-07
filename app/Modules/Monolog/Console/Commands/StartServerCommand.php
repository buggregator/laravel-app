<?php
declare(strict_types=1);

namespace Modules\Monolog\Console\Commands;

use Illuminate\Contracts\Events\Dispatcher;
use Interfaces\Console\StreamHandler;
use Modules\Monolog\Connection as MonologConnection;
use App\TCP\Server;
use Closure;
use Illuminate\Console\Command;
use Laravel\Octane\Commands\Concerns\InteractsWithIO;
use Swoole\Coroutine\Server\Connection;

class StartServerCommand extends Command
{
    use InteractsWithIO;

    protected $signature = 'monolog:start
                    {--host= : The IP address the server should bind to}
                    {--port= : The port the server should be available on}';

    protected $description = 'Run monolog server';

    private MonologConnection $connection;
    private StreamHandler $streamsHandler;

    public function __construct(private Dispatcher $events)
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->connection = new MonologConnection();
        $this->streamsHandler = new StreamHandler($this->output, $this->getLaravel());

        $server = new Server(
            $this->option('host') ?: '0.0.0.0',
            (int) ($this->option('port') ?: 9913),
            'Monolog'
        );

        $server
            ->onReceive($this->onReceive())
            ->onConnect($this->onConnect())
            ->onClose($this->onClose())
            ->run($this->output);
    }

    public function handleStream($stream, $verbosity = null)
    {
        match ($stream['type']) {
            'request' => !$this->getLaravel()->environment('local', 'testing') ?: $this->requestInfo($stream, $verbosity),
            default => $this->streamsHandler->shouldBeSkipped($stream) ?: $this->streamsHandler->handle($stream),
        };
    }

    private function onReceive(): Closure
    {
        return function (Connection $conn, string $data, float $start) {

            $this->connection->handleMessage($data, function (array $event) {
                $this->handleStream($event);
            }, function (string $error) {
                $this->error($error);
            });

            $this->handleStream([
                'type' => 'request',
                'url' => '',
                'method' => 'MONOLOG:DATA',
                'duration' => (microtime(true) - $start) * 1000,
                'statusCode' => 200,
                'memory' => memory_get_usage(),
            ]);
        };
    }

    private function onConnect(): Closure
    {
        return function (Connection $conn, float $start) {

            $this->connection->ready($conn);

            $this->handleStream([
                'type' => 'request',
                'url' => '',
                'method' => 'MONOLOG:CONNECTED',
                'duration' => (microtime(true) - $start) * 1000,
                'statusCode' => 200,
                'memory' => memory_get_usage(),
            ]);
        };
    }

    private function onClose(): Closure
    {
        return function (Connection $conn, float $start) {
            $this->handleStream([
                'type' => 'request',
                'url' => '',
                'method' => 'MONOLOG:CLOSED',
                'duration' => (microtime(true) - $start) * 1000,
                'statusCode' => 200,
                'memory' => memory_get_usage(),
            ]);
        };
    }
}
