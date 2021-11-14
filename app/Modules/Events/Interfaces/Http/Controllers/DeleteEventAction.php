<?php
declare(strict_types=1);

namespace Modules\Events\Interfaces\Http\Controllers;

use App\Commands\DeleteEvent;
use App\Contracts\Command\CommandBus;
use App\Domain\ValueObjects\Uuid;
use Interfaces\Http\Controllers\Controller;
use Ramsey\Uuid\UuidInterface;

class DeleteEventAction extends Controller
{
    public function __invoke(CommandBus $bus, UuidInterface $uuid): void
    {
        $bus->dispatch(new DeleteEvent(new Uuid($uuid)));
    }
}
