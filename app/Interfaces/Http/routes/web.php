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

Route::get('/mail', Modules\Smtp\Http\Controllers\ListAction::class)->name('smtp');
Route::get('/mail/{uuid}', Modules\Smtp\Http\Controllers\ShowAction::class)->name('smtp.show');
Route::delete('/mail/{uuid}', Modules\Smtp\Http\Controllers\DeleteAction::class);
Route::get('/mail/{uuid}/html', Modules\Smtp\Http\Controllers\ShowHtmlAction::class);

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
