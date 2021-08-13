<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Illuminate\Http\Request;

Route::get('/', function () {
    return Inertia::render('Logs');
});

Route::post('/', function (Request $request) {
    $type = $request->input('payloads.0.type');

    if ($type === 'create_lock') {
        $hash = $request->input('payloads.0.content.name');
        Cache::put($hash, 1, now()->addMinutes(5));
    }

    $client = new \WebSocket\Client(sprintf('ws://%s:%d/', config('websocket.host'), config('websocket.port')));
    $client->send(json_encode($request->all()));

    return true;
});

Route::get('/_availability_check', function () {
    return response('', env('HTTP_CHECK_STATUS', 200));
});

Route::get('/locks/{hash}', function (string $hash) {
    $lock = Cache::get($hash);

    if (!$lock) {
        abort(404);
    }

    if (is_array($lock)) {
        Cache::forget($hash);

        return $lock;
    }

    return ['active' => true, 'stop_execution' => false];
});

Route::delete('/locks/{hash}', function (Request $request, string $hash) {
    Cache::put($hash, [
        'active' => false,
        'stop_execution' => (bool)$request->stop_execution,
    ]);
});
