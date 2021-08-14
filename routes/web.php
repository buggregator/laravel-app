<?php

use App\Http\Controllers\Ray\CheckAvailabilityAction;
use App\Http\Controllers\Ray\StoreEventAction;
use App\Http\Controllers\ShowEventsAction;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ray\Locks;

Route::get('/', ShowEventsAction::class);
Route::post('/', StoreEventAction::class);
Route::get('/_availability_check', CheckAvailabilityAction::class);

Route::get('/locks/{hash}', Locks\CheckAction::class);
Route::delete('/locks/{hash}', Locks\DeleteAction::class);
