<?php

declare(strict_types=1);

namespace App\Contracts\Command;

interface CommandBus
{
    public function dispatch(Command $command);
}
