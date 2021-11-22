<?php

declare(strict_types=1);

namespace Modules\Ray\Interfaces\Http\Controllers\Locks;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Http\Request;
use Interfaces\Http\Controllers\Controller;
use Spatie\RouteAttributes\Attributes\Delete;

class DeleteAction extends Controller
{
    #[Delete(uri: '/locks/{hash}', name: 'ray.lock.delete')]
    public function __invoke(Request $request, Repository $cache, string $hash)
    {
        $cache->put($hash, [
            'active' => false,
            'stop_execution' => (bool) $request->stop_execution,
        ]);
    }
}
