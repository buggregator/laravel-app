<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\Websocket\Joined;
use App\Listeners\Event\SendToConsole;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\IncommingEvents\Domain\Events\EventWasReceived;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            [SendEmailVerificationNotification::class, 'handle'],
        ],
        EventWasReceived::class => [
            SendToConsole::class,
        ],
        Joined::class => [
            //SendEventsAfterChannelJoin::class
        ],
    ];
}
