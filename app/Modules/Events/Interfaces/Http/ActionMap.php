<?php

declare(strict_types=1);

namespace Modules\Events\Interfaces\Http;

use Illuminate\Contracts\Config\Repository;
use Modules\Events\Exceptions\ActionNotFoundException;

final class ActionMap
{
    public function __construct(
        private Repository $config
    ) {
    }

    public function getForType(string $type, string $action): string
    {
        $action = $this->config->get('server.'.$type.'.http.'.$action);

        if (! $action) {
            throw new ActionNotFoundException(sprintf('Action for event type [%s] not found.', $type));
        }

        return $action;
    }
}
