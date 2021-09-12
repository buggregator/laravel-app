<?php

use Interfaces\Websocket\Controllers\ConnectAction;
use Interfaces\Websocket\Middleware\ChannelJoined;
use Modules\Events\Http\Controllers as Events;
use Modules\Ray\Http\Controllers as Ray;
use Modules\Sentry\Http\Controllers as Sentry;
use Modules\Monolog\Http\Controllers\Slack;
use Interfaces\Http\Controllers\ShowEventsAction;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowEventsAction::class);

Route::get('ws', ConnectAction::class)->middleware(ChannelJoined::class);
Route::post('/', Ray\StoreEventAction::class);
Route::get('/_availability_check', Ray\CheckAvailabilityAction::class);
Route::get('/locks/{hash}', Ray\Locks\CheckAction::class);
Route::delete('/locks/{hash}', Ray\Locks\DeleteAction::class);

Route::delete('/events', Events\ClearEventsAction::class);
Route::delete('/event/{id}', Events\DeleteEventAction::class);

Route::post('api/{projectId}/store', Sentry\StoreEventAction::class);
Route::post('api/{projectId}/envelope', function (\Illuminate\Http\Request $request) {
});

Route::post('slack', Slack\StoreEventAction::class);
