<?php
declare(strict_types=1);

namespace App\Commands;

use App\Contracts\Command\Command;
use App\Domain\ValueObjects\Uuid;

class DeleteEvent implements Command
{
    /**
     * TODO: should be read only
     */
    public function __construct(
        public Uuid $uuid
    )
    {
    }
}
