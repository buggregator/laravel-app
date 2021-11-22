<?php

declare(strict_types=1);

namespace Modules\Events\Application\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => (string) $this->getUuid(),
            'type' => $this->getType(),
            'payload' => $this->getPayload()->toArray(),
            'timestamp' => $this->getDate()->getTimestamp(),
        ];
    }
}
