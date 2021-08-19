<?php

use App\Http\Controllers\Ray;
use App\Http\Controllers\Sentry;
use App\Http\Controllers\Slack;
use App\Http\Controllers\ShowEventsAction;
use Illuminate\Support\Facades\Route;
use Ramsey\Uuid\Uuid;

Route::get('/', ShowEventsAction::class);

Route::post('/', Ray\StoreEventAction::class);
Route::get('/_availability_check', Ray\CheckAvailabilityAction::class);
Route::get('/locks/{hash}', Ray\Locks\CheckAction::class);
Route::delete('/locks/{hash}', Ray\Locks\DeleteAction::class);

Route::delete('/events', function (\App\EventsRepository $events) {
    $events->clear();
});
Route::delete('/event/{id}', function (\App\EventsRepository $events, string $id) {
    $events->delete(Uuid::fromString($id)->toString());
});

Route::post('api/{projectId}/store', Sentry\StoreEventAction::class);

Route::post('api/{projectId}/envelope', function (\Illuminate\Http\Request $request) {

});

Route::post('slack', Slack\StoreEventAction::class);
