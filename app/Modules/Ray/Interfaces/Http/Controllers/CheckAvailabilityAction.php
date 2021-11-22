<?php

declare(strict_types=1);

namespace Modules\Ray\Interfaces\Http\Controllers;

use Interfaces\Http\Controllers\Controller;
use Spatie\RouteAttributes\Attributes\Get;

class CheckAvailabilityAction extends Controller
{
    #[Get(uri: '/_availability_check', name: 'ray.availability_check')]
    public function __invoke()
    {
        return response('', config('server.ray.http_check_status'));
    }
}
