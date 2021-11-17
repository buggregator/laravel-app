<?php

declare(strict_types=1);

namespace Modules\Inspector\Interfaces\Http\Controllers;

use App\Contracts\Command\CommandBus;
use App\Domain\ValueObjects\Uuid;
use Modules\Events\Interfaces\Http\Controllers\DeleteEventAction;
use Spatie\RouteAttributes\Attributes\Delete;

class DeleteAction extends DeleteEventAction
{
    #[Delete(uri: '/inspector/{uuid}', name: 'inspector.delete')]
    public function __invoke(CommandBus $bus, Uuid $uuid)
    {
        parent::__invoke($bus, $uuid);

        return redirect()->route('inspector', status: 303);
    }
}
