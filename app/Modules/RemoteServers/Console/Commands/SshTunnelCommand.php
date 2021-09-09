<?php
declare(strict_types=1);

namespace Modules\RemoteServers\Console\Commands;

use Illuminate\Console\Command;

class SshTunnelCommand extends Command
{
    protected $signature = 'ssh:tunnel
                    {--host= : The IP address the server should connect to}
                    {--port= : The port the server should be available on}
                    {--user= : The user}
                    {--key_path= : Ssh key path}';

    protected $description = 'Run SMTP server';

    public function handle()
    {
        
    }
}
