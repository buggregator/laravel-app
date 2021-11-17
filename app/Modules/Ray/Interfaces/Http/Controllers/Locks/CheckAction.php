<?php

declare(strict_types=1);

namespace Modules\Ray\Interfaces\Http\Controllers\Locks;

use Illuminate\Contracts\Cache\Repository;
use Interfaces\Http\Controllers\Controller;
use Spatie\RouteAttributes\Attributes\Get;

class CheckAction extends Controller
{
    #[Get(uri: '/locks/{hash}', name: 'ray.lock.check')]
    public function __invoke(Repository $cache, string $hash)
    {
        $lock = $cache->get($hash);

        if (! $lock) {
            abort(404);
        }

        if (is_array($lock)) {
            $cache->forget($hash);

            return $lock;
        }

        return ['active' => true, 'stop_execution' => false];
    }
}
