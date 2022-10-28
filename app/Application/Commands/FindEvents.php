<?php

declare(strict_types=1);

namespace App\Commands;

use App\Contracts\Query\Query;

final class FindEvents extends AskEvents implements Query
{
    // TODO: use readonly property
    public function __construct(
        public ?string $type = null,
        public ?int $projectId = null,
        public ?int $transactionId = null,
        public ?int $offset = null,
        public ?int $limit = null,
    ) {
        parent::__construct($this->type, $this->projectId, $this->transactionId);
    }
}
