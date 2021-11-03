<?php
declare(strict_types=1);

namespace Modules\Inspector\Http\Controllers;

use App\Events\EventReceived;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;
use Interfaces\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StoreEventAction extends Controller
{
    public function __invoke(
        Request    $request,
        Dispatcher $events,
    ): void
    {
        $data = json_decode(base64_decode($request->getContent()), true)
            ?? throw new HttpException('Invalid data');

        $events->dispatch(new EventReceived('inspector', $data, true));
    }
}
