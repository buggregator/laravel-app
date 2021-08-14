<?php
declare(strict_types=1);

namespace App\Http\Controllers\Ray\Locks;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Cache\Repository;

class CheckAction extends Controller
{
    public function __invoke(Repository $cache, string $hash)
    {
        $lock = $cache->get($hash);

        if (!$lock) {
            abort(404);
        }

        if (is_array($lock)) {
            $cache->forget($hash);
            return $lock;
        }

        return ['active' => true, 'stop_execution' => false];
    }
}
