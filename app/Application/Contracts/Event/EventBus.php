<?php

declare(strict_types=1);

namespace App\Contracts\Event;

interface EventBus
{
    public function publish(object ...$events): void;
}
