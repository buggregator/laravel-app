<?php
declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Swoole\Http\Request;
use Swoole\WebSocket\Server;
use Swoole\WebSocket\Frame;
use Swoole\Table;

class WebsocketServer extends Command
{
    protected $signature = 'websocket:serve
                    {--host= : The IP address the server should bind to}
                    {--port= : The port the server should be available on}';

    protected $description = 'Run a websocket server';

    public function handle()
    {
        $port = $this->option('port') ?: config('websocket.port');
        $host = $this->option('host') ?: config('websocket.host');
        $server = new Server($host, (int)$port);

        $server->set([
            'log_level' => SWOOLE_LOG_WARNING,
        ]);

        $connections = new Table(1024);
        $connections->column('client', Table::TYPE_INT, 4);
        $connections->create();

        $server->on('Message', function (Server $server, Frame $frame) use ($connections) {
            foreach ($connections as $client) {
                if ($client['client'] == $frame->fd) {
                    continue;
                }

                try {
                    $server->push($client['client'], str_replace('&nbsp;', ' ', $frame->data));
                } catch (\Throwable $e) {
                    $this->error($e->getMessage());
                }
            }
        });

        $server->on("start", function (Server $server) use ($host, $port) {
            $this->info(sprintf("Swoole WebSocket Server is started at http://%s:%s", $host, $port));
        });

        $server->on('open', function (Server $server, Request $request) use ($connections) {
            $this->info("connection open: {$request->fd}");

            $connections->set((string)$request->fd, ['client' => $request->fd]);
        });

        $server->on('close', function (Server $server, int $fd) use ($connections) {
            $connections->del((string)$fd);
            $this->info("connection close: {$fd}");
        });

        $server->on('disconnect', function (Server $server, int $fd) use ($connections) {
            $connections->del((string)$fd);
            $this->info("connection disconnect: {$fd}");
        });

        $server->start();
    }
}
