<?php

declare(strict_types=1);

namespace Modules\Ray\Handlers;

use Modules\Ray\Contracts\EventHandler;

class ReplaceNoBreakSpaceUnicode implements EventHandler
{
    public function handle(array $event): array
    {
        return json_decode(str_replace('&nbsp;', ' ', json_encode($event)), true);
    }
}
