<?php

declare(strict_types=1);

namespace Modules\Events\Interfaces\Http\Controllers;

use App\Commands\DeleteEvent;
use App\Contracts\Command\CommandBus;
use App\Domain\ValueObjects\Uuid;
use App\Exceptions\EntityNotFoundException;
use Interfaces\Http\Controllers\Controller;
use Spatie\RouteAttributes\Attributes\Delete;

class DeleteEventAction extends Controller
{
    #[Delete(uri: '/event/{uuid}', name: 'event.delete')]
    public function __invoke(CommandBus $bus, Uuid $uuid)
    {
        try {
            $bus->dispatch(new DeleteEvent($uuid));
        } catch (EntityNotFoundException $e) {
            abort(404, $e->getMessage());
        }
    }
}
