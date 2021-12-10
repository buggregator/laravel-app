<?php

declare(strict_types=1);

namespace Modules\Ray\Interfaces\Http\Controllers;

use App\Commands\FindEventByUuid;
use App\Contracts\Query\QueryBus;
use App\Domain\ValueObjects\Uuid;
use App\Exceptions\EntityNotFoundException;
use Illuminate\Support\Arr;
use Interfaces\Http\Controllers\Controller;
use Spatie\RouteAttributes\Attributes\Get;

class ShowHtmlAction extends Controller
{
    #[Get(uri: '/ray/{uuid}/html', name: 'ray.show.html')]
    public function __invoke(QueryBus $bus, Uuid $uuid): ?string
    {
        try {
            $event = $bus->ask(new FindEventByUuid($uuid));
        } catch (EntityNotFoundException $e) {
            abort(404, $e->getMessage());
        }

        $type = Arr::get($event->getPayload()->toArray(), 'payloads.0.type');
        if ($type !== 'mailable') {
            abort(404, 'Event type should be mailable.');
        }

        $html = Arr::get($event->getPayload()->toArray(), 'payloads.0.content.html');

        if ($html === null) {
            abort(500, 'HTML is empty');
        }

        return $html;
    }
}
