<?php
declare(strict_types=1);

namespace App\Http\Controllers\Ray;

use App\Http\Controllers\Controller;

class CheckAvailabilityAction extends Controller
{
    public function __invoke()
    {
        return response('', env('HTTP_CHECK_STATUS', 200));
    }
}
