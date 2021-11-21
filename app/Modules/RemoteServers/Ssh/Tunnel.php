<?php

declare(strict_types=1);

namespace Modules\RemoteServers\Ssh;

use Symfony\Component\Process\Process;

final class Tunnel
{
    private ?string $pathToPrivateKey = null;
    protected bool $strictHostChecking = false;
    protected bool $passwordAuthentication = false;

    public function __construct(
        private string $user,
        private string $host,
        private int $port
    ) {
    }

    public function usePrivateKey(string $pathToPrivateKey): self
    {
        $this->pathToPrivateKey = $pathToPrivateKey;

        return $this;
    }

    public function runPortForwarding(int ...$ports): Process
    {
        $process = new Process($this->buildArgs(...$ports));
        $process->setTimeout(60);
        $process->start();

        while ($process->isRunning()) {
            sleep(1);
        }

        if ($process->getExitCode() !== 0) {
            throw new SSHException(
                sprintf(
                    'Unable to create ssh tunnel. Output: %s ErrorOutput: %s',
                    $process->getOutput(),
                    $process->getErrorOutput()
                )
            );
        }

        return $process;
    }

    private function buildArgs(int ...$ports): array
    {
        $args = [
            'ssh',
            '-R',
            '-o ServerAliveInterval=15',
            '-o ExitOnForwardFailure=yes',
            ...array_map(fn (int $port) => "{$port}:localhost:{$port}", $ports),
        ];

        if (! is_null($this->port)) {
            $args[] = "-p {$this->port}";
        }

        if ($this->pathToPrivateKey) {
            $args[] = "-i {$this->pathToPrivateKey}";
        }

        if (! $this->strictHostChecking) {
            $args[] = '-o StrictHostKeyChecking=no';
            $args[] = '-o UserKnownHostsFile=/dev/null';
        }

        if (! $this->passwordAuthentication) {
            $args[] = '-o PasswordAuthentication=no';
        }

        $args[] = $this->host;

        return $args;
    }
}
