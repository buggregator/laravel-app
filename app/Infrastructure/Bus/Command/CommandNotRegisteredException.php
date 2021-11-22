<?php

declare(strict_types=1);

namespace Infrastructure\Bus\Command;

use App\Contracts\Command\Command;
use RuntimeException;

final class CommandNotRegisteredException extends RuntimeException
{
    public function __construct(Command $command)
    {
        $commandClass = get_class($command);

        parent::__construct("The command <$commandClass> hasn't a command handler associated");
    }
}
