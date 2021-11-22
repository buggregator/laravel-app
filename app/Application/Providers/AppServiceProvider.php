<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\VarDumper\Dumper\CliDumper;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        CliDumper::$defaultOutput = 'php://stderr';
    }
}
