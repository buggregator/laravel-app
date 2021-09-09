<?php
declare(strict_types=1);

namespace Modules\Events\Http\Controllers;

use App\Contracts\EventsRepository;
use Interfaces\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid;

class DeleteEventAction extends Controller
{
    public function __invoke(EventsRepository $events, string $id)
    {
        $events->delete(Uuid::fromString($id)->toString());
    }
}
