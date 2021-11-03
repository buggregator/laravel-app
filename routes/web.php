<?php

use Interfaces\Websocket\Controllers\ConnectAction;
use Interfaces\Websocket\Middleware\ChannelJoined;
use Modules\Events\Http\Controllers as Events;
use Modules\Ray\Http\Controllers as Ray;
use Modules\Sentry\Http\Controllers as Sentry;
use Modules\Inspector\Http\Controllers as Inspector;
use Modules\Smtp\Http\Controllers as Smtp;
use Modules\Terminal\Http\Controllers as Terminal;
use Modules\Monolog\Http\Controllers\Slack;
use Illuminate\Support\Facades\Route;

// Events
Route::get('/', Events\ListAction::class);
Route::delete('/events', Events\ClearEventsAction::class);
Route::delete('/event/{uuid}', Events\DeleteEventAction::class);

Route::get('ws', ConnectAction::class)->middleware(ChannelJoined::class);

// Ray
Route::post('/', Ray\StoreEventAction::class);
Route::get('/_availability_check', Ray\CheckAvailabilityAction::class);
Route::get('/locks/{hash}', Ray\Locks\CheckAction::class);
Route::delete('/locks/{hash}', Ray\Locks\DeleteAction::class);


// Monolog slack
Route::post('slack', Slack\StoreEventAction::class);

// SMTP
Route::get('/mail', Smtp\ListAction::class)->name('smtp');
Route::get('/mail/{uuid}', Smtp\ShowAction::class)->name('smtp.show');
Route::delete('/mail/{uuid}', Smtp\DeleteAction::class);
Route::get('/mail/{uuid}/html', Smtp\ShowHtmlAction::class);

// Inspector
Route::post('inspector', Inspector\StoreEventAction::class);
Route::get('/inspector', Inspector\ListAction::class)->name('inspector');
Route::get('/inspector/{uuid}', Inspector\ShowAction::class)->name('inspector.show');

// Sentry
Route::post('api/{projectId}/store', Sentry\StoreEventAction::class);
Route::post('api/{projectId}/envelope', function (\Illuminate\Http\Request $request) {});

// Terminal
Route::get('/terminal', Terminal\ListAction::class)->name('terminal');
