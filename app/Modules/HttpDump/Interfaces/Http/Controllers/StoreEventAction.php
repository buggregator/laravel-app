<?php

declare(strict_types=1);

namespace Modules\HttpDump\Interfaces\Http\Controllers;

use App\Commands\FindProjectByName;
use App\Commands\HandleReceivedEvent;
use App\Contracts\Command\CommandBus;
use App\Contracts\Query\QueryBus;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Interfaces\Http\Controllers\Controller;
use Spatie\RouteAttributes\Attributes\Any;
use Spatie\RouteAttributes\Attributes\Where;
use Symfony\Bridge\PsrHttpMessage\Factory\UploadedFile;
use Symfony\Component\Console\Output\ConsoleOutput;

class StoreEventAction extends Controller
{
    #[Any(uri: 'httpdump/store/{extraData?}', name: 'httpdump.event.store')]
    #[Where('extraData', '(.*)')]
    public function __invoke(
        Request $request,
        CommandBus $commands,
        QueryBus $queryBus,
        ConsoleOutput $output,
    ): void {
        $project = $queryBus->ask(new FindProjectByName('default'));
        $projectId = $project->getId();

        $data = $this->prepareRequest($request);
        $commands->dispatch(
            new HandleReceivedEvent(
                (int) $projectId,
                'httpdump',
                $data,
                true
            )
        );
    }

    private function prepareRequest(Request $request)
    {
        return [
            'received_at' => now()->toDateTimeString(),
            'request' => [
                'method' => $request->getMethod(),
                'uri' => $request->getRequestUri(),
                'headers' => $request->headers->all(),
                'body' => $request->getContent(),
                'query' => $request->query->all(),
                'post' => $this->getPostData($request),
            ],
        ];
    }

    private function getPostData(Request $request): array
    {
        $contentType = current(Arr::get($request->headers->all(), 'content-type', [])) ?: 'no content type';

        if ($contentType === 'application/x-www-form-urlencoded') {
            return $request->request->all();
        }

        if ($contentType === 'application/json') {
            return json_decode($request->getContent(), true);
        }

        if (str_starts_with($contentType, 'multipart/form-data') && $request->allFiles() !== []) {
            return array_map(function (UploadedFile $file) {
                return [
                    'originalName' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ];
            }, collect($request->files->all())->flatten()->toArray());
        }

        return [];
    }
}
