<?php
declare(strict_types=1);

namespace Modules\Events\Http\Controllers;

use App\Contracts\EventsRepository;
use Interfaces\Http\Controllers\Controller;

class ClearEventsAction extends Controller
{
    public function __invoke(EventsRepository $events)
    {
        $events->clear();
    }
}
