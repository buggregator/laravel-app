<?php

namespace Interfaces\Console\Commands\Server;

use Interfaces\Console\StreamHandler;
use Laravel\Octane\Swoole\ServerProcessInspector;
use Laravel\Octane\Swoole\ServerStateFile;
use Laravel\Octane\Swoole\SwooleExtension;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class StartServerCommand extends \Laravel\Octane\Commands\StartSwooleCommand
{
    public $signature = 'server:start
        {--host=127.0.0.1 : The IP address the server should bind to}
        {--port=8000 : The port the server should be available on}
        {--workers=auto : The number of workers that should be available to handle requests}
        {--task-workers=auto : The number of task workers that should be available to handle tasks}
        {--max-requests=500 : The number of requests to process before reloading the server}
        {--watch : Automatically reload the server when the application is modified}';

    private StreamHandler $streamsHandler;

    /**
     * Handle the command.
     *
     * @param \Laravel\Octane\Swoole\ServerProcessInspector $inspector
     * @param \Laravel\Octane\Swoole\ServerStateFile $serverStateFile
     * @param \Laravel\Octane\Swoole\SwooleExtension $extension
     * @return int
     */
    public function handle(
        ServerProcessInspector $inspector,
        ServerStateFile        $serverStateFile,
        SwooleExtension        $extension
    )
    {
        if (!$extension->isInstalled()) {
            $this->error('The Swoole extension is missing.');

            return 1;
        }

        if ($inspector->serverIsRunning()) {
            $this->error('Server is already running.');

            return 1;
        }

        if (config('octane.swoole.ssl', false) === true && !defined('SWOOLE_SSL')) {
            $this->error('You must configure Swoole with `--enable-openssl` to support ssl.');

            return 1;
        }

        $this->writeServerStateFile($serverStateFile, $extension);

        $this->forgetEnvironmentVariables();

        $server = tap(new Process([
            (new PhpExecutableFinder)->find(), 'swoole-server', $serverStateFile->path(),
        ], realpath(__DIR__ . '/../../../../../bin'), [
            'APP_ENV' => app()->environment(),
            'APP_BASE_PATH' => base_path(),
            'LARAVEL_OCTANE' => 1,
        ]))->start();

        $this->streamsHandler = new StreamHandler($this->output, $this->getLaravel());

        return $this->runServer($server, $inspector, 'swoole');
    }

    public function handleStream($stream, $verbosity = null)
    {
        match ($stream['type']) {
            'request' => $this->requestInfo($stream, $verbosity),
            'throwable' => $this->throwableInfo($stream, $verbosity),
            'shutdown' => $this->shutdownInfo($stream, $verbosity),
            default => $this->streamsHandler->shouldBeSkipped($stream) ?: $this->streamsHandler->handle($stream),
        };
    }
}
