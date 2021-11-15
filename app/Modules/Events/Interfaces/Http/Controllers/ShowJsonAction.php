<?php
declare(strict_types=1);

namespace Modules\Events\Interfaces\Http\Controllers;

use App\Commands\FindEventByUuid;
use App\Contracts\Query\QueryBus;
use App\Domain\ValueObjects\Uuid;
use Interfaces\Http\Controllers\Controller;
use Ramsey\Uuid\UuidInterface;

class ShowJsonAction extends Controller
{
    public function __invoke(QueryBus $bus, UuidInterface $uuid)
    {
        $event = $bus->ask(new FindEventByUuid(new Uuid($uuid)));
        if (!$event) {
            abort(404);
        }

        return $event;
    }
}
