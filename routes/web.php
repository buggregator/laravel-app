<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Logs');
});

Route::post('/', function (\Illuminate\Http\Request $request, \Symfony\Component\Console\Output\ConsoleOutput $output) {
    $client = new \WebSocket\Client(sprintf('ws://%s:%d/', config('websocket.host'), config('websocket.port')));
    $client->send(json_encode($request->all()));

    return true;
});

Route::get('/_availability_check', function () {
    return response('', env('HTTP_CHECK_STATUS', 200));
});
