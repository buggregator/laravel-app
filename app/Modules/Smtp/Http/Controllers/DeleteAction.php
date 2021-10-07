<?php
declare(strict_types=1);

namespace Modules\Smtp\Http\Controllers;

use App\Contracts\EventsRepository;
use Interfaces\Http\Controllers\Controller;
use Ramsey\Uuid\UuidInterface;

class DeleteAction extends Controller
{
    public function __invoke(EventsRepository $events, UuidInterface $uuid)
    {
        $event = $events->find($uuid);
        if (!$event) {
            abort(404);
        }

        $events->delete($uuid);

        return redirect()->route('smtp', status: 303);
    }
}
