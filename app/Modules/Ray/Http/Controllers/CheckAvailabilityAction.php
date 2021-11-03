<?php
declare(strict_types=1);

namespace Modules\Ray\Http\Controllers;

use Interfaces\Http\Controllers\Controller;

class CheckAvailabilityAction extends Controller
{
    public function __invoke()
    {
        return response('', env('HTTP_CHECK_STATUS', 200));
    }
}
