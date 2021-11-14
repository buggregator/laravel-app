<?php
declare(strict_types=1);

namespace Modules\Events\Application\Commands\ClearEvents;

class Command implements \App\Contracts\Command\Command
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
