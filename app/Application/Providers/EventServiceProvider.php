<?php

namespace App\Providers;

use App\Events\EventReceived;
use App\Events\Websocket\Joined;
use App\Listeners\Event\SendEventsAfterChannelJoin;
use App\Listeners\Event\SendToConsole;
use App\Listeners\Event\StoreEventToDatabase;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        EventReceived::class => [
            SendToConsole::class,
            StoreEventToDatabase::class,
        ],
        Joined::class => [
            SendEventsAfterChannelJoin::class
        ]
    ];
}
