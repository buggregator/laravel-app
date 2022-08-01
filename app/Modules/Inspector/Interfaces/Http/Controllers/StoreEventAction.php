<?php

declare(strict_types=1);

namespace Modules\Inspector\Interfaces\Http\Controllers;

use App\Commands\FindProjectByName;
use App\Commands\HandleReceivedEvent;
use App\Contracts\Command\CommandBus;
use App\Contracts\Query\QueryBus;
use Illuminate\Http\Request;
use Interfaces\Http\Controllers\Controller;
use Spatie\RouteAttributes\Attributes\Post;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StoreEventAction extends Controller
{
    #[Post(uri: 'inspector', name: 'inspector.event.store')]
    public function __invoke(
        Request $request,
        CommandBus $commands,
        QueryBus $queryBus,
    ): void {
        $data = json_decode(base64_decode($request->getContent()), true)
            ?? throw new HttpException(500, 'Invalid data');

        $type = $data[0]['type'] ?? 'unknown';

        if ($type !== 'request') {
            abort(403);
        }

        $project = $queryBus->ask(new FindProjectByName('default'));
        $projectId = $project->getId();
        $commands->dispatch(
            new HandleReceivedEvent((int) $projectId, 'inspector', $data, true)
        );
    }
}
