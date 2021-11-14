<?php
declare(strict_types=1);

namespace Modules\Inspector\Http\Controllers;

use App\Commands\HandleReceivedEvent;
use App\Contracts\Command\CommandBus;
use Illuminate\Http\Request;
use Interfaces\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StoreEventAction extends Controller
{
    public function __invoke(
        Request    $request,
        CommandBus $commands,
    ): void
    {
        $data = json_decode(base64_decode($request->getContent()), true)
            ?? throw new HttpException('Invalid data');

        $commands->dispatch(
            new HandleReceivedEvent('inspector', $data, true)
        );
    }
}
