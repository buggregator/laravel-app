<?php
declare(strict_types=1);

namespace Modules\Events\Interfaces\Http\Controllers;

use App\Commands\ClearEvents;
use App\Contracts\Command\CommandBus;
use Interfaces\Http\Controllers\Controller;

class ClearEventsAction extends Controller
{
    public function __invoke(CommandBus $bus): void
    {
        $bus->dispatch(new ClearEvents());
    }
}
