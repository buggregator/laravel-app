<?php

declare(strict_types=1);

namespace Modules\Inspector\Interfaces\Http\Controllers;

use App\Contracts\Query\QueryBus;
use Illuminate\Http\Request;
use Modules\Events\Interfaces\Http\ActionMap;
use Spatie\RouteAttributes\Attributes\Get;

class ListAction extends \Modules\Events\Interfaces\Http\Controllers\ListAction
{
    #[Get(uri: '/inspector', name: 'inspector')]
    public function __invoke(Request $request, QueryBus $bus, ActionMap $actionMap)
    {
        $request->offsetSet('type', 'inspector');

        return parent::__invoke($request, $bus, $actionMap);
    }
}
