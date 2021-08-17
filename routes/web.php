<?php

use App\Http\Controllers\Ray;
use App\Http\Controllers\Sentry;
use App\Http\Controllers\ShowEventsAction;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowEventsAction::class);

Route::post('/', Ray\StoreEventAction::class);
Route::get('/_availability_check', Ray\CheckAvailabilityAction::class);
Route::get('/locks/{hash}', Ray\Locks\CheckAction::class);
Route::delete('/locks/{hash}', Ray\Locks\DeleteAction::class);

Route::post('api/{projectId}/store', Sentry\StoreEventAction::class);

Route::post('api/{projectId}/envelope', function (\Illuminate\Http\Request $request) {

});
