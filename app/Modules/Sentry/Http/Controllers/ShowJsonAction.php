<?php
declare(strict_types=1);

namespace Modules\Sentry\Http\Controllers;

use App\Contracts\EventsRepository;
use Inertia\Inertia;
use Interfaces\Http\Controllers\Controller;
use Ramsey\Uuid\UuidInterface;

class ShowJsonAction extends Controller
{
    public function __invoke(EventsRepository $events, UuidInterface $uuid)
    {
        $event = $events->find($uuid);
        if (!$event) {
            abort(404);
        }

        return response()->json($event);
    }
}
