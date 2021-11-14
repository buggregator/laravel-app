<?php
declare(strict_types=1);

namespace App\Commands;

use App\Contracts\Command\Command;

class ClearEvents implements Command
{
    /**
     * TODO: should be read only
     */
    public function __construct(
        public ?string $type = null
    )
    {
    }
}
