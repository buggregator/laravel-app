<?php

declare(strict_types=1);

namespace Modules\Events\Application\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class EventCollection extends ResourceCollection
{
    public $collects = EventResource::class;
}
