<?php

declare(strict_types=1);

namespace Modules\User\Interfaces\Console\Commands;

use Illuminate\Support\Str;

class CreateUserConfig
{
    public function username(): string
    {
        return env('AUTH_USERNAME', 'admin');
    }

    public function password(): string
    {
        return env('AUTH_PASSWORD', Str::random(8));
    }
}
