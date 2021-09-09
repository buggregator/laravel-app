<?php
declare(strict_types=1);

namespace Modules\Events;

use App\Contracts\EventsRepository as EventsRepositoryContract;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->bind(EventsRepositoryContract::class, EventsRepository::class);
    }
}
