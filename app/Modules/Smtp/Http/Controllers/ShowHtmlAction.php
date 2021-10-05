<?php
declare(strict_types=1);

namespace Modules\Smtp\Http\Controllers;

use App\Contracts\EventsRepository;
use Illuminate\Support\Arr;
use Interfaces\Http\Controllers\Controller;

class ShowHtmlAction extends Controller
{
    public function __invoke(EventsRepository $events, string $uuid)
    {
        $event = $events->find($uuid);
        if (!$event) {
            abort(404);
        }

        return Arr::get($event, 'data.html');
    }
}
