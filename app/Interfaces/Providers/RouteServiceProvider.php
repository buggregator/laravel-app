<?php

declare(strict_types=1);

namespace Interfaces\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Spatie\RouteAttributes\RouteRegistrar;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/dashboard';

    // protected $namespace = 'Interfaces\\Http\\Controllers';

    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        $this->registerRoutesFromAttributes();
    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    private function registerRoutesFromAttributes(): void
    {
        if (! $this->shouldRegisterRoutes()) {
            return;
        }

        $routeRegistrar = (new RouteRegistrar(app()->router))
            ->useRootNamespace('')
            ->useMiddleware(config('route-attributes.middleware') ?? []);

        collect(config('route-attributes.directories'))
            ->each(
                fn (string $directory) => $routeRegistrar->registerDirectory($directory)
            );
    }

    private function shouldRegisterRoutes(): bool
    {
        if (! config('route-attributes.enabled')) {
            return false;
        }

        if ($this->app->routesAreCached()) {
            return false;
        }

        return true;
    }
}
