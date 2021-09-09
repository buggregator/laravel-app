<?php
declare(strict_types=1);

namespace Modules\Monolog\Http\Controllers\Slack;

use App\Events\EventReceived;
use Illuminate\Contracts\Events\Dispatcher;
use Interfaces\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class StoreEventAction extends Controller
{
    public function __invoke(
        Request    $request,
        Dispatcher $events,
    ): void
    {
        $events->dispatch(new EventReceived(
            ['type' => 'slack', 'uuid' => Uuid::uuid4()->toString(), 'data' => $request->all()]
        ));
    }
}

