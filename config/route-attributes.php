<?php

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Interfaces\Http\Middleware\EncryptCookies;
use Interfaces\Http\Middleware\HandleInertiaRequests;
use Laravel\Jetstream\Http\Middleware\AuthenticateSession;

return [
    /*
     *  Automatic registration of routes will only happen if this setting is `true`
     */
    'enabled' => true,

    /*
     * Controllers in these directories that have routing attributes
     * will automatically be registered.
     */
    'directories' => [
        base_path('app/Interfaces'),
        base_path('app/Modules'),
    ],

    /**
     * This middleware will be applied to all routes.
     */
    'middleware' => [
        'web'
    ],
];
