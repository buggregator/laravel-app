<?php

declare(strict_types=1);

namespace Modules\Sentry\Interfaces\Http\Controllers;

use App\Contracts\Command\CommandBus;
use App\Domain\ValueObjects\Uuid;
use Modules\Events\Interfaces\Http\Controllers\DeleteEventAction;
use Spatie\RouteAttributes\Attributes\Delete;

class DeleteAction extends DeleteEventAction
{
    #[Delete(uri: '/sentry/{uuid}', name: 'sentry.delete')]
    public function __invoke(CommandBus $bus, Uuid $uuid)
    {
        parent::__invoke($bus, $uuid);

        return redirect()->route('sentry', status: 303);
    }
}
