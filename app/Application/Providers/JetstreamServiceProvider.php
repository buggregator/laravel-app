<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Fortify;

class JetstreamServiceProvider extends \Laravel\Jetstream\JetstreamServiceProvider
{
    protected function bootInertia()
    {
        Fortify::loginView(function () {
            return Inertia::render('Auth/Login', [
                'canResetPassword' => Route::has('password.request'),
                'status' => session('status'),
            ]);
        });
    }
}
