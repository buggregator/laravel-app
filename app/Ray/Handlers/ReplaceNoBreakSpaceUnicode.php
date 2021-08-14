<?php
declare(strict_types=1);

namespace App\Ray\Handlers;

use App\Ray\Contracts\EventHandler;

class ReplaceNoBreakSpaceUnicode implements EventHandler
{
    public function handle(array $event): array
    {
        return json_decode(str_replace('&nbsp;', ' ', json_encode($event)), true);
    }
}
