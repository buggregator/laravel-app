<?php

declare(strict_types=1);

namespace Modules\Sentry\Interfaces\Http\Controllers;

use App\Contracts\Query\QueryBus;
use App\Domain\ValueObjects\Uuid;
use Illuminate\Http\Request;
use Modules\Events\Interfaces\Http\ActionMap;
use Spatie\RouteAttributes\Attributes\Get;

class ShowAction extends \Modules\Events\Interfaces\Http\Controllers\ShowAction
{
    #[Get(uri: '/sentry/{uuid}', name: 'sentry.show')]
    public function __invoke(Request $request, QueryBus $bus, ActionMap $actionMap, Uuid $uuid)
    {
        $request->offsetSet('type', 'sentry');

        return parent::__invoke($request, $bus, $actionMap, $uuid);
    }
}
