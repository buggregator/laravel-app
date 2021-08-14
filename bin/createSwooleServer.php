<?php

$config = $serverState['octaneConfig'];

try {
    $host = $serverState['host'] ?? '127.0.0.1';
    $protocol = ($config['swoole']['ssl'] ?? false)
        ? SWOOLE_SOCK_TCP | SWOOLE_SSL
        : SWOOLE_SOCK_TCP;

    $server = new Swoole\WebSocket\Server(
        $host,
        $serverState['port'] ?? '23517',
        SWOOLE_PROCESS,
        $protocol,
    );
} catch (Throwable $e) {
    Laravel\Octane\Stream::shutdown($e);

    exit(1);
}

$server->set(array_merge(
    $serverState['defaultServerOptions'],
    $config['swoole']['options'] ?? []
));

return $server;
