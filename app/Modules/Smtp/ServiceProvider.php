<?php
declare(strict_types=1);

namespace Modules\Smtp;

use Modules\Smtp\Console\Commands\StartServerCommand;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->commands([
            StartServerCommand::class
        ]);
    }
}
