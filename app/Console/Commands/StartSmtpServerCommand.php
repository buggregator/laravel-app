<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\StreamHandler;
use App\EventsRepository;
use App\Smtp\Connection as SmtpConnection;
use App\TCP\Server;
use Closure;
use Illuminate\Console\Command;
use Laravel\Octane\Commands\Concerns\InteractsWithIO;
use NunoMaduro\Collision\Adapters\Laravel\ExceptionHandler;
use Swoole\Coroutine\Server\Connection;

class StartSmtpServerCommand extends Command
{
    use InteractsWithIO;

    protected $signature = 'smtp:start
                    {--host= : The IP address the server should bind to}
                    {--port= : The port the server should be available on}';

    protected $description = 'Run a websocket server';

    private StreamHandler $streamsHandler;
    private SmtpConnection $smtpConnection;

    public function handle(EventsRepository $events)
    {
        $this->smtpConnection = new SmtpConnection($events);
        $this->streamsHandler = new StreamHandler($this->output, $this->getLaravel());

        $server = new Server(
            $this->option('host') ?: '0.0.0.0',
            $this->option('port') ?: 1025,
            'SMTP'
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
            $this->handleStream([
                'type' => 'request',
                'url' => '',
                'method' => 'SMTP:REQUEST',
                'duration' => (microtime(true) - $start) * 1000,
                'statusCode' => 200,
                'memory' => memory_get_usage(),
            ]);

            try {
                $this->smtpConnection->handle($data, fn(array $event) => $this->handleStream($event));
            } catch (\Throwable $e) {
                app(ExceptionHandler::class)->renderForConsole($this->output, $e);
            }
        };
    }

    private function onConnect(): Closure
    {
        return function (Connection $conn, float $start) {
            $this->smtpConnection->ready($conn);

            $this->handleStream([
                'type' => 'request',
                'url' => $conn->exportSocket()->fd,
                'method' => 'SMTP:CONNECTED',
                'duration' => (microtime(true) - $start) * 1000,
                'statusCode' => 200,
                'memory' => memory_get_usage(),
            ]);
        };
    }

    private function onClose(): Closure
    {
        return function (Connection $conn, float $start) {
            $this->smtpConnection->ready($conn);

            $this->handleStream([
                'type' => 'request',
                'url' => '',
                'method' => 'SMTP:CLOSED',
                'duration' => (microtime(true) - $start) * 1000,
                'statusCode' => 200,
                'memory' => memory_get_usage(),
            ]);
        };
    }
}
