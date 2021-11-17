<?php

declare(strict_types=1);

namespace App\Commands;

use App\Contracts\Command\Command;

class ClearEvents implements Command
{
    // TODO: use readonly property
    public function __construct(
        public ?string $type = null
    ) {
    }
}
