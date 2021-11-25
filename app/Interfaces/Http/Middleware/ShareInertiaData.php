<?php

declare(strict_types=1);

namespace Interfaces\Http\Middleware;

use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Modules\User\Application\Resources\UserResource;

class ShareInertiaData
{
    public function handle($request, $next)
    {
        Inertia::share(array_filter([
            'user' => function () use ($request) {
                if (! $request->user()) {
                    return;
                }

                return new UserResource($request->user());
            },
            'errorBags' => function () {
                return collect(optional(Session::get('errors'))->getBags() ?: [])->mapWithKeys(function ($bag, $key) {
                    return [$key => $bag->messages()];
                })->all();
            },
        ]));

        return $next($request);
    }
}
