<?php
declare(strict_types=1);

namespace Modules\Sentry\Http\Controllers;

use App\Commands\DeleteEvent;
use App\Contracts\Command\CommandBus;
use App\Domain\ValueObjects\Uuid;
use Interfaces\Http\Controllers\Controller;
use Ramsey\Uuid\UuidInterface;

class DeleteAction extends Controller
{
    public function __invoke(CommandBus $bus, UuidInterface $uuid)
    {
        $bus->dispatch(new DeleteEvent(new Uuid($uuid)));

        return redirect()->route('sentry', status: 303);
    }
}
